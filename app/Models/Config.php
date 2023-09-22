<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public $timestamps = false;

    protected $table = 'config';

    protected $fillable = [
        'threshold_level_1',
        'threshold_level_2',
        'threshold_level_3',
    ];

    protected $casts = [
        'threshold_level_1' => 'integer',
        'threshold_level_2' => 'integer',
        'threshold_level_3' => 'integer',
    ];
}
