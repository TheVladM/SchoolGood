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

        // Autres enseignants
        $teacher3 = User::updateOrCreate(
            ['email' => 'teacher3@schoolgood.test'],
            [
                'name' => 'Martine Fotso',
                'phone' => '+237600000010',
                'role' => UserRole::Teacher,
                'department' => UserDepartment::Teaching,
                'job_title' => 'Enseignante sciences et histoire',
                'password' => Hash::make('password'),
            ]
        );

        $teacher4 = User::updateOrCreate(
            ['email' => 'teacher4@schoolgood.test'],
            [
                'name' => 'Robert Ekeng',
                'phone' => '+237600000011',
                'role' => UserRole::Teacher,
                'department' => UserDepartment::Teaching,
                'job_title' => 'Titulaire anglophone',
                'password' => Hash::make('password'),
            ]
        );

        $teacher5 = User::updateOrCreate(
            ['email' => 'teacher5@schoolgood.test'],
            [
                'name' => 'Amandine Sop',
                'phone' => '+237600000012',
                'role' => UserRole::Teacher,
                'department' => UserDepartment::Teaching,
                'job_title' => 'Enseignante de culture générale',
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

        // Autres parents
        $parent2 = User::updateOrCreate(
            ['email' => 'parent2@schoolgood.test'],
            [
                'name' => 'Jean Talla',
                'phone' => '+237600000007',
                'role' => UserRole::Parent,
                'password' => Hash::make('password'),
            ]
        );

        $parent3 = User::updateOrCreate(
            ['email' => 'parent3@schoolgood.test'],
            [
                'name' => 'Sylvie Kamgno',
                'phone' => '+237600000008',
                'role' => UserRole::Parent,
                'password' => Hash::make('password'),
            ]
        );

        $parent4 = User::updateOrCreate(
            ['email' => 'parent4@schoolgood.test'],
            [
                'name' => 'Paul Mube',
                'phone' => '+237600000009',
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

        $classroom2 = Classroom::updateOrCreate(
            ['name' => 'CM1 B'],
            [
                'level' => 'CM1',
                'section' => ClassroomSection::Francophone,
                'room' => 'B13',
                'location' => 'Batiment B',
                'main_teacher_id' => $teacher3->id,
                'language_teacher_id' => $languageTeacher->id,
            ]
        );

        $classroomAnglophone = Classroom::updateOrCreate(
            ['name' => 'CM1 Anglophone'],
            [
                'level' => 'CM1',
                'section' => ClassroomSection::Anglophone,
                'room' => 'C01',
                'location' => 'Batiment C',
                'main_teacher_id' => $teacher4->id,
                'language_teacher_id' => $teacher5->id,
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

        $nextClassroom2 = Classroom::updateOrCreate(
            ['name' => 'CM2 Anglophone'],
            [
                'level' => 'CM2',
                'section' => ClassroomSection::Anglophone,
                'room' => 'C02',
                'location' => 'Batiment C',
                'main_teacher_id' => $teacher4->id,
                'language_teacher_id' => $teacher5->id,
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

        $student2 = Student::updateOrCreate(
            ['first_name' => 'Yannick', 'last_name' => 'Talla'],
            [
                'birth_date' => '2016-08-22',
                'classroom_id' => $classroom->id,
                'parent_id' => $parent2->id,
                'is_active' => true,
                'left_at' => null,
            ]
        );

        $student3 = Student::updateOrCreate(
            ['first_name' => 'Arianne', 'last_name' => 'Kamgno'],
            [
                'birth_date' => '2016-12-10',
                'classroom_id' => $classroom2->id,
                'parent_id' => $parent3->id,
                'is_active' => true,
                'left_at' => null,
            ]
        );

        $student4 = Student::updateOrCreate(
            ['first_name' => 'Ethan', 'last_name' => 'Mube'],
            [
                'birth_date' => '2016-03-05',
                'classroom_id' => $classroomAnglophone->id,
                'parent_id' => $parent4->id,
                'is_active' => true,
                'left_at' => null,
            ]
        );

        $student5 = Student::updateOrCreate(
            ['first_name' => 'Chiara', 'last_name' => 'Ndzi'],
            [
                'birth_date' => '2017-02-14',
                'classroom_id' => $classroomAnglophone->id,
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

        StudentSchoolYearRecord::updateOrCreate(
            [
                'student_id' => $student2->id,
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

        StudentSchoolYearRecord::updateOrCreate(
            [
                'student_id' => $student3->id,
                'school_year_id' => $currentSchoolYear->id,
            ],
            [
                'classroom_id' => $classroom2->id,
                'classroom_name_snapshot' => $classroom2->name,
                'level_snapshot' => $classroom2->level,
                'section_snapshot' => $classroom2->section?->value,
                'status' => StudentSchoolYearStatus::Active,
            ]
        );

        StudentSchoolYearRecord::updateOrCreate(
            [
                'student_id' => $student4->id,
                'school_year_id' => $currentSchoolYear->id,
            ],
            [
                'classroom_id' => $classroomAnglophone->id,
                'classroom_name_snapshot' => $classroomAnglophone->name,
                'level_snapshot' => $classroomAnglophone->level,
                'section_snapshot' => $classroomAnglophone->section?->value,
                'status' => StudentSchoolYearStatus::Active,
            ]
        );

        StudentSchoolYearRecord::updateOrCreate(
            [
                'student_id' => $student5->id,
                'school_year_id' => $currentSchoolYear->id,
            ],
            [
                'classroom_id' => $classroomAnglophone->id,
                'classroom_name_snapshot' => $classroomAnglophone->name,
                'level_snapshot' => $classroomAnglophone->level,
                'section_snapshot' => $classroomAnglophone->section?->value,
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

        // Cours pour CM1 B
        Course::updateOrCreate(
            ['title' => 'Maths fondamentales', 'classroom_id' => $classroom2->id],
            [
                'content' => 'Revision des operations et resolution de problemes.',
                'teacher_id' => $teacher3->id,
                'day' => CourseDay::Monday,
            ]
        );

        Course::updateOrCreate(
            ['title' => 'Sciences et Histoire', 'classroom_id' => $classroom2->id],
            [
                'content' => 'Initiation aux sciences naturelles et histoire.',
                'teacher_id' => $teacher3->id,
                'day' => CourseDay::Wednesday,
            ]
        );

        // Cours pour CM1 Anglophone
        Course::updateOrCreate(
            ['title' => 'English Studies', 'classroom_id' => $classroomAnglophone->id],
            [
                'content' => 'Grammar and vocabulary building.',
                'teacher_id' => $teacher4->id,
                'day' => CourseDay::Tuesday,
            ]
        );

        Course::updateOrCreate(
            ['title' => 'Mathematics', 'classroom_id' => $classroomAnglophone->id],
            [
                'content' => 'Advanced mathematical concepts.',
                'teacher_id' => $teacher4->id,
                'day' => CourseDay::Friday,
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

        // Paiements pour student2
        Payment::updateOrCreate(
            ['student_id' => $student2->id, 'type' => PaymentType::Registration->value],
            [
                'amount' => 35000,
                'method' => PaymentMethod::MtnMomo,
                'reference' => 'MTN-2026-0003',
                'account_reference' => '237670000007',
                'status' => PaymentStatus::Paid,
                'notes' => 'Inscription reglee par MTN Mobile Money.',
                'received_by_id' => $scolarite->id,
                'validated_by_id' => $founder->id,
                'validated_at' => now(),
            ]
        );

        Payment::updateOrCreate(
            ['student_id' => $student2->id, 'type' => PaymentType::FirstInstallment->value],
            [
                'amount' => 50000,
                'method' => PaymentMethod::OrangeMoney,
                'reference' => 'OM-2026-0004',
                'account_reference' => '237670000007',
                'status' => PaymentStatus::Paid,
                'notes' => 'Premiere tranche reglee.',
                'received_by_id' => $scolarite->id,
                'validated_by_id' => $founder->id,
                'validated_at' => now(),
            ]
        );

        // Paiements pour student3
        Payment::updateOrCreate(
            ['student_id' => $student3->id, 'type' => PaymentType::Registration->value],
            [
                'amount' => 35000,
                'method' => PaymentMethod::Bank,
                'reference' => 'BANK-2026-0005',
                'account_reference' => 'ACC-SCHOOLGOOD-02',
                'status' => PaymentStatus::Paid,
                'notes' => 'Inscription reglee par virement bancaire.',
                'received_by_id' => $scolarite->id,
                'validated_by_id' => $founder->id,
                'validated_at' => now(),
            ]
        );

        // Paiements pour student4 (anglophone)
        Payment::updateOrCreate(
            ['student_id' => $student4->id, 'type' => PaymentType::Registration->value],
            [
                'amount' => 40000,
                'method' => PaymentMethod::OrangeMoney,
                'reference' => 'OM-2026-0006',
                'account_reference' => '237600000011',
                'status' => PaymentStatus::Paid,
                'notes' => 'Registration fees paid.',
                'received_by_id' => $scolarite->id,
                'validated_by_id' => $founder->id,
                'validated_at' => now(),
            ]
        );

        Payment::updateOrCreate(
            ['student_id' => $student4->id, 'type' => PaymentType::FirstInstallment->value],
            [
                'amount' => 55000,
                'method' => PaymentMethod::OrangeMoney,
                'reference' => 'OM-2026-0007',
                'account_reference' => '237600000011',
                'status' => PaymentStatus::Paid,
                'notes' => 'First installment paid.',
                'received_by_id' => $scolarite->id,
                'validated_by_id' => $founder->id,
                'validated_at' => now(),
            ]
        );

        // Paiements pour student5
        Payment::updateOrCreate(
            ['student_id' => $student5->id, 'type' => PaymentType::Registration->value],
            [
                'amount' => 40000,
                'method' => PaymentMethod::Bank,
                'reference' => 'BANK-2026-0008',
                'account_reference' => 'ACC-SCHOOLGOOD-03',
                'status' => PaymentStatus::Paid,
                'notes' => 'Registration fees paid via bank transfer.',
                'received_by_id' => $scolarite->id,
                'validated_by_id' => $founder->id,
                'validated_at' => now(),
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

        // Emploi du temps pour CM1 Anglophone
        TimetableEntry::updateOrCreate(
            [
                'level' => 'CM1',
                'section' => ClassroomSection::Anglophone->value,
                'day' => CourseDay::Tuesday->value,
                'start_time' => '08:30',
                'end_time' => '09:30',
            ],
            [
                'subject' => 'English Studies',
                'notes' => 'English classes for anglophone section.',
            ]
        );

        TimetableEntry::updateOrCreate(
            [
                'level' => 'CM1',
                'section' => ClassroomSection::Anglophone->value,
                'day' => CourseDay::Friday->value,
                'start_time' => '09:00',
                'end_time' => '10:00',
            ],
            [
                'subject' => 'Mathematics',
                'notes' => 'Math classes for anglophone section.',
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

        Announcement::updateOrCreate(
            ['title' => 'Inscription ouverte pour activites parascolaires'],
            [
                'content' => 'Les inscriptions aux activites parascolaires (football, musique, informatique) sont ouvertes jusqu au 30 juin.',
                'audience' => AnnouncementAudience::AllParents,
                'status' => AnnouncementStatus::Approved,
                'classroom_id' => null,
                'author_id' => $scolarite->id,
                'approved_by_id' => $founder->id,
                'approved_at' => now(),
            ]
        );

        Announcement::updateOrCreate(
            ['title' => 'Fermeture de l etablissement'],
            [
                'content' => 'L etablissement sera ferme du 25 au 31 decembre pour les vacances de Noel.',
                'audience' => AnnouncementAudience::AllParents,
                'status' => AnnouncementStatus::Approved,
                'classroom_id' => null,
                'author_id' => $admin->id,
                'approved_by_id' => $founder->id,
                'approved_at' => now(),
            ]
        );

        Announcement::updateOrCreate(
            ['title' => 'Remise des bulletins CM1 A'],
            [
                'content' => 'Les bulletins du premier trimestre seront remis le samedi 15 juin a partir de 10h.',
                'audience' => AnnouncementAudience::Classroom,
                'status' => AnnouncementStatus::Approved,
                'classroom_id' => $classroom->id,
                'author_id' => $mainTeacher->id,
                'approved_by_id' => $founder->id,
                'approved_at' => now(),
            ]
        );

        Announcement::updateOrCreate(
            ['title' => 'Reunion parents-enseignants anglophone'],
            [
                'content' => 'Parent-teacher meeting for anglophone section on Saturday 20th at 2PM. Please confirm your attendance.',
                'audience' => AnnouncementAudience::Classroom,
                'status' => AnnouncementStatus::Approved,
                'classroom_id' => $classroomAnglophone->id,
                'author_id' => $teacher4->id,
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

        $frenchBook = Book::updateOrCreate(
            ['title' => 'La Grammaire au College', 'author' => 'Didier Dupre'],
            [
                'isbn' => 'BK-FR-005',
                'category' => 'Francais',
                'language' => 'Francais',
                'total_copies' => 5,
                'shelf_location' => 'Rayon A3',
                'loan_duration_days' => 10,
                'late_fee_per_day' => 200,
                'description' => 'Guide complet de grammaire francaise.',
                'acquired_at' => '2026-01-05',
                'managed_by_id' => $scolarite->id,
            ]
        );

        $scienceBook = Book::updateOrCreate(
            ['title' => 'Sciences de la Nature', 'author' => 'Jean Leblanc'],
            [
                'isbn' => 'BK-SCI-002',
                'category' => 'Sciences',
                'language' => 'Francais',
                'total_copies' => 3,
                'shelf_location' => 'Rayon C1',
                'loan_duration_days' => 7,
                'late_fee_per_day' => 250,
                'description' => 'Decouverte des sciences naturelles.',
                'acquired_at' => '2026-02-01',
                'managed_by_id' => $scolarite->id,
            ]
        );

        $historyBook = Book::updateOrCreate(
            ['title' => 'Histoire du Cameroun', 'author' => 'Cedric Ngui'],
            [
                'isbn' => 'BK-HIST-001',
                'category' => 'Histoire',
                'language' => 'Francais',
                'total_copies' => 2,
                'shelf_location' => 'Rayon D1',
                'loan_duration_days' => 14,
                'late_fee_per_day' => 200,
                'description' => 'Manuel d histoire et geographie du Cameroun.',
                'acquired_at' => '2025-12-10',
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

        // Autres emprunts
        BookLoan::updateOrCreate(
            [
                'book_id' => $frenchBook->id,
                'student_id' => $student2->id,
                'borrowed_at' => '2026-05-20',
            ],
            [
                'due_at' => '2026-05-30',
                'daily_penalty_rate' => 200,
                'notes' => 'Emprunt pour travail scolaire.',
                'issued_by_id' => $scolarite->id,
            ]
        );

        BookLoan::updateOrCreate(
            [
                'book_id' => $scienceBook->id,
                'student_id' => $student3->id,
                'borrowed_at' => '2026-05-15',
            ],
            [
                'due_at' => '2026-05-22',
                'daily_penalty_rate' => 250,
                'notes' => 'Preparation pour examen de sciences.',
                'issued_by_id' => $scolarite->id,
            ]
        );

        BookLoan::updateOrCreate(
            [
                'book_id' => $historyBook->id,
                'user_id' => $teacher3->id,
                'borrowed_at' => '2026-05-10',
            ],
            [
                'due_at' => '2026-05-24',
                'returned_at' => '2026-05-23',
                'daily_penalty_rate' => 200,
                'notes' => 'Preparation de cours d histoire.',
                'issued_by_id' => $scolarite->id,
                'returned_by_id' => $scolarite->id,
            ]
        );

        BookLoan::updateOrCreate(
            [
                'book_id' => $englishBook->id,
                'student_id' => $student4->id,
                'borrowed_at' => '2026-05-18',
            ],
            [
                'due_at' => '2026-05-23',
                'daily_penalty_rate' => 300,
                'notes' => 'Reading comprehension practice.',
                'issued_by_id' => $scolarite->id,
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

        // Devoirs pour CM1 B
        Homework::updateOrCreate(
            ['title' => 'Operations arithmetiques complexes', 'classroom_id' => $classroom2->id],
            [
                'description' => 'Effectuer les operations pages 52-53: addition, soustraction et multiplication.',
                'subject' => 'Mathematiques',
                'teacher_id' => $teacher3->id,
                'due_date' => now()->addDays(4),
                'status' => 'assigned',
            ]
        );

        Homework::updateOrCreate(
            ['title' => 'Recherche sur les animaux', 'classroom_id' => $classroom2->id],
            [
                'description' => 'Faire une recherche sur 3 animaux differents: caracteristiques, habitat, alimentation.',
                'subject' => 'Sciences',
                'teacher_id' => $teacher3->id,
                'due_date' => now()->addDays(6),
                'status' => 'assigned',
            ]
        );

        // Devoirs pour anglophone
        Homework::updateOrCreate(
            ['title' => 'Grammar exercises', 'classroom_id' => $classroomAnglophone->id],
            [
                'description' => 'Complete exercises 1-20 on present simple and present continuous.',
                'subject' => 'English',
                'teacher_id' => $teacher4->id,
                'due_date' => now()->addDays(3),
                'status' => 'assigned',
            ]
        );

        Homework::updateOrCreate(
            ['title' => 'Algebra problem set', 'classroom_id' => $classroomAnglophone->id],
            [
                'description' => 'Solve problems 1-15 from the algebra chapter in your textbook.',
                'subject' => 'Mathematics',
                'teacher_id' => $teacher4->id,
                'due_date' => now()->addDays(4),
                'status' => 'assigned',
            ]
        );

        Homework::updateOrCreate(
            ['title' => 'Vocabulary building exercise', 'classroom_id' => $classroomAnglophone->id],
            [
                'description' => 'Learn 20 new English words and create sentences with each one.',
                'subject' => 'English',
                'teacher_id' => $teacher5->id,
                'due_date' => now()->addDays(5),
                'status' => 'assigned',
            ]
        );
    }
}
