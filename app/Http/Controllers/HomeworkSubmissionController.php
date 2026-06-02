<?php

namespace App\Http\Controllers;

use App\Enums\HomeworkSubmissionStatus;
use App\Enums\UserRole;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HomeworkSubmissionController extends Controller
{
    public function store(Request $request, Homework $homework): RedirectResponse
    {
        $this->authorize('view', $homework);
        abort_unless($request->user()->hasRole(UserRole::Parent), 403);

        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ]);

        $student = Student::findOrFail($data['student_id']);
        abort_unless($student->parent_id === $request->user()->id, 403);
        abort_unless($student->classroom_id === $homework->classroom_id, 403);

        $filePath = $request->hasFile('file')
            ? $request->file('file')->store('homework-submissions', 'public')
            : null;

        HomeworkSubmission::updateOrCreate(
            [
                'homework_id' => $homework->id,
                'student_id' => $student->id,
            ],
            [
                'status' => HomeworkSubmissionStatus::Submitted,
                'file_path' => $filePath,
                'submitted_at' => now(),
                'graded_at' => null,
                'grade' => null,
                'teacher_feedback' => null,
            ]
        );

        return redirect()
            ->route('homeworks.show', $homework)
            ->with('success', 'Devoir rendu avec succès.');
    }

    public function grade(Request $request, Homework $homework, HomeworkSubmission $submission): RedirectResponse
    {
        $this->authorize('update', $homework);

        if ($submission->homework_id !== $homework->id) {
            throw ValidationException::withMessages([
                'submission' => 'Ce rendu ne correspond pas à ce devoir.',
            ]);
        }

        $data = $request->validate([
            'grade' => ['required', 'numeric', 'min:0', 'max:20'],
            'teacher_feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $submission->update([
            'status' => HomeworkSubmissionStatus::Graded,
            'grade' => $data['grade'],
            'teacher_feedback' => $data['teacher_feedback'] ?? null,
            'graded_at' => now(),
        ]);

        return redirect()
            ->route('homeworks.show', $homework)
            ->with('success', 'Note enregistrée.');
    }
}
