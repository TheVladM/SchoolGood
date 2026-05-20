<?php

namespace Database\Seeders;

use App\Enums\AnnouncementAudience;
use App\Enums\AnnouncementStatus;
use App\Enums\ClassroomSection;
use App\Enums\CourseDay;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\SchoolYearStatus;
use App\Enums\StudentSchoolYearStatus;
use App\Enums\UserDepartment;
use App\Enums\UserRole;
use App\Models\Announcement;
use App\Models\Book;
use App\Models\BookLoan;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Homework;
use App\Models\Payment;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentSchoolYearRecord;
use App\Models\TimetableEntry;
use App\Models\TuitionFee;
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
                'department' => UserDepartment::Direction,
                'job_title' => 'Fondateur de l etablissement',
                'password' => Hash::make('password'),
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@schoolgood.test'],
            [
                'name' => 'Admin SchoolGood',
                'phone' => '+237600000002',
                'role' => UserRole::Admin,
                'department' => UserDepartment::Administration,
                'job_title' => 'Administrateur de la plateforme',
                'password' => Hash::make('password'),
            ]
        );

        $scolarite = User::updateOrCreate(
            ['email' => 'scolarite@schoolgood.test'],
            [
                'name' => 'Agent Scolarite',
                'phone' => '+237600000003',
                'role' => UserRole::Scolarite,
                'department' => UserDepartment::Scolarite,
                'job_title' => 'Responsable inscriptions et pension',
                'password' => Hash::make('password'),
            ]
        );

        $mainTeacher = User::updateOrCreate(
            ['email' => 'teacher1@schoolgood.test'],
            [
                'name' => 'Marie Essomba',
                'phone' => '+237600000004',
                'role' => UserRole::Teacher,
                'department' => UserDepartment::Teaching,
                'job_title' => 'Titulaire francophone',
                'password' => Hash::make('password'),
            ]
        );

        $languageTeacher = User::updateOrCreate(
            ['email' => 'teacher2@schoolgood.test'],
            [
                'name' => 'John Nfor',
                'phone' => '+237600000005',
                'role' => UserRole::Teacher,
                'department' => UserDepartment::Teaching,
                'job_title' => 'Enseignant de langue',
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

        $currentSchoolYear = SchoolYear::updateOrCreate(
            ['name' => '2025-2026'],
            [
                'starts_on' => '2025-09-01',
                'ends_on' => '2026-07-05',
                'diploma_awarded_on' => '2026-07-05',
                'promotion_opens_on' => '2026-05-10',
                'status' => SchoolYearStatus::Current,
            ]
        );

        $nextSchoolYear = SchoolYear::updateOrCreate(
            ['name' => '2026-2027'],
            [
                'starts_on' => '2026-09-01',
                'ends_on' => '2027-07-05',
                'diploma_awarded_on' => '2027-07-05',
                'promotion_opens_on' => '2027-05-10',
                'status' => SchoolYearStatus::Planned,
            ]
        );

        $currentSchoolYear->update([
            'next_school_year_id' => $nextSchoolYear->id,
        ]);

        $classroom = Classroom::updateOrCreate(
            ['name' => 'CM1 A'],
            [
                'level' => 'CM1',
                'section' => ClassroomSection::Francophone,
                'room' => 'B12',
                'location' => 'Batiment B',
                'main_teacher_id' => $mainTeacher->id,
                'language_teacher_id' => $languageTeacher->id,
            ]
        );

        $nextClassroom = Classroom::updateOrCreate(
            ['name' => 'CM2 A'],
            [
                'level' => 'CM2',
                'section' => ClassroomSection::Francophone,
                'room' => 'B14',
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
                'is_active' => true,
                'left_at' => null,
            ]
        );

        StudentSchoolYearRecord::updateOrCreate(
            [
                'student_id' => $student->id,
                'school_year_id' => $currentSchoolYear->id,
            ],
            [
                'classroom_id' => $classroom->id,
                'classroom_name_snapshot' => $classroom->name,
                'level_snapshot' => $classroom->level,
                'section_snapshot' => $classroom->section?->value,
                'status' => StudentSchoolYearStatus::Active,
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
                'reference' => 'OM-2026-0001',
                'account_reference' => '237600000006',
                'status' => PaymentStatus::Paid,
                'notes' => 'Inscription reglee par Orange Money.',
                'received_by_id' => $scolarite->id,
                'validated_by_id' => $founder->id,
                'validated_at' => now(),
            ]
        );

        Payment::updateOrCreate(
            ['student_id' => $student->id, 'type' => PaymentType::FirstInstallment->value],
            [
                'amount' => 50000,
                'method' => PaymentMethod::Bank,
                'reference' => 'BANK-2026-0002',
                'account_reference' => 'ACC-SCHOOLGOOD-01',
                'status' => PaymentStatus::Pending,
                'notes' => 'Premiere tranche en attente de confirmation bancaire.',
                'received_by_id' => $scolarite->id,
            ]
        );

        TimetableEntry::updateOrCreate(
            [
                'level' => 'CM1',
                'section' => ClassroomSection::Francophone->value,
                'day' => CourseDay::Monday->value,
                'start_time' => '08:00',
                'end_time' => '09:00',
            ],
            [
                'subject' => 'Mathematiques',
                'notes' => 'Bloc commun a toutes les classes du niveau CM1.',
            ]
        );

        TimetableEntry::updateOrCreate(
            [
                'level' => 'CM1',
                'section' => ClassroomSection::Francophone->value,
                'day' => CourseDay::Thursday->value,
                'start_time' => '10:00',
                'end_time' => '11:00',
            ],
            [
                'subject' => 'Anglais',
                'notes' => 'Cours partage pour toutes les classes CM1 francophones.',
            ]
        );

        Announcement::updateOrCreate(
            ['title' => 'Reunion de rentree'],
            [
                'content' => 'Une reunion d information parents-ecole est prevue vendredi a 15h.',
                'audience' => AnnouncementAudience::AllParents,
                'status' => AnnouncementStatus::Approved,
                'classroom_id' => null,
                'author_id' => $scolarite->id,
                'approved_by_id' => $founder->id,
                'approved_at' => now(),
            ]
        );

        $mathBook = Book::updateOrCreate(
            ['title' => 'Mon premier cahier de mathematiques', 'author' => 'Equipe pedagogique'],
            [
                'isbn' => 'BK-MATH-001',
                'category' => 'Mathematiques',
                'language' => 'Francais',
                'total_copies' => 4,
                'shelf_location' => 'Rayon A1',
                'loan_duration_days' => 7,
                'late_fee_per_day' => 250,
                'description' => 'Livre de soutien pour les classes de primaire.',
                'acquired_at' => '2026-01-12',
                'managed_by_id' => $scolarite->id,
            ]
        );

        $englishBook = Book::updateOrCreate(
            ['title' => 'Young English Readers', 'author' => 'Cambridge Team'],
            [
                'isbn' => 'BK-ENG-010',
                'category' => 'Anglais',
                'language' => 'English',
                'total_copies' => 3,
                'shelf_location' => 'Rayon B2',
                'loan_duration_days' => 5,
                'late_fee_per_day' => 300,
                'description' => 'Lectures guidees pour l anglais oral et ecrit.',
                'acquired_at' => '2026-02-18',
                'managed_by_id' => $scolarite->id,
            ]
        );

        BookLoan::updateOrCreate(
            [
                'book_id' => $mathBook->id,
                'student_id' => $student->id,
                'borrowed_at' => '2026-05-12',
            ],
            [
                'due_at' => '2026-05-19',
                'daily_penalty_rate' => 250,
                'notes' => 'Emprunt a domicile pour revision.',
                'issued_by_id' => $scolarite->id,
            ]
        );

        BookLoan::updateOrCreate(
            [
                'book_id' => $englishBook->id,
                'user_id' => $languageTeacher->id,
                'borrowed_at' => '2026-04-20',
            ],
            [
                'due_at' => '2026-04-25',
                'returned_at' => '2026-04-24',
                'daily_penalty_rate' => 300,
                'notes' => 'Preparation de sequence pedagogique.',
                'issued_by_id' => $scolarite->id,
                'returned_by_id' => $scolarite->id,
            ]
        );

        // Frais de scolarité par niveau et section
        TuitionFee::updateOrCreate(
            ['level' => 'CM1', 'section' => ClassroomSection::Francophone],
            [
                'registration_fee' => 35000,
                'first_installment' => 50000,
                'second_installment' => 50000,
                'third_installment' => 50000,
                'notes' => 'Tarifs pour la classe CM1 francophone',
                'managed_by_id' => $founder->id,
            ]
        );

        TuitionFee::updateOrCreate(
            ['level' => 'CM2', 'section' => ClassroomSection::Francophone],
            [
                'registration_fee' => 35000,
                'first_installment' => 55000,
                'second_installment' => 55000,
                'third_installment' => 55000,
                'notes' => 'Tarifs pour la classe CM2 francophone',
                'managed_by_id' => $founder->id,
            ]
        );

        TuitionFee::updateOrCreate(
            ['level' => 'CM1', 'section' => ClassroomSection::Anglophone],
            [
                'registration_fee' => 40000,
                'first_installment' => 55000,
                'second_installment' => 55000,
                'third_installment' => 55000,
                'notes' => 'Tarifs pour la classe CM1 anglophone',
                'managed_by_id' => $founder->id,
            ]
        );

        TuitionFee::updateOrCreate(
            ['level' => 'CM2', 'section' => ClassroomSection::Anglophone],
            [
                'registration_fee' => 40000,
                'first_installment' => 60000,
                'second_installment' => 60000,
                'third_installment' => 60000,
                'notes' => 'Tarifs pour la classe CM2 anglophone',
                'managed_by_id' => $founder->id,
            ]
        );

        // Devoirs pour les classes
        Homework::updateOrCreate(
            ['title' => 'Problemes de mathematiques'],
            [
                'description' => 'Resolver les 10 problemes pages 45-46 du cahier.',
                'subject' => 'Mathematiques',
                'teacher_id' => $mainTeacher->id,
                'classroom_id' => $classroom->id,
                'due_date' => now()->addDays(3),
                'status' => 'assigned',
            ]
        );

        Homework::updateOrCreate(
            ['title' => 'Reading comprehension exercise'],
            [
                'description' => 'Read chapter 5 of "Young English Readers" and answer the questions.',
                'subject' => 'English',
                'teacher_id' => $languageTeacher->id,
                'classroom_id' => $classroom->id,
                'due_date' => now()->addDays(2),
                'status' => 'assigned',
            ]
        );

        Homework::updateOrCreate(
            ['title' => 'Redaction sur les saisons'],
            [
                'description' => 'Ecrire une redaction de 10 lignes minimum sur la saison preferee.',
                'subject' => 'Francais',
                'teacher_id' => $mainTeacher->id,
                'classroom_id' => $classroom->id,
                'due_date' => now()->addDays(5),
                'status' => 'assigned',
            ]
        );
    }
}
