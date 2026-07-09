<?php

namespace App\Actions\Event;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ListFavoriteEvents
{
    public function handle(User $user): Collection
    {
        return $user->favoriteEvents()
            ->withMin(['sessions as next_session_at' => function ($query) {
                $query->where('ses_start_at', '>=', now());
            }], 'ses_start_at')
            ->with('city')
            ->get();
    }
}