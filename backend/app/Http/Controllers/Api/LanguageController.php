<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Languages\LanguageResource;
use App\Http\Resources\Places\RegionResource;
use App\Http\Resources\Places\CityResource;
use App\Models\Language;
use App\Models\Region;
use App\Models\City;
use Illuminate\Http\Request;

class LanguageController extends ApiModalController
{
    public function index()
    {
        $cities = LanguageResource::collection(
            applyListingOrder(Language::query())->get()
        );

        return $this->success($cities,200, __('dashboard.list_of_languages'));
    }

    public function show(Language $language)
    {
        $language = new LanguageResource($language);

        return $this->success($language);
    }
}
