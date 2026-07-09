<?php

namespace App\Actions\City;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ListInterestedCities
{
    public function handle(User $user): Collection
    {
        return $user->interestedCities()->get();
    }
}