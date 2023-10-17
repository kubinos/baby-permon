<?php

namespace App\Models;

use App\Enums\Emotion;
use App\Enums\GameType;
use App\Enums\Language;
use App\Enums\Level;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory;
    use HasTimestamps;
    use SoftDeletes;

    protected $table = 'game';

    protected $attributes = [
        'points' => 0,
    ];

    protected $fillable = [
        'chip',
        'type',
        'salutation',
        'level',
        'emotion',
        'language',
        'points',
        'created_at',
    ];

    protected $appends = [
        'expiration',
    ];

    protected $casts = [
        'chip' => 'string',
        'type' => GameType::class,
        'salutation' => 'string',
        'level' => Level::class,
        'emotion' => Emotion::class,
        'language' => Language::class,
        'points' => 'integer',
        'expiration' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function expiration(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at->addHours(config('game.limit.hours')),
        );
    }

    public function scopeActive(Builder $query): Builder
    {
        $subHours = now()->subHours(config('game.limit.hours'));

        return $query
            ->where('created_at', '>=', $subHours)
            ->whereNull('deleted_at')
        ;
    }

    public function hasEnded(): bool
    {
        if ($this->deleted_at !== null) {
            return true;
        }

        return now()->diffInHours($this->created_at) >= config('game.limit.hours');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('c');
    }
}
