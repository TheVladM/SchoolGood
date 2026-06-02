<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\Homework;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeworkControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $teacher;
    private User $admin;
    private User $founder;
    private Classroom $classroom;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->teacher = User::factory()->create(['role' => UserRole::Teacher]);
        $this->admin = User::factory()->create(['role' => UserRole::Admin]);
        $this->founder = User::factory()->create(['role' => UserRole::Founder]);
        $this->classroom = Classroom::factory()->create([
            'main_teacher_id' => $this->teacher->id,
        ]);
    }

    public function test_teacher_can_view_homeworks_index(): void
    {
        $this->actingAs($this->teacher);
        
        $response = $this->get(route('homeworks.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('homeworks.index');
    }

    public function test_teacher_can_access_create_homework_form(): void
    {
        $this->actingAs($this->teacher);
        
        $response = $this->get(route('homeworks.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('homeworks.create');
    }

    public function test_teacher_can_create_homework(): void
    {
        $this->actingAs($this->teacher);
        
        $data = [
            'title' => 'Test Homework',
            'description' => 'Test Description',
            'subject' => 'Maths',
            'teacher_id' => $this->teacher->id,
            'classroom_id' => $this->classroom->id,
            'due_date' => now()->addDays(3)->format('Y-m-d\TH:i'),
        ];
        
        $response = $this->post(route('homeworks.store'), $data);
        
        $response->assertRedirect(route('homeworks.index'));
        $this->assertDatabaseHas('homeworks', [
            'title' => 'Test Homework',
            'teacher_id' => $this->teacher->id,
        ]);
    }

    public function test_teacher_can_view_homework(): void
    {
        $homework = Homework::factory()->create(['teacher_id' => $this->teacher->id]);
        
        $this->actingAs($this->teacher);
        
        $response = $this->get(route('homeworks.show', $homework));
        
        $response->assertStatus(200);
        $response->assertViewIs('homeworks.show');
    }

    public function test_teacher_can_edit_own_homework(): void
    {
        $homework = Homework::factory()->create(['teacher_id' => $this->teacher->id]);
        
        $this->actingAs($this->teacher);
        
        $response = $this->get(route('homeworks.edit', $homework));
        
        $response->assertStatus(200);
        $response->assertViewIs('homeworks.edit');
    }

    public function test_teacher_cannot_edit_others_homework(): void
    {
        $otherTeacher = User::factory()->create(['role' => UserRole::Teacher]);
        $homework = Homework::factory()->create(['teacher_id' => $otherTeacher->id]);
        
        $this->actingAs($this->teacher);
        
        $response = $this->get(route('homeworks.edit', $homework));
        
        $response->assertStatus(403);
    }

    public function test_teacher_can_update_own_homework(): void
    {
        $homework = Homework::factory()->create(['teacher_id' => $this->teacher->id]);
        
        $this->actingAs($this->teacher);
        
        $data = [
            'title' => 'Updated Homework',
            'description' => 'Updated Description',
            'subject' => 'English',
            'teacher_id' => $this->teacher->id,
            'classroom_id' => $this->classroom->id,
            'due_date' => now()->addDays(5)->format('Y-m-d\TH:i'),
        ];
        
        $response = $this->patch(route('homeworks.update', $homework), $data);
        
        $response->assertRedirect(route('homeworks.show', $homework));
        $this->assertDatabaseHas('homeworks', [
            'id' => $homework->id,
            'title' => 'Updated Homework',
        ]);
    }

    public function test_teacher_can_delete_own_homework(): void
    {
        $homework = Homework::factory()->create(['teacher_id' => $this->teacher->id]);
        
        $this->actingAs($this->teacher);
        
        $response = $this->delete(route('homeworks.destroy', $homework));
        
        $response->assertRedirect(route('homeworks.index'));
        $this->assertDatabaseMissing('homeworks', ['id' => $homework->id]);
    }

    public function test_admin_can_delete_any_homework(): void
    {
        $homework = Homework::factory()->create(['teacher_id' => $this->teacher->id]);
        
        $this->actingAs($this->admin);
        
        $response = $this->delete(route('homeworks.destroy', $homework));
        
        $response->assertRedirect(route('homeworks.index'));
        $this->assertDatabaseMissing('homeworks', ['id' => $homework->id]);
    }

    public function test_unauthenticated_user_cannot_access_homeworks(): void
    {
        $response = $this->get(route('homeworks.index'));
        
        $response->assertRedirect(route('login'));
    }
}

