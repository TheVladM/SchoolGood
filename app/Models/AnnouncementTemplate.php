<?php

namespace App\Models;

use App\Enums\AnnouncementAudience;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnouncementTemplate extends Model
{
    protected $fillable = [
        'name',
        'title',
        'content',
        'audience',
        'created_by_id',
    ];

    protected function casts(): array
    {
        return [
            'audience' => AnnouncementAudience::class,
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
