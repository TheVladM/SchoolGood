<?php

namespace Database\Seeders;

use App\Enums\ClassroomSection;
use App\Enums\CourseDay;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $founder = User::updateOrCreate(
            ['email' => 'founder@schoolgood.test'],
            [
                'name' => 'Fondateur SchoolGood',
                'phone' => '+237600000001',
                'role' => UserRole::Founder,
                'password' => Hash::make('password'),
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@schoolgood.test'],
            [
                'name' => 'Admin SchoolGood',
                'phone' => '+237600000002',
                'role' => UserRole::Admin,
                'password' => Hash::make('password'),
            ]
        );

        $scolarite = User::updateOrCreate(
            ['email' => 'scolarite@schoolgood.test'],
            [
                'name' => 'Agent Scolarite',
                'phone' => '+237600000003',
                'role' => UserRole::Scolarite,
                'password' => Hash::make('password'),
            ]
        );

        $mainTeacher = User::updateOrCreate(
            ['email' => 'teacher1@schoolgood.test'],
            [
                'name' => 'Marie Essomba',
                'phone' => '+237600000004',
                'role' => UserRole::Teacher,
                'password' => Hash::make('password'),
            ]
        );

        $languageTeacher = User::updateOrCreate(
            ['email' => 'teacher2@schoolgood.test'],
            [
                'name' => 'John Nfor',
                'phone' => '+237600000005',
                'role' => UserRole::Teacher,
                'password' => Hash::make('password'),
            ]
        );

        $parent = User::updateOrCreate(
            ['email' => 'parent@schoolgood.test'],
            [
                'name' => 'Grace Ndzi',
                'phone' => '+237600000006',
                'role' => UserRole::Parent,
                'password' => Hash::make('password'),
            ]
        );

        $classroom = Classroom::updateOrCreate(
            ['name' => 'CM1 A'],
            [
                'level' => 'Primaire',
                'section' => ClassroomSection::Bilingue,
                'room' => 'B12',
                'location' => 'Batiment B',
                'main_teacher_id' => $mainTeacher->id,
                'language_teacher_id' => $languageTeacher->id,
            ]
        );

        $student = Student::updateOrCreate(
            ['first_name' => 'Kevin', 'last_name' => 'Ndzi'],
            [
                'birth_date' => '2016-04-18',
                'classroom_id' => $classroom->id,
                'parent_id' => $parent->id,
            ]
        );

        Course::updateOrCreate(
            ['title' => 'Maths fondamentales', 'classroom_id' => $classroom->id],
            [
                'content' => 'Revision des operations et resolution de problemes.',
                'teacher_id' => $mainTeacher->id,
                'day' => CourseDay::Monday,
            ]
        );

        Course::updateOrCreate(
            ['title' => 'English Expression', 'classroom_id' => $classroom->id],
            [
                'content' => 'Speaking drills and reading comprehension.',
                'teacher_id' => $languageTeacher->id,
                'day' => CourseDay::Thursday,
            ]
        );

        Payment::updateOrCreate(
            ['student_id' => $student->id, 'type' => PaymentType::Registration->value],
            [
                'amount' => 35000,
                'method' => PaymentMethod::OrangeMoney,
                'status' => PaymentStatus::Paid,
            ]
        );

        Payment::updateOrCreate(
            ['student_id' => $student->id, 'type' => PaymentType::FirstInstallment->value],
            [
                'amount' => 50000,
                'method' => PaymentMethod::Bank,
                'status' => PaymentStatus::Pending,
            ]
        );
    }
}
