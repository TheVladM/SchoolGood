<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserDepartment;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'department',
        'job_title',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'role' => UserRole::class,
            'department' => UserDepartment::class,
            'password' => 'hashed',
        ];
    }

    public function children(): HasMany
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    public function mainClassrooms(): HasMany
    {
        return $this->hasMany(Classroom::class, 'main_teacher_id');
    }

    public function languageClassrooms(): HasMany
    {
        return $this->hasMany(Classroom::class, 'language_teacher_id');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function receivedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'received_by_id');
    }

    public function validatedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'validated_by_id');
    }

    public function authoredAnnouncements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'author_id');
    }

    public function approvedAnnouncements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'approved_by_id');
    }

    public function hasRole(UserRole|string $role): bool
    {
        $value = $role instanceof UserRole ? $role->value : $role;

        return $this->role?->value === $value;
    }

    public function hasAnyRole(array $roles): bool
    {
        $values = array_map(
            static fn (UserRole|string $role) => $role instanceof UserRole ? $role->value : $role,
            $roles
        );

        return in_array($this->role?->value, $values, true);
    }
}
