<?php

namespace App\Models;

use App\Enums\VideoAppointmentStatus;
use Database\Factories\VideoAppointmentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoAppointment extends Model
{
    /** @use HasFactory<VideoAppointmentFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'preferred_date',
        'preferred_time',
        'note',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'status' => VideoAppointmentStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', VideoAppointmentStatus::Pending);
    }
}
