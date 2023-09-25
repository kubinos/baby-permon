<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

class GameLog extends Model
{
    use HasTimestamps;

    protected $table = 'game_log';

    protected $fillable = [
        'chip',
        'salutation',
        'action',
    ];
}
