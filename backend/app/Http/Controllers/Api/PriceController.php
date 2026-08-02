<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Prices\PriceResource;
use App\Models\Price;

class PriceController extends ApiModalController
{
    public function index()
    {
        $prices = PriceResource::collection(
            applyListingOrder(Price::where('show', 1))->get()
        );
        return $this->success($prices,200, __('dashboard.list_of_prices'));
    }
}
