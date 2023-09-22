<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sound extends Model
{
    use HasFactory;
    use HasTimestamps;

    protected $table = 'sound';

    protected $fillable = [
        'name',
        'number'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function tasksCs(): HasMany
    {
        return $this->hasMany(Task::class, 'sound_cs_id', 'id');
    }

    public function tasksEn(): HasMany
    {
        return $this->hasMany(Task::class, 'sound_en_id', 'id');
    }

    public function tasksDe(): HasMany
    {
        return $this->hasMany(Task::class, 'sound_de_id', 'id');
    }
}
