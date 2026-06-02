<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_parent_only_sees_their_own_children(): void
    {
        $parent = User::factory()->create(['role' => UserRole::Parent]);
        $otherParent = User::factory()->create(['role' => UserRole::Parent]);
        $classroom = Classroom::create([
            'name' => '6e A',
            'level' => 'CM1',
            'section' => 'bilingue',
            'room' => 'A3',
        ]);

        Student::create([
            'first_name' => 'Kevin',
            'last_name' => 'Ndzi',
            'birth_date' => '2014-03-11',
            'classroom_id' => $classroom->id,
            'parent_id' => $parent->id,
        ]);

        Student::create([
            'first_name' => 'Alice',
            'last_name' => 'Nji',
            'birth_date' => '2014-08-02',
            'classroom_id' => $classroom->id,
            'parent_id' => $otherParent->id,
        ]);

        $this->actingAs($parent)
            ->get(route('students.index'))
            ->assertOk()
            ->assertSee('Kevin Ndzi')
            ->assertDontSee('Alice Nji');
    }

    public function test_scolarite_can_create_a_student_with_new_parent(): void
    {
        $scolarite = User::factory()->create(['role' => UserRole::Scolarite]);
        $schoolYear = SchoolYear::create([
            'name' => '2025-2026',
            'starts_on' => '2025-09-01',
            'ends_on' => '2026-07-05',
            'status' => 'current',
        ]);
        $classroom = Classroom::create([
            'name' => 'CM2 B',
            'level' => 'CM2',
            'section' => 'francophone',
            'room' => 'B7',
        ]);

        $this->actingAs($scolarite)
            ->post(route('students.store'), [
                'first_name' => 'Junior',
                'last_name' => 'Mbah',
                'birth_date' => '2015-05-12',
                'classroom_id' => $classroom->id,
                'school_year_id' => $schoolYear->id,
                'create_new_parent' => '1',
                'parent_name' => 'Parent Mbah',
                'parent_email' => 'parent.mbah@example.com',
                'parent_password' => 'password123',
            ])
            ->assertRedirect(route('students.index'));

        $this->assertDatabaseHas('students', [
            'first_name' => 'Junior',
            'last_name' => 'Mbah',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'parent.mbah@example.com',
            'role' => UserRole::Parent->value,
        ]);
    }
}
