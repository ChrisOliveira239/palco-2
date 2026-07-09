<?php

namespace App\Actions\Event;

use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListEvents
{
    public function handle(array $cityIds): LengthAwarePaginator
    {
        return Event::query()
            ->inCities($cityIds)
            ->withMin(['sessions as next_session_at' => function ($query) {
                $query->where('ses_start_at', '>=', now());
            }], 'ses_start_at')
            ->whereHas('sessions', function ($query) {
                $query->where('ses_start_at', '>=', now());
            })
            ->with('city')
            ->orderBy('next_session_at')
            ->paginate(15);
    }
}
