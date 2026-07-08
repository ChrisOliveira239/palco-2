<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'cid_name',
        'cid_state',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'eve_city_id');
    }

    public function interestedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'city_user', 'int_city_id', 'int_user_id');
    }
}
