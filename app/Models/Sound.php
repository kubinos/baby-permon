<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sound extends Model
{
    use HasFactory;
    use HasTimestamps;

    protected $table = 'sound';

    protected $fillable = [
        'name',
        'number'
    ];

    protected $casts = [
        'number' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
