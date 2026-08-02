<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Places\RegionResource;
use App\Http\Resources\Places\CityResource;
use App\Models\Region;
use App\Models\City;
use Illuminate\Http\Request;

class RegionController extends ApiModalController
{
    public function index()
    {
        $regions = RegionResource::collection(
            Region::all()
        );

        return $this->success($regions,200, __('dashboard.list_of_regions'));
    }

    public function citiesByRegionId(Request $request,$region_id)
    {
    	$cities = CityResource::collection(
    		City::where('region_id',$region_id)->get()
    	);

        return $this->success($cities,200, __('dashboard.list_of_cities'));
    }

    public function show(Region $region)
    {
    	$region = new RegionResource($region);

        return $this->success($region);
    }
}
