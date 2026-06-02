<?php

namespace Tests\Feature;

use App\Enums\HomeworkSubmissionStatus;
use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeworkSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_parent_can_submit_homework_for_child(): void
    {
        $parent = User::factory()->create(['role' => UserRole::Parent]);
        $teacher = User::factory()->create(['role' => UserRole::Teacher]);
        $classroom = Classroom::create([
            'name' => 'CM1 A',
            'level' => 'CM1',
            'section' => 'francophone',
            'room' => 'A1',
            'main_teacher_id' => $teacher->id,
        ]);

        $student = Student::create([
            'first_name' => 'Luc',
            'last_name' => 'Mba',
            'birth_date' => '2016-01-01',
            'classroom_id' => $classroom->id,
            'parent_id' => $parent->id,
        ]);

        $homework = Homework::factory()->create([
            'teacher_id' => $teacher->id,
            'classroom_id' => $classroom->id,
        ]);

        HomeworkSubmission::create([
            'homework_id' => $homework->id,
            'student_id' => $student->id,
            'status' => HomeworkSubmissionStatus::Pending,
        ]);

        $this->actingAs($parent)
            ->post(route('homeworks.submissions.store', $homework), [
                'student_id' => $student->id,
            ])
            ->assertRedirect(route('homeworks.show', $homework));

        $this->assertSame(
            HomeworkSubmissionStatus::Submitted,
            HomeworkSubmission::where('homework_id', $homework->id)->first()->status
        );
    }
}
