<?php

namespace App\Http\Controllers;

use App\Enums\SchoolYearStatus;
use App\Enums\StudentSchoolYearStatus;
use App\Enums\UserRole;
use App\Models\SchoolYear;
use App\Models\User;
use App\Services\SchoolYearPromotionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SchoolYearController extends Controller
{
    public function __construct(private SchoolYearPromotionService $promotionService) {}

    public function index(Request $request): View
    {
        $this->authorizeSchoolYearManagement($request->user());

        $schoolYears = SchoolYear::query()
            ->with('nextSchoolYear')
            ->withCount('studentRecords')
            ->orderByDesc('starts_on')
            ->paginate(10);

        return view('school_years.index', ['schoolYears' => $schoolYears]);
    }

    public function create(Request $request): View
    {
        $this->authorizeSchoolYearManagement($request->user());

        return view('school_years.create', [
            'statuses' => SchoolYearStatus::options(),
            'availableNextYears' => SchoolYear::orderByDesc('starts_on')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeSchoolYearManagement($request->user());

        $data = $this->validatedData($request);
        $schoolYear = SchoolYear::create($data);
        $this->syncCurrentStatus($schoolYear, $data['status']);

        return redirect()
            ->route('school-years.index')
            ->with('success', 'Annee scolaire creee avec succes.');
    }

    public function show(Request $request, SchoolYear $schoolYear): View
    {
        $this->authorizeSchoolYearManagement($request->user());

        $schoolYear->load('nextSchoolYear');
        $studentRecords = $schoolYear->studentRecords()
            ->with(['student.parent', 'classroom'])
            ->latest()
            ->get();

        return view('school_years.show', [
            'schoolYear' => $schoolYear,
            'studentRecords' => $studentRecords,
        ]);
    }

    public function edit(Request $request, SchoolYear $schoolYear): View
    {
        $this->authorizeSchoolYearManagement($request->user());

        return view('school_years.edit', [
            'schoolYear' => $schoolYear,
            'statuses' => SchoolYearStatus::options(),
            'availableNextYears' => SchoolYear::whereKeyNot($schoolYear->id)->orderByDesc('starts_on')->get(),
        ]);
    }

    public function update(Request $request, SchoolYear $schoolYear): RedirectResponse
    {
        $this->authorizeSchoolYearManagement($request->user());

        $data = $this->validatedData($request, $schoolYear);
        $schoolYear->update($data);
        $this->syncCurrentStatus($schoolYear, $data['status']);

        return redirect()
            ->route('school-years.index')
            ->with('success', 'Annee scolaire mise a jour avec succes.');
    }

    public function destroy(Request $request, SchoolYear $schoolYear): RedirectResponse
    {
        $this->authorizeSchoolYearManagement($request->user());

        abort_if(
            $schoolYear->studentRecords()->exists(),
            422,
            'Impossible de supprimer une annee scolaire qui contient deja des historiques eleves.'
        );

        $schoolYear->delete();

        return redirect()
            ->route('school-years.index')
            ->with('success', 'Annee scolaire supprimee avec succes.');
    }

    public function preparePromotions(Request $request, SchoolYear $schoolYear): RedirectResponse
    {
        $this->authorizeSchoolYearManagement($request->user());

        if (! $schoolYear->canPreparePromotions()) {
            throw ValidationException::withMessages([
                'promotion' => 'La date de preparation des promotions n est pas encore atteinte.',
            ]);
        }

        $nextSchoolYear = $schoolYear->nextSchoolYear;

        if (! $nextSchoolYear) {
            throw ValidationException::withMessages([
                'promotion' => 'Veuillez definir l annee scolaire suivante avant de preparer les promotions.',
            ]);
        }

        $result = $this->promotionService->preparePromotions($schoolYear);
        $preparedCount = $result['prepared'];
        $skippedCount = $result['skipped'];

        return redirect()
            ->route('school-years.show', $schoolYear)
            ->with('success', "Promotions preparees: {$preparedCount}. Dossiers ignores: {$skippedCount}.");
    }

    private function validatedData(Request $request, ?SchoolYear $schoolYear = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('school_years', 'name')->ignore($schoolYear?->id)],
            'starts_on' => ['required', 'date'],
            'ends_on' => ['required', 'date', 'after:starts_on'],
            'diploma_awarded_on' => ['nullable', 'date'],
            'promotion_opens_on' => ['nullable', 'date'],
            'status' => ['required', Rule::enum(SchoolYearStatus::class)],
            'next_school_year_id' => ['nullable', 'exists:school_years,id'],
            'auto_promote_enabled' => ['sometimes', 'boolean'],
        ]);

        if (filled($data['next_school_year_id'] ?? null) && $schoolYear && (int) $data['next_school_year_id'] === $schoolYear->id) {
            throw ValidationException::withMessages([
                'next_school_year_id' => 'Une annee scolaire ne peut pas se referencer elle-meme comme annee suivante.',
            ]);
        }

        if (
            filled($data['diploma_awarded_on'] ?? null)
            && $data['diploma_awarded_on'] > $data['ends_on']
        ) {
            throw ValidationException::withMessages([
                'diploma_awarded_on' => 'La remise des diplomes doit rester dans la periode de l annee scolaire.',
            ]);
        }

        if (
            filled($data['promotion_opens_on'] ?? null)
            && $data['promotion_opens_on'] < $data['starts_on']
        ) {
            throw ValidationException::withMessages([
                'promotion_opens_on' => 'La preparation des promotions ne peut pas commencer avant l annee scolaire.',
            ]);
        }

        return $data;
    }

    private function syncCurrentStatus(SchoolYear $schoolYear, string $status): void
    {
        if ($status !== SchoolYearStatus::Current->value) {
            return;
        }

        SchoolYear::query()
            ->whereKeyNot($schoolYear->id)
            ->where('status', SchoolYearStatus::Current->value)
            ->update(['status' => SchoolYearStatus::Planned->value]);
    }

    private function authorizeSchoolYearManagement(User $user): void
    {
        $this->authorizeRoles($user, [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);
    }

}
