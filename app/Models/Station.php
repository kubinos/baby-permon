<?php

namespace App\Models;

use App\Enums\Location;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Station extends Model
{
    use HasFactory;
    use HasTimestamps;

    protected $table = 'station';

    protected $fillable = [
        'name',
        'location'
    ];

    protected $casts = [
        'location' => Location::class,
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'station_id', 'id');
    }
}
