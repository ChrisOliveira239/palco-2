<?php

namespace App\Actions\City;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class SyncInterestedCities
{
    public function handle(User $user, array $cityIds): Collection
    {
        $user->interestedCities()->sync($cityIds);

        return $user->interestedCities()->get();
    }
}
