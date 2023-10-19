<?php

namespace App\Models;

use App\Enums\Difficulty;
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
        'response_correct',
        'points_correct',
        'response_partial',
        'points_partial',
        'points_incorrect',
    ];

    protected $casts = [
        'difficulty' => Difficulty::class,
        'station_id' => 'integer',
        'sound_cs_id' => 'integer',
        'sound_en_id' => 'integer',
        'sound_de_id' => 'integer',
        'response_correct' => 'array',
        'points_correct' => 'integer',
        'response_partial' => 'array',
        'points_partial' => 'integer',
        'points_incorrect' => 'integer',
    ];

    protected $hidden = [
        'response_correct',
        'response_partial',
        'created_at',
        'updated_at',
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

    public function getAnswerClean($prop): array
    {
        $response = $this->{$prop};

        return array_map(function (string $item) {
            return str_split($item)[0];
        }, $response);
    }

    /**
     * @param array<string> $response
     * @return array<string>
     */
    public static function parseResponse(array $response): array
    {
        $new = ['0', '0', '0'];

        for ($i = 0; $i < 3; $i++) {

            foreach ($response as $item) {
                $chars = str_split($item);

                if (($i + 1) === intval($chars[0])) {
                    if (count($chars) === 2) {
                        $new[$i] = $chars[1];
                    }
                }
            }
        }

        return $new;
    }
}
