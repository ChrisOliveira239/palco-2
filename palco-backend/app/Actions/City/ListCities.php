<?php

namespace App\Actions\City;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;

class ListCities
{
    public function handle(?string $search): Collection
    {
        return City::query()
            ->when($search, fn ($query) => $query->where('cid_name', 'like', "%{$search}%"))
            ->orderBy('cid_name')
            ->limit(20)
            ->get();
    }
}