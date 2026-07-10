<?php

namespace App\Actions\Event;

use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListEventsForAdmin
{
    public function handle(?string $search, ?int $cityId, string $status): LengthAwarePaginator
    {
        $query = match ($status) {
            'inactive' => Event::query()->onlyInactive(),
            'all' => Event::query()->withInactive(),
            default => Event::query(),
        };

        return $query
            ->when($search, fn ($query) => $query->where('eve_title', 'like', "%{$search}%"))
            ->when($cityId, fn ($query) => $query->where('eve_city_id', $cityId))
            ->with('city')
            ->withCount('sessions')
            ->orderBy('eve_title')
            ->paginate(15);
    }
}