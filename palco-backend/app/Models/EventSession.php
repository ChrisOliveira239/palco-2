<?php

namespace App\Models;

use App\Enums\PricingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'ses_event_id',
        'ses_start_at',
        'ses_end_at',
        'ses_pricing_type',
        'ses_price',
        'ses_capacity',
    ];

    protected function casts(): array
    {
        return [
            'ses_start_at' => 'datetime',
            'ses_end_at' => 'datetime',
            'ses_pricing_type' => PricingType::class,
            'ses_price' => 'decimal:2',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'ses_event_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'ing_session_id');
    }
}
