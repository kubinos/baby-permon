<?php

namespace App\Models;

use App\Enums\Location;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

class GameLog extends Model
{
    use HasTimestamps;

    protected $table = 'game_log';

    protected $fillable = [
        'id',
        'game_id',
        'chip',
        'location',
        'type',
        'task_id',
        'action',
    ];

    protected $casts = [
        'location' => Location::class,
    ];
}
