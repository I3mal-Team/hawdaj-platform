<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Offers\OfferCollection;
use App\Http\Resources\Swalefs\SwalefResource;
use App\Models\Opinion;
use App\Models\Swalef;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Newsletter\Newsletter;
use App\Models\Setting;
use App\Http\Resources\offers\ServiceResource;
use App\Models\MostVisit;
use App\Models\Offer;

class OfferController extends ApiModalController
{
    public function offers($zad){
        $offers = applyListingOrder(
            Offer::select('offers.*')->join('menus', 'menus.id', 'offers.menu_id')
                ->join('zad_elgadels', 'zad_elgadels.id', 'menus.zad_id')
                ->where('zad_elgadels.id', $zad)->with('menu')
        )->paginate();
    	$offers = new OfferCollection($offers);
        return $this->success($offers,200, __('dashboard.list_of_offers'));
    }
}
