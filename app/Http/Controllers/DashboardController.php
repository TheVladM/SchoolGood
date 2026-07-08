<?php

namespace App\Http\Controllers;

use App\Enums\AnnouncementStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\Announcement;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Homework;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $stats = $this->buildStats($user);

        return view('dashboard', [
            'stats' => $stats,
            'recentStudents' => $stats['recentStudents'],
            'recentCourses' => $stats['recentCourses'],
            'recentPayments' => $stats['recentPayments'],
            'recentHomeworks' => $stats['recentHomeworks'] ?? collect(),
            'headline' => $stats['headline'],
            'subheadline' => $stats['subheadline'],
            'pendingActions' => $stats['pendingActions'] ?? [],
            'children' => $stats['children'] ?? collect(),
        ]);
    }

    private function buildStats(User $user): array
    {
        if ($user->hasRole(UserRole::Parent)) {
            $children = Student::with(['classroom', 'payments'])
                ->where('parent_id', $user->id)
                ->latest()
                ->get();

            $childIds = $children->pluck('id');
            $classroomIds = $children->pluck('classroom_id')->unique();

            return [
                'headline' => __('dashboard.headline_parent'),
                'subheadline' => __('dashboard.subheadline_parent'),
                'cards' => [
                    ['label' => __('dashboard.card_children'), 'value' => $children->count()],
                    ['label' => __('dashboard.card_classes'), 'value' => $classroomIds->count()],
                    ['label' => __('dashboard.card_paid_payments'), 'value' => Payment::whereIn('student_id', $childIds)->where('status', PaymentStatus::Paid->value)->count()],
                    ['label' => __('dashboard.card_active_homeworks'), 'value' => Homework::whereIn('classroom_id', $classroomIds)->where('due_date', '>', now())->count()],
                ],
                'children' => $children,
                'recentStudents' => $children,
                'recentCourses' => Course::with(['classroom', 'teacher'])
                    ->whereIn('classroom_id', $classroomIds)
                    ->latest()
                    ->take(5)
                    ->get(),
                'recentPayments' => Payment::with('student')
                    ->whereIn('student_id', $childIds)
                    ->latest()
                    ->take(5)
                    ->get(),
                'recentHomeworks' => Homework::with(['classroom', 'teacher'])
                    ->whereIn('classroom_id', $classroomIds)
                    ->orderBy('due_date')
                    ->take(5)
                    ->get(),
                'pendingActions' => [],
            ];
        }

        if ($user->hasRole(UserRole::Teacher)) {
            $classrooms = Classroom::withCount('students')
                ->where(function ($query) use ($user): void {
                    $query
                        ->where('main_teacher_id', $user->id)
                        ->orWhere('language_teacher_id', $user->id);
                })
                ->latest()
                ->get();

            $classroomIds = $classrooms->pluck('id');

            $homeworksToGrade = Homework::query()
                ->where('teacher_id', $user->id)
                ->whereHas('submissions', fn ($q) => $q->where('status', 'submitted'))
                ->count();

            return [
                'headline' => __('dashboard.headline_teacher'),
                'subheadline' => __('dashboard.subheadline_teacher'),
                'cards' => [
                    ['label' => __('dashboard.card_my_classes'), 'value' => $classrooms->count()],
                    ['label' => __('dashboard.card_my_courses'), 'value' => Course::where('teacher_id', $user->id)->count()],
                    ['label' => __('dashboard.card_my_homeworks'), 'value' => Homework::where('teacher_id', $user->id)->count()],
                    ['label' => __('dashboard.card_to_grade'), 'value' => $homeworksToGrade],
                ],
                'recentStudents' => Student::with(['classroom', 'parent'])
                    ->whereIn('classroom_id', $classroomIds)
                    ->latest()
                    ->take(5)
                    ->get(),
                'recentCourses' => Course::with(['classroom', 'teacher'])
                    ->where('teacher_id', $user->id)
                    ->latest()
                    ->take(5)
                    ->get(),
                'recentHomeworks' => Homework::with(['classroom', 'teacher'])
                    ->where('teacher_id', $user->id)
                    ->orderBy('due_date')
                    ->take(5)
                    ->get(),
                'recentPayments' => collect(),
                'pendingActions' => $homeworksToGrade > 0 ? [
                    [
                        'label' => __('dashboard.homeworks_to_grade'),
                        'count' => $homeworksToGrade,
                        'url' => route('homeworks.index'),
                    ],
                ] : [],
            ];
        }

        if ($user->hasRole(UserRole::Scolarite)) {
            $pendingPayments = Payment::where('status', PaymentStatus::Pending->value)->count();

            return [
                'headline' => __('dashboard.headline_scolarite'),
                'subheadline' => __('dashboard.subheadline_scolarite'),
                'cards' => [
                    ['label' => __('dashboard.card_active_students'), 'value' => Student::where('is_active', true)->count()],
                    ['label' => __('dashboard.card_pending_payments'), 'value' => $pendingPayments],
                    ['label' => __('dashboard.card_sent_messages'), 'value' => Announcement::where('author_id', $user->id)->count()],
                    ['label' => __('dashboard.card_classes'), 'value' => Classroom::count()],
                ],
                'recentStudents' => Student::with(['classroom', 'parent'])->latest()->take(5)->get(),
                'recentCourses' => Course::with(['classroom', 'teacher'])->latest()->take(5)->get(),
                'recentHomeworks' => Homework::with(['classroom', 'teacher'])->latest()->take(5)->get(),
                'recentPayments' => Payment::with('student')->latest()->take(5)->get(),
                'pendingActions' => array_filter([
                    $pendingPayments > 0 ? [
                        'label' => __('dashboard.pending_payments_validate'),
                        'count' => $pendingPayments,
                        'url' => route('payments.index'),
                    ] : null,
                    [
                        'label' => __('dashboard.new_payment'),
                        'count' => null,
                        'url' => route('payments.create'),
                    ],
                ]),
            ];
        }

        $pendingMessages = Announcement::where('status', AnnouncementStatus::PendingApproval->value)->count();
        $pendingPayments = Payment::where('status', PaymentStatus::Pending->value)->count();

        $pendingActions = [];

        if ($user->hasRole(UserRole::Founder)) {
            if ($pendingMessages > 0) {
                $pendingActions[] = [
                    'label' => __('dashboard.pending_messages'),
                    'count' => $pendingMessages,
                    'url' => route('announcements.index', ['filter' => 'pending']),
                ];
            }

            if ($pendingPayments > 0) {
                $pendingActions[] = [
                    'label' => __('dashboard.pending_payments'),
                    'count' => $pendingPayments,
                    'url' => route('payments.index'),
                ];
            }
        }

        return [
            'headline' => $user->hasRole(UserRole::Admin) ? __('dashboard.headline_admin') : __('dashboard.headline_founder'),
            'subheadline' => __('dashboard.subheadline_founder'),
            'cards' => [
                ['label' => __('dashboard.card_students'), 'value' => Student::count()],
                ['label' => __('dashboard.card_teachers'), 'value' => User::where('role', UserRole::Teacher->value)->count()],
                ['label' => __('dashboard.card_classes'), 'value' => Classroom::count()],
                ['label' => __('dashboard.card_homeworks'), 'value' => Homework::count()],
            ],
            'recentStudents' => Student::with(['classroom', 'parent'])->latest()->take(5)->get(),
            'recentCourses' => Course::with(['classroom', 'teacher'])->latest()->take(5)->get(),
            'recentHomeworks' => Homework::with(['classroom', 'teacher'])->latest()->take(5)->get(),
            'recentPayments' => Payment::with('student')->latest()->take(5)->get(),
            'pendingActions' => $pendingActions,
        ];
    }
}
