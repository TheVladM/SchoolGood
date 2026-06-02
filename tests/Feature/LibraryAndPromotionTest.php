<?php

namespace Tests\Feature;

use App\Enums\ClassroomSection;
use App\Enums\SchoolYearStatus;
use App\Enums\StudentSchoolYearStatus;
use App\Enums\UserRole;
use App\Models\Book;
use App\Models\BookLoan;
use App\Models\Classroom;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentSchoolYearRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LibraryAndPromotionTest extends TestCase
{
    use RefreshDatabase;

    public function test_preparing_promotions_creates_next_year_record_and_updates_student_classroom(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $parent = User::factory()->create(['role' => UserRole::Parent]);
        $currentYear = SchoolYear::create([
            'name' => '2025-2026',
            'starts_on' => '2025-09-01',
            'ends_on' => '2026-07-05',
            'promotion_opens_on' => '2026-05-01',
            'status' => SchoolYearStatus::Current,
        ]);
        $nextYear = SchoolYear::create([
            'name' => '2026-2027',
            'starts_on' => '2026-09-01',
            'ends_on' => '2027-07-05',
            'status' => SchoolYearStatus::Planned,
        ]);
        $currentYear->update(['next_school_year_id' => $nextYear->id]);

        $classroom = Classroom::create([
            'name' => 'CM1 A',
            'level' => 'CM1',
            'section' => ClassroomSection::Francophone->value,
            'room' => 'B1',
        ]);
        $nextClassroom = Classroom::create([
            'name' => 'CM2 A',
            'level' => 'CM2',
            'section' => ClassroomSection::Francophone->value,
            'room' => 'B2',
        ]);

        $student = Student::create([
            'first_name' => 'Maya',
            'last_name' => 'Ekani',
            'birth_date' => '2015-04-11',
            'classroom_id' => $classroom->id,
            'parent_id' => $parent->id,
        ]);

        StudentSchoolYearRecord::create([
            'student_id' => $student->id,
            'school_year_id' => $currentYear->id,
            'classroom_id' => $classroom->id,
            'classroom_name_snapshot' => $classroom->name,
            'level_snapshot' => $classroom->level,
            'section_snapshot' => $classroom->section?->value,
            'status' => StudentSchoolYearStatus::Active,
        ]);

        $this->actingAs($admin)
            ->post(route('school-years.prepare-promotions', $currentYear))
            ->assertRedirect(route('school-years.show', $currentYear));

        $this->assertDatabaseHas('student_school_year_records', [
            'student_id' => $student->id,
            'school_year_id' => $nextYear->id,
            'classroom_id' => $nextClassroom->id,
            'status' => StudentSchoolYearStatus::PreRegistered->value,
        ]);

        $this->assertDatabaseHas('student_school_year_records', [
            'student_id' => $student->id,
            'school_year_id' => $currentYear->id,
            'status' => StudentSchoolYearStatus::Promoted->value,
        ]);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'classroom_id' => $nextClassroom->id,
        ]);
    }

    public function test_archiving_student_keeps_previous_history_even_when_future_record_is_withdrawn(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $parent = User::factory()->create(['role' => UserRole::Parent]);
        $currentYear = SchoolYear::create([
            'name' => '2025-2026',
            'starts_on' => '2025-09-01',
            'ends_on' => '2026-07-05',
            'status' => SchoolYearStatus::Closed,
        ]);
        $nextYear = SchoolYear::create([
            'name' => '2026-2027',
            'starts_on' => '2026-09-01',
            'ends_on' => '2027-07-05',
            'status' => SchoolYearStatus::Planned,
        ]);

        $classroom = Classroom::create([
            'name' => 'CM1 B',
            'level' => 'CM1',
            'section' => ClassroomSection::Francophone->value,
            'room' => 'B3',
        ]);
        $futureClassroom = Classroom::create([
            'name' => 'CM2 B',
            'level' => 'CM2',
            'section' => ClassroomSection::Francophone->value,
            'room' => 'B4',
        ]);

        $student = Student::create([
            'first_name' => 'Noah',
            'last_name' => 'Mbe',
            'birth_date' => '2015-06-08',
            'classroom_id' => $futureClassroom->id,
            'parent_id' => $parent->id,
        ]);

        StudentSchoolYearRecord::create([
            'student_id' => $student->id,
            'school_year_id' => $currentYear->id,
            'classroom_id' => $classroom->id,
            'classroom_name_snapshot' => $classroom->name,
            'level_snapshot' => $classroom->level,
            'section_snapshot' => $classroom->section?->value,
            'status' => StudentSchoolYearStatus::Promoted,
        ]);

        StudentSchoolYearRecord::create([
            'student_id' => $student->id,
            'school_year_id' => $nextYear->id,
            'classroom_id' => $futureClassroom->id,
            'classroom_name_snapshot' => $futureClassroom->name,
            'level_snapshot' => $futureClassroom->level,
            'section_snapshot' => $futureClassroom->section?->value,
            'status' => StudentSchoolYearStatus::PreRegistered,
        ]);

        $this->actingAs($admin)
            ->delete(route('students.destroy', $student))
            ->assertRedirect(route('students.index'));

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'is_active' => false,
        ]);

        $this->assertDatabaseHas('student_school_year_records', [
            'student_id' => $student->id,
            'school_year_id' => $currentYear->id,
            'status' => StudentSchoolYearStatus::Promoted->value,
        ]);

        $this->assertDatabaseHas('student_school_year_records', [
            'student_id' => $student->id,
            'school_year_id' => $nextYear->id,
            'status' => StudentSchoolYearStatus::Withdrawn->value,
        ]);
    }

    public function test_scolarite_can_return_a_book_loan_but_not_create_one(): void
    {
        $scolarite = User::factory()->create(['role' => UserRole::Scolarite]);
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $parent = User::factory()->create(['role' => UserRole::Parent]);
        $classroom = Classroom::create([
            'name' => 'CM1 A',
            'level' => 'CM1',
            'section' => ClassroomSection::Francophone->value,
            'room' => 'A1',
        ]);
        $student = Student::create([
            'first_name' => 'Lina',
            'last_name' => 'Tchoumi',
            'birth_date' => '2016-01-05',
            'classroom_id' => $classroom->id,
            'parent_id' => $parent->id,
        ]);
        $book = Book::create([
            'title' => 'Lecture CM1',
            'author' => 'Equipe ecole',
            'total_copies' => 2,
            'loan_duration_days' => 7,
            'late_fee_per_day' => 200,
        ]);

        $this->actingAs($scolarite)
            ->post(route('book-loans.store'), [
                'book_id' => $book->id,
                'student_id' => $student->id,
                'borrowed_at' => '2026-05-16',
            ])
            ->assertForbidden();

        $this->actingAs($admin)
            ->post(route('book-loans.store'), [
                'book_id' => $book->id,
                'student_id' => $student->id,
                'borrowed_at' => '2026-05-16',
            ])
            ->assertRedirect(route('book-loans.index'));

        $loan = BookLoan::firstOrFail();

        $this->actingAs($scolarite)
            ->post(route('book-loans.return', $loan))
            ->assertRedirect();

        $this->assertDatabaseHas('book_loans', [
            'id' => $loan->id,
            'student_id' => $student->id,
        ]);

        $this->assertNotNull($loan->fresh()->returned_at);
    }

    public function test_teacher_only_sees_their_own_book_loans(): void
    {
        $teacher = User::factory()->create(['role' => UserRole::Teacher]);
        $otherTeacher = User::factory()->create(['role' => UserRole::Teacher]);
        $book = Book::create([
            'title' => 'Library Book',
            'author' => 'Author',
            'total_copies' => 3,
            'loan_duration_days' => 7,
            'late_fee_per_day' => 100,
        ]);

        BookLoan::create([
            'book_id' => $book->id,
            'user_id' => $teacher->id,
            'borrowed_at' => '2026-05-10',
            'due_at' => '2026-05-17',
            'daily_penalty_rate' => 100,
        ]);

        BookLoan::create([
            'book_id' => $book->id,
            'user_id' => $otherTeacher->id,
            'borrowed_at' => '2026-05-11',
            'due_at' => '2026-05-18',
            'daily_penalty_rate' => 100,
        ]);

        $this->actingAs($teacher)
            ->get(route('book-loans.index'))
            ->assertOk()
            ->assertSee('Library Book')
            ->assertSee($teacher->name)
            ->assertDontSee($otherTeacher->name);
    }
}
