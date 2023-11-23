<?php

namespace App\Models;

use App\Enums\Emotion;
use App\Enums\GameType;
use App\Enums\Language;
use App\Enums\Level;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory;
    use HasTimestamps;
    use SoftDeletes;

    protected $table = 'game';

    protected $attributes = [
        'points' => 0,
        'labyrint_points' => 0,
        'streak' => 1,
    ];

    protected $fillable = [
        'chip',
        'type',
        'salutation',
        'level',
        'streak',
        'emotion',
        'labyring_time',
        'language',
        'points',
        'labyrint_points',
        'current_task_id',
        'ended_at',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'expiration',
        'type_trans',
    ];

    protected $casts = [
        'chip' => 'string',
        'type' => GameType::class,
        'salutation' => 'string',
        'level' => Level::class,
        'streak' => 'integer',
        'emotion' => Emotion::class,
        'language' => Language::class,
        'points' => 'integer',
        'labyrint_points' => 'integer',
        'ended_at' => 'datetime',
        'expiration' => 'datetime',
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    public function typeTrans(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type->toString(),
        );
    }

    public function expiration(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at->addHours(config('game.limit.hours')),
        );
    }

    public function hasEnded(): bool
    {
        if ($this->deleted_at !== null) {
            return true;
        }

        return now()->diffInHours($this->created_at) >= config('game.limit.hours');
    }

    public function currentTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'current_task_id', 'id');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('c');
    }
}
