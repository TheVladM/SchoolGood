<?php

namespace Tests\Feature;

use App\Enums\ClassroomSection;
use App\Enums\CourseDay;
use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\TimetableEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTimetableSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_timetable_entry_creates_synced_courses_for_matching_classrooms(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $titular = User::factory()->create(['role' => UserRole::Teacher]);
        $language = User::factory()->create(['role' => UserRole::Teacher]);

        $classroomA = Classroom::create([
            'name' => 'CM1 A',
            'level' => 'CM1',
            'section' => ClassroomSection::Francophone->value,
            'room' => 'A1',
            'main_teacher_id' => $titular->id,
            'language_teacher_id' => $language->id,
        ]);

        $classroomB = Classroom::create([
            'name' => 'CM1 B',
            'level' => 'CM1',
            'section' => ClassroomSection::Francophone->value,
            'room' => 'A2',
            'main_teacher_id' => $titular->id,
            'language_teacher_id' => $language->id,
        ]);

        $this->actingAs($admin)
            ->post(route('timetable-entries.store'), [
                'level' => 'CM1',
                'section' => ClassroomSection::Francophone->value,
                'subject' => 'Mathématiques',
                'day' => CourseDay::Monday->value,
                'start_time' => '08:00',
                'end_time' => '09:00',
                'notes' => 'Chapitre 1',
                'sync_courses' => '1',
            ])
            ->assertRedirect(route('timetable-entries.index'));

        $entry = TimetableEntry::firstOrFail();

        $this->assertSame(2, Course::where('timetable_entry_id', $entry->id)->count());

        $courseA = Course::where('classroom_id', $classroomA->id)->first();
        $this->assertSame('Mathématiques', $courseA->title);
        $this->assertSame($titular->id, $courseA->teacher_id);

        $this->actingAs($admin)
            ->post(route('timetable-entries.store'), [
                'level' => 'CM1',
                'section' => ClassroomSection::Francophone->value,
                'subject' => 'Anglais',
                'day' => CourseDay::Tuesday->value,
                'start_time' => '10:00',
                'end_time' => '11:00',
                'sync_courses' => '1',
            ]);

        $englishCourse = Course::where('classroom_id', $classroomA->id)->where('title', 'Anglais')->first();
        $this->assertSame($language->id, $englishCourse->teacher_id);
    }

    public function test_setup_titular_courses_creates_standard_subjects(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $titular = User::factory()->create(['role' => UserRole::Teacher]);

        $classroom = Classroom::create([
            'name' => 'CE2 A',
            'level' => 'CE2',
            'section' => ClassroomSection::Francophone->value,
            'room' => 'B2',
            'main_teacher_id' => $titular->id,
        ]);

        $this->actingAs($admin)
            ->post(route('classrooms.setup-titular-courses', $classroom))
            ->assertRedirect();

        $this->assertTrue(
            Course::where('classroom_id', $classroom->id)->where('title', 'Mathématiques')->exists()
        );
        $this->assertTrue(
            Course::where('classroom_id', $classroom->id)->where('title', 'Anglais')->exists()
        );
    }
}
