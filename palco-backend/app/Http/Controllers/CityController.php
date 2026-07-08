<?php

namespace App\Http\Controllers;

use App\Actions\City\ListCities;
use App\Http\Requests\City\IndexCityRequest;
use App\Http\Resources\CityResource;

class CityController extends Controller
{
    public function index(IndexCityRequest $request, ListCities $listCities)
    {
        $cities = $listCities->handle($request->validated('search'));

        return CityResource::collection($cities);
    }
}
