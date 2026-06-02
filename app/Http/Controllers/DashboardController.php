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
                'headline' => 'Vue parent',
                'subheadline' => 'Suivez les inscriptions, les paiements, les classes et les devoirs de vos enfants.',
                'cards' => [
                    ['label' => 'Enfants', 'value' => $children->count()],
                    ['label' => 'Classes', 'value' => $classroomIds->count()],
                    ['label' => 'Paiements payés', 'value' => Payment::whereIn('student_id', $childIds)->where('status', PaymentStatus::Paid->value)->count()],
                    ['label' => 'Devoirs actifs', 'value' => Homework::whereIn('classroom_id', $classroomIds)->where('due_date', '>', now())->count()],
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
                'headline' => 'Vue enseignant',
                'subheadline' => 'Vos classes, cours du jour et devoirs à corriger.',
                'cards' => [
                    ['label' => 'Mes classes', 'value' => $classrooms->count()],
                    ['label' => 'Mes cours', 'value' => Course::where('teacher_id', $user->id)->count()],
                    ['label' => 'Mes devoirs', 'value' => Homework::where('teacher_id', $user->id)->count()],
                    ['label' => 'Rendus à noter', 'value' => $homeworksToGrade],
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
                        'label' => 'Devoirs à corriger',
                        'count' => $homeworksToGrade,
                        'url' => route('homeworks.index'),
                    ],
                ] : [],
            ];
        }

        if ($user->hasRole(UserRole::Scolarite)) {
            $pendingPayments = Payment::where('status', PaymentStatus::Pending->value)->count();

            return [
                'headline' => 'Vue scolarité',
                'subheadline' => 'Encaissements, messages et suivi des familles.',
                'cards' => [
                    ['label' => 'Élèves actifs', 'value' => Student::where('is_active', true)->count()],
                    ['label' => 'Paiements en attente', 'value' => $pendingPayments],
                    ['label' => 'Messages envoyés', 'value' => Announcement::where('author_id', $user->id)->count()],
                    ['label' => 'Classes', 'value' => Classroom::count()],
                ],
                'recentStudents' => Student::with(['classroom', 'parent'])->latest()->take(5)->get(),
                'recentCourses' => Course::with(['classroom', 'teacher'])->latest()->take(5)->get(),
                'recentHomeworks' => Homework::with(['classroom', 'teacher'])->latest()->take(5)->get(),
                'recentPayments' => Payment::with('student')->latest()->take(5)->get(),
                'pendingActions' => array_filter([
                    $pendingPayments > 0 ? [
                        'label' => 'Paiements à valider',
                        'count' => $pendingPayments,
                        'url' => route('payments.index'),
                    ] : null,
                    [
                        'label' => 'Nouveau paiement',
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
                    'label' => 'Messages à approuver',
                    'count' => $pendingMessages,
                    'url' => route('announcements.index', ['filter' => 'pending']),
                ];
            }

            if ($pendingPayments > 0) {
                $pendingActions[] = [
                    'label' => 'Paiements en attente',
                    'count' => $pendingPayments,
                    'url' => route('payments.index'),
                ];
            }
        }

        return [
            'headline' => $user->hasRole(UserRole::Admin) ? 'Vue administration' : 'Vue fondateur',
            'subheadline' => 'Pilotez l\'établissement, les affectations et les validations.',
            'cards' => [
                ['label' => 'Élèves', 'value' => Student::count()],
                ['label' => 'Enseignants', 'value' => User::where('role', UserRole::Teacher->value)->count()],
                ['label' => 'Classes', 'value' => Classroom::count()],
                ['label' => 'Devoirs', 'value' => Homework::count()],
            ],
            'recentStudents' => Student::with(['classroom', 'parent'])->latest()->take(5)->get(),
            'recentCourses' => Course::with(['classroom', 'teacher'])->latest()->take(5)->get(),
            'recentHomeworks' => Homework::with(['classroom', 'teacher'])->latest()->take(5)->get(),
            'recentPayments' => Payment::with('student')->latest()->take(5)->get(),
            'pendingActions' => $pendingActions,
        ];
    }
}
