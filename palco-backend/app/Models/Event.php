<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'eve_title',
        'eve_synopsis',
        'eve_type',
        'eve_venue_name',
        'eve_city_id',
        'eve_ticket_url',
        'eve_poster_path',
        'eve_created_by_id',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'eve_city_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'eve_created_by_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(EventSession::class, 'ses_event_id');
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_favorites', 'fav_event_id', 'fav_user_id');
    }

    public function scopeInCities(Builder $query, array $cityIds): Builder
    {
        return $query->whereIn('eve_city_id', $cityIds);
    }
}
