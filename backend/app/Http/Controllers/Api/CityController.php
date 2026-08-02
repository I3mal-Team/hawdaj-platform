<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Places\RegionResource;
use App\Http\Resources\Places\CityResource;
use App\Models\Region;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends ApiModalController
{
    public function index()
    {
        $cities = CityResource::collection(
            City::all()
        );

        return $this->success($cities,200, __('dashboard.list_of_cities'));
    }

    public function show(City $city)
    {
    	$city = new CityResource($city);

        return $this->success($city);
    }
}
