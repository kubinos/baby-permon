<?php

namespace App\Models;

use App\Enums\Emotion;
use App\Enums\Language;
use App\Enums\Level;
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

    protected $fillable = [
        'chip',
        'salutation',
        'level',
        'emotion',
        'language',
    ];

    protected $casts = [
        'chip' => 'string',
        'salutation' => 'string',
        'level' => Level::class,
        'emotion' => Emotion::class,
        'language' => Language::class,
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function hasEnded(): bool
    {
        if ($this->deleted_at !== null) {
            return true;
        }

        return now()->diffInHours($this->created_at) >= config('game.limit.hours');
    }
}
