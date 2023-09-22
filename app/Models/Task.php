<?php

namespace App\Models;

use App\Enums\Color;
use App\Enums\Difficulty;
use App\Enums\Number;
use App\Enums\Shape;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    use HasTimestamps;

    protected $table = 'task';

    protected $fillable = [
        'name',
        'difficulty',
        'station_id',
        'sound_cs_id',
        'sound_en_id',
        'sound_de_id',
        'response_number',
        'response_color',
        'response_shape',
        'points_correct',
        'points_partial',
        'points_incorrect',
    ];

    protected $casts = [
        'difficulty' => Difficulty::class,
        'station_id' => 'integer',
        'sound_cs_id' => 'integer',
        'sound_en_id' => 'integer',
        'sound_de_id' => 'integer',
        'response_number' => Number::class,
        'response_color' => Color::class,
        'response_shape' => Shape::class,
        'points_correct' => 'integer',
        'points_partial' => 'integer',
        'points_incorrect' => 'integer',
    ];

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'station_id', 'id');
    }

    public function soundCs(): BelongsTo
    {
        return $this->belongsTo(Sound::class, 'sound_cs_id', 'id');
    }

    public function soundEn(): BelongsTo
    {
        return $this->belongsTo(Sound::class, 'sound_en_id', 'id');
    }

    public function soundDe(): BelongsTo
    {
        return $this->belongsTo(Sound::class, 'sound_de_id', 'id');
    }
}
