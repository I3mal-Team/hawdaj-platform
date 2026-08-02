<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Sliders\SliderResource;
use App\Models\Slider;

class SliderController extends ApiModalController
{
    public function index()
    {
        $sliders = Slider::query()
            ->active()
            ->ordered()
            ->get();

        return $this->success(
            SliderResource::collection($sliders),
            200,
            __('dashboard.list_of_sliders')
        );
    }
}
