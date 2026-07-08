<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ing_session_id',
        'ing_hash_code',
        'ing_holder_name',
        'ing_holder_document',
        'ing_holder_email',
        'ing_user_id',
        'ing_purchased_at',
    ];

    protected function casts(): array
    {
        return [
            'ing_purchased_at' => 'datetime',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(EventSession::class, 'ing_session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ing_user_id');
    }
}