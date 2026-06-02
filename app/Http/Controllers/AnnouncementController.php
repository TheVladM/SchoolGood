<?php

namespace App\Http\Controllers;

use App\Enums\AnnouncementAudience;
use App\Enums\AnnouncementStatus;
use App\Enums\UserRole;
use App\Models\Announcement;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use App\Notifications\AnnouncementApprovedNotification;
use App\Services\AnnouncementReadService;
use App\Services\AnnouncementRecipientService;
use App\Services\Sms\SmsService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AnnouncementController extends Controller
{
    public function __construct(
        private AnnouncementReadService $readService,
        private AnnouncementRecipientService $recipientService,
        private SmsService $sms,
    ) {}

    public function index(Request $request): View
    {
        $this->authorizeAnnouncementAccess($request->user());

        $query = $this->visibleAnnouncementsQuery($request->user())
            ->with(['author', 'approver', 'classroom']);

        if (
            $request->user()->hasRole(UserRole::Founder)
            && $request->query('filter') === 'pending'
        ) {
            $query->where('status', AnnouncementStatus::PendingApproval->value);
        }

        $announcements = $query->latest()->paginate(10)->withQueryString();

        $pendingCount = $request->user()->hasRole(UserRole::Founder)
            ? Announcement::where('status', AnnouncementStatus::PendingApproval->value)->count()
            : 0;

        return view('announcements.index', [
            'announcements' => $announcements,
            'pendingCount' => $pendingCount,
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Announcement::class);

        return view('announcements.create', [
            'audiences' => $this->audienceOptions($request->user()),
            'classrooms' => Classroom::orderBy('name')->get(),
            'parents' => User::query()
                ->where('role', UserRole::Parent->value)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Announcement::class);

        $data = $this->validatedData($request);
        $data['author_id'] = $request->user()->id;
        $data = $this->applyApprovalWorkflow($request->user(), $data);

        $data['attachments'] = $this->storeAttachments($request);

        Announcement::create($data);

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Message enregistre avec succes.');
    }

    public function show(Request $request, Announcement $announcement): View
    {
        $this->authorizeAnnouncementAccess($request->user());
        $this->ensureAnnouncementVisible($request->user(), $announcement);
        $announcement->load(['author', 'approver', 'classroom', 'reads.user']);

        if ($request->user()->hasRole(UserRole::Parent)) {
            $this->readService->record($announcement, $request->user());
            $announcement->load('reads.user');
        }

        $recipients = $this->recipientService->parents($announcement);
        $readUserIds = $announcement->reads->pluck('user_id');

        return view('announcements.show', [
            'announcement' => $announcement,
            'recipients' => $recipients,
            'readUserIds' => $readUserIds,
            'canSeeReadReceipts' => $request->user()->hasAnyRole([
                UserRole::Founder,
                UserRole::Admin,
                UserRole::Scolarite,
            ]),
        ]);
    }

    public function edit(Request $request, Announcement $announcement): View
    {
        $this->authorize('update', $announcement);

        return view('announcements.edit', [
            'announcement' => $announcement,
            'audiences' => $this->audienceOptions($request->user()),
            'classrooms' => Classroom::orderBy('name')->get(),
            'parents' => User::query()
                ->where('role', UserRole::Parent->value)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $this->authorize('update', $announcement);

        $data = $this->validatedData($request);
        $data = $this->applyApprovalWorkflow($request->user(), $data, $announcement);

        $announcement->update($data);

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Message mis a jour avec succes.');
    }

    public function destroy(Request $request, Announcement $announcement): RedirectResponse
    {
        $this->authorize('delete', $announcement);

        $announcement->delete();

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Message supprime avec succes.');
    }

    public function approve(Request $request, Announcement $announcement): RedirectResponse
    {
        $this->authorize('approve', $announcement);

        $announcement->update([
            'status' => AnnouncementStatus::Approved,
            'approved_by_id' => $request->user()->id,
            'approved_at' => now(),
        ]);

        $announcement->load('author');
        $announcement->author?->notify(new AnnouncementApprovedNotification($announcement));

        foreach ($this->recipientService->parents($announcement) as $parent) {
            $parent->notify(new AnnouncementApprovedNotification($announcement));
            if ($parent->phone) {
                $this->sms->send(
                    $parent->phone,
                    'SchoolGood : nouveau message « '.$announcement->title.' ». Connectez-vous pour le lire.'
                );
            }
        }

        return back()->with('success', 'Message approuvé et prêt pour les familles.');
    }

    public function reject(Request $request, Announcement $announcement): RedirectResponse
    {
        $this->authorize('reject', $announcement);

        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:2000'],
        ]);

        $announcement->update([
            'status' => AnnouncementStatus::Rejected,
            'approved_by_id' => $request->user()->id,
            'approved_at' => now(),
            'rejection_reason' => $data['rejection_reason'],
        ]);

        return back()->with('success', 'Message refusé.');
    }

    private function storeAttachments(Request $request): ?array
    {
        if (! $request->hasFile('attachments')) {
            return null;
        }

        $paths = [];
        foreach ($request->file('attachments') as $file) {
            $paths[] = $file->store('announcements', 'public');
        }

        return $paths;
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'audience' => ['required', Rule::enum(AnnouncementAudience::class)],
            'classroom_id' => ['nullable', 'exists:classrooms,id'],
            'parent_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', UserRole::Parent->value)),
            ],
        ]);

        if (
            $data['audience'] === AnnouncementAudience::Classroom->value
            && blank($data['classroom_id'] ?? null)
        ) {
            throw ValidationException::withMessages([
                'classroom_id' => 'Veuillez choisir la classe destinataire.',
            ]);
        }

        if (
            $data['audience'] === AnnouncementAudience::Parent->value
            && blank($data['parent_id'] ?? null)
        ) {
            throw ValidationException::withMessages([
                'parent_id' => 'Veuillez choisir le parent destinataire.',
            ]);
        }

        if ($data['audience'] === AnnouncementAudience::AllParents->value) {
            $data['classroom_id'] = null;
            $data['parent_id'] = null;
        }

        if ($data['audience'] === AnnouncementAudience::Classroom->value) {
            $data['parent_id'] = null;
        }

        if ($data['audience'] === AnnouncementAudience::Parent->value) {
            $data['classroom_id'] = null;
        }

        return $data;
    }

    private function applyApprovalWorkflow(User $user, array $data, ?Announcement $announcement = null): array
    {
        if ($user->hasAnyRole([UserRole::Founder, UserRole::Admin])) {
            return array_merge($data, [
                'status' => AnnouncementStatus::Approved,
                'approved_by_id' => $user->id,
                'approved_at' => now(),
            ]);
        }

        return array_merge($data, [
            'status' => AnnouncementStatus::PendingApproval,
            'approved_by_id' => null,
            'approved_at' => null,
        ]);
    }

    private function authorizeAnnouncementAccess(User $user): void
    {
        $this->authorizeRoles($user, [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
            UserRole::Parent,
        ]);
    }

    private function visibleAnnouncementsQuery(User $user): Builder
    {
        if ($user->hasRole(UserRole::Parent)) {
            $classroomIds = Student::query()
                ->where('parent_id', $user->id)
                ->pluck('classroom_id')
                ->unique()
                ->filter();

            return Announcement::query()
                ->where('status', AnnouncementStatus::Approved->value)
                ->where(function ($query) use ($user, $classroomIds): void {
                    $query->where('audience', AnnouncementAudience::AllParents->value);

                    if ($classroomIds->isNotEmpty()) {
                        $query->orWhere(function ($subQuery) use ($classroomIds): void {
                            $subQuery
                                ->where('audience', AnnouncementAudience::Classroom->value)
                                ->whereIn('classroom_id', $classroomIds);
                        });
                    }

                    $query->orWhere(function ($subQuery) use ($user): void {
                        $subQuery
                            ->where('audience', AnnouncementAudience::Parent->value)
                            ->where('parent_id', $user->id);
                    });
                });
        }

        if ($user->hasRole(UserRole::Scolarite)) {
            return Announcement::query()->where('author_id', $user->id);
        }

        return Announcement::query();
    }

    private function audienceOptions(User $user): array
    {
        $options = AnnouncementAudience::options();

        if ($user->hasRole(UserRole::Scolarite)) {
            return array_filter(
                $options,
                static fn (string $value) => in_array($value, [
                    AnnouncementAudience::Parent->value,
                    AnnouncementAudience::Classroom->value,
                    AnnouncementAudience::AllParents->value,
                ], true),
                ARRAY_FILTER_USE_KEY
            );
        }

        return $options;
    }

    private function ensureAnnouncementVisible(User $user, Announcement $announcement): void
    {
        abort_unless(
            $this->visibleAnnouncementsQuery($user)->whereKey($announcement->id)->exists(),
            403,
            'Vous ne pouvez pas consulter ce message.'
        );
    }

    private function ensureAnnouncementEditable(User $user, Announcement $announcement): void
    {
        $this->authorizeRoles($user, [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

        if ($user->hasRole(UserRole::Founder)) {
            return;
        }

        abort_unless(
            $announcement->author_id === $user->id,
            403,
            'Vous ne pouvez modifier que vos propres messages.'
        );
    }
}
