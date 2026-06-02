<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Student;
use App\Models\StudentSchoolGrade;
use App\Models\User;

class StudentSchoolGradePolicy
{
    public function viewAny(User $user, Student $student): bool
    {
        return (new StudentPolicy)->view($user, $student);
    }

    public function create(User $user, Student $student): bool
    {
        if (in_array($user->role, [UserRole::Founder, UserRole::Admin, UserRole::Scolarite], true)) {
            return true;
        }

        if ($user->role === UserRole::Teacher && $student->classroom) {
            return $user->teachesInClassroom($student->classroom);
        }

        return false;
    }

    public function delete(User $user, StudentSchoolGrade $grade): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Admin, UserRole::Scolarite], true);
    }
}
