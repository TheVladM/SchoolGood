<?php

namespace Tests\Feature;

use App\Enums\AnnouncementAudience;
use App\Enums\AnnouncementStatus;
use App\Enums\ClassroomSection;
use App\Enums\UserRole;
use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementReadReceiptTest extends TestCase
{
    use RefreshDatabase;

    public function test_parent_opening_message_records_read_receipt(): void
    {
        $founder = User::factory()->create(['role' => UserRole::Founder]);
        $parent = User::factory()->create(['role' => UserRole::Parent]);
        $classroom = Classroom::create([
            'name' => 'CM1 A',
            'level' => 'CM1',
            'section' => ClassroomSection::Francophone->value,
            'room' => 'A1',
        ]);

        Student::create([
            'first_name' => 'Lina',
            'last_name' => 'Test',
            'birth_date' => '2016-01-01',
            'classroom_id' => $classroom->id,
            'parent_id' => $parent->id,
        ]);

        $announcement = Announcement::create([
            'title' => 'Réunion parents',
            'content' => 'Samedi 10h.',
            'audience' => AnnouncementAudience::AllParents,
            'status' => AnnouncementStatus::Approved,
            'author_id' => $founder->id,
            'approved_by_id' => $founder->id,
            'approved_at' => now(),
        ]);

        $this->actingAs($parent)
            ->get(route('announcements.show', $announcement))
            ->assertOk()
            ->assertSee('Accusé de lecture enregistré');

        $this->assertDatabaseHas('announcement_reads', [
            'announcement_id' => $announcement->id,
            'user_id' => $parent->id,
        ]);

        $this->actingAs($founder)
            ->get(route('announcements.show', $announcement))
            ->assertOk()
            ->assertSee('1 / 1 parent(s)')
            ->assertSee('Lu le');
    }
}
