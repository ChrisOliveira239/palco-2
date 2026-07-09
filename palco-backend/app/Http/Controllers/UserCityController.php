<?php

namespace App\Http\Controllers;

use App\Actions\City\ListInterestedCities;
use App\Actions\City\SyncInterestedCities;
use App\Http\Requests\City\UpdateInterestedCitiesRequest;
use App\Http\Resources\CityResource;
use Illuminate\Http\Request;

class UserCityController extends Controller
{
    public function index(Request $request, ListInterestedCities $listInterestedCities)
    {
        $cities = $listInterestedCities->handle($request->user());

        return CityResource::collection($cities);
    }

    public function update(UpdateInterestedCitiesRequest $request, SyncInterestedCities $syncInterestedCities)
    {
        $cities = $syncInterestedCities->handle($request->user(), $request->validated('city_ids'));

        return CityResource::collection($cities);
    }
}