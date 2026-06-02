<?php

namespace Tests\Feature;

use App\Enums\AnnouncementStatus;
use App\Enums\UserRole;
use App\Models\Announcement;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\TimetableEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_access_payment_management_module(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)
            ->get(route('payments.index'))
            ->assertForbidden();
    }

    public function test_scolarite_message_requires_founder_approval_before_parent_sees_it(): void
    {
        $founder = User::factory()->create(['role' => UserRole::Founder]);
        $scolarite = User::factory()->create(['role' => UserRole::Scolarite]);
        $parent = User::factory()->create(['role' => UserRole::Parent]);
        $classroom = Classroom::create([
            'name' => 'CM1 A',
            'level' => 'CM1',
            'section' => 'francophone',
            'room' => 'A1',
        ]);

        Student::create([
            'first_name' => 'Lina',
            'last_name' => 'Meka',
            'birth_date' => '2016-02-10',
            'classroom_id' => $classroom->id,
            'parent_id' => $parent->id,
        ]);

        $this->actingAs($scolarite)
            ->post(route('announcements.store'), [
                'title' => 'Sortie pedagogique',
                'content' => 'Les eleves iront au musee samedi.',
                'audience' => 'classroom',
                'classroom_id' => $classroom->id,
            ])
            ->assertRedirect(route('announcements.index'));

        $announcement = Announcement::where('title', 'Sortie pedagogique')->firstOrFail();

        $this->assertSame(AnnouncementStatus::PendingApproval, $announcement->status);

        $this->actingAs($parent)
            ->get(route('announcements.index'))
            ->assertOk()
            ->assertDontSee('Sortie pedagogique');

        $this->actingAs($founder)
            ->post(route('announcements.approve', $announcement))
            ->assertRedirect();

        $this->actingAs($parent)
            ->get(route('announcements.index'))
            ->assertOk()
            ->assertSee('Sortie pedagogique');
    }

    public function test_teacher_cannot_be_titular_of_two_classrooms(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $titularTeacher = User::factory()->create(['role' => UserRole::Teacher]);
        $languageTeacherOne = User::factory()->create(['role' => UserRole::Teacher]);
        $languageTeacherTwo = User::factory()->create(['role' => UserRole::Teacher]);

        Classroom::create([
            'name' => 'CM1 A',
            'level' => 'CM1',
            'section' => 'francophone',
            'room' => 'A2',
            'main_teacher_id' => $titularTeacher->id,
            'language_teacher_id' => $languageTeacherOne->id,
        ]);

        $this->actingAs($admin)
            ->post(route('classrooms.store'), [
                'name' => 'CM2 A',
                'level' => 'CM2',
                'section' => 'francophone',
                'cycle_type' => 'standard',
                'room' => 'A3',
                'main_teacher_id' => $titularTeacher->id,
                'language_teacher_id' => $languageTeacherTwo->id,
            ])
            ->assertSessionHasErrors('main_teacher_id');
    }

    public function test_parent_only_sees_timetable_entries_for_child_level_and_section(): void
    {
        $parent = User::factory()->create(['role' => UserRole::Parent]);
        $classroom = Classroom::create([
            'name' => 'CM1 B',
            'level' => 'CM1',
            'section' => 'francophone',
            'room' => 'B1',
        ]);

        Student::create([
            'first_name' => 'Nora',
            'last_name' => 'Tabe',
            'birth_date' => '2015-09-03',
            'classroom_id' => $classroom->id,
            'parent_id' => $parent->id,
        ]);

        TimetableEntry::create([
            'level' => 'CM1',
            'section' => 'francophone',
            'subject' => 'Lecture',
            'day' => 'Lundi',
            'start_time' => '08:00',
            'end_time' => '09:00',
        ]);

        TimetableEntry::create([
            'level' => 'CM2',
            'section' => 'anglophone',
            'subject' => 'French',
            'day' => 'Mardi',
            'start_time' => '10:00',
            'end_time' => '11:00',
        ]);

        $this->actingAs($parent)
            ->get(route('timetable-entries.index'))
            ->assertOk()
            ->assertSee('Lecture')
            ->assertDontSee('French');
    }
}
