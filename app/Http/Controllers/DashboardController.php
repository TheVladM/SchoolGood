<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\Course;
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
            'headline' => $stats['headline'],
            'subheadline' => $stats['subheadline'],
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

            $payments = Payment::with('student')
                ->whereIn('student_id', $childIds)
                ->latest()
                ->take(5)
                ->get();

            return [
                'headline' => 'Vue parent',
                'subheadline' => 'Suivez les inscriptions, les paiements et les classes de vos enfants.',
                'cards' => [
                    ['label' => 'Enfants', 'value' => $children->count()],
                    ['label' => 'Classes', 'value' => $classroomIds->count()],
                    ['label' => 'Paiements payes', 'value' => Payment::whereIn('student_id', $childIds)->where('status', PaymentStatus::Paid->value)->count()],
                    ['label' => 'Paiements en attente', 'value' => Payment::whereIn('student_id', $childIds)->where('status', PaymentStatus::Pending->value)->count()],
                ],
                'recentStudents' => $children,
                'recentCourses' => Course::with(['classroom', 'teacher'])
                    ->whereIn('classroom_id', $classroomIds)
                    ->latest()
                    ->take(5)
                    ->get(),
                'recentPayments' => $payments,
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

            return [
                'headline' => 'Vue enseignant',
                'subheadline' => 'Retrouvez vos classes attribuees, vos cours et les eleves associes.',
                'cards' => [
                    ['label' => 'Mes classes', 'value' => $classrooms->count()],
                    ['label' => 'Mes cours', 'value' => Course::where('teacher_id', $user->id)->count()],
                    ['label' => 'Eleves suivis', 'value' => Student::whereIn('classroom_id', $classroomIds)->count()],
                    ['label' => 'Parents relies', 'value' => Student::whereIn('classroom_id', $classroomIds)->distinct('parent_id')->count('parent_id')],
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
                'recentPayments' => collect(),
            ];
        }

        return [
            'headline' => 'Vue administration',
            'subheadline' => 'Pilotez l\'etablissement, les affectations et les encaissements depuis un seul tableau de bord.',
            'cards' => [
                ['label' => 'Eleves', 'value' => Student::count()],
                ['label' => 'Enseignants', 'value' => User::where('role', UserRole::Teacher->value)->count()],
                ['label' => 'Classes', 'value' => Classroom::count()],
                ['label' => 'Paiements payes', 'value' => Payment::where('status', PaymentStatus::Paid->value)->count()],
            ],
            'recentStudents' => Student::with(['classroom', 'parent'])->latest()->take(5)->get(),
            'recentCourses' => Course::with(['classroom', 'teacher'])->latest()->take(5)->get(),
            'recentPayments' => Payment::with('student')->latest()->take(5)->get(),
        ];
    }
}
