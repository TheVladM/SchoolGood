<?php

namespace App\Models;

use App\Enums\UserRole;
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
