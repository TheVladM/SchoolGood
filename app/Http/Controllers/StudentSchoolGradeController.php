<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentSchoolGrade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StudentSchoolGradeController extends Controller
{
    public function store(Request $request, Student $student): RedirectResponse
    {
        $this->authorize('create', [StudentSchoolGrade::class, $student]);

        $data = $request->validate([
            'school_year_id' => ['required', 'exists:school_years,id'],
            'subject' => ['required', 'string', 'max:100'],
            'term' => ['required', 'string', 'max:50'],
            'grade' => ['required', 'numeric', 'min:0', 'max:20'],
            'comment' => ['nullable', 'string'],
        ]);

        StudentSchoolGrade::create([
            ...$data,
            'student_id' => $student->id,
            'recorded_by_id' => $request->user()->id,
        ]);

        return back()->with('success', 'Note enregistrée.');
    }

    public function destroy(Request $request, Student $student, StudentSchoolGrade $grade): RedirectResponse
    {
        abort_unless($grade->student_id === $student->id, 404);
        $this->authorize('delete', $grade);

        $grade->delete();

        return back()->with('success', 'Note supprimée.');
    }
}
