<?php

namespace App\Http\Controllers\Api;

use App\Models\ZadElgadel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Zads\ZadResource;
use App\Http\Resources\Zads\ZadCollection;

class ZadController extends ApiModalController
{
    public function index(Request $request)
    {
        $zads = ZadElgadel::select('zad_elgadels.*')->with('ratings', 'galleries', 'city', 'region', 'ceo')->where('active', 1);

        // Get location coordinates for distance sorting
        $location = getLocationCoords();
        $lat = $location['lat'];
        $lng = $location['lng'];
        $shouldSortByDistance = $location['hasLocation'];

        if (request('search')) {
            $zads = $zads->where(function ($q) use ($request) {
                $q->whereTranslationLike('title', '%' . $request->search . '%')
                    ->orWhereTranslationLike('description', '%' . $request->search . '%');
            });
        }

        if (request('region_id')) {
            $zads = $zads->where('region_id', request('region_id'));
        }

        if (request('city_id')) {
            $zads = $zads->where('city_id', request('city_id'));
        }

        if (request('food_categories')) {
//            $categories_id = explode(',' , request('food_categories')) ?? [];
            $categories_id = request('food_categories') ?? [];
            $zads->join('food_category_zad_elgadels', 'food_category_zad_elgadels.zad_elgadel_id', 'zad_elgadels.id' )
                ->whereIn('food_category_zad_elgadels.food_category_id', $categories_id);
        }

        if (request('categories')) {
            $categories_id = explode(',' , request('categories'));
            $zads->join('category_zad_elgadels', 'category_zad_elgadels.zad_elgadel_id', 'zad_elgadels.id' )
                ->whereIn('category_zad_elgadels.category_id', $categories_id);
        }

        if (request('rate')) {
            $zads = $zads->leftJoin('rates', 'rates.parent_id', '=', 'zad_elgadels.id')
            ->where('rates.type', '=', 'zad_elgadels')
            ->addSelect(DB::raw('AVG(rates.rate) AS rates_average'))
            ->having('rates_average' , "=", request('rate'));
        }

        // Apply distance sorting if location is available
        if ($shouldSortByDistance) {
            $zads = applyDistanceSorting($zads, $lat, $lng);
        } elseif (request('nearest') && request('lat') && request('lng')) {
            // Keep backward compatibility with old 'nearest' parameter
            $nearestLat = request('lat');
            $nearestLng = request('lng');
            $zads = applyDistanceSorting($zads, $nearestLat, $nearestLng);
        } else {
            applyListingOrder($zads);
        }

        $zads = $zads->paginate(request('per_page', 10));

        $zads = new ZadCollection($zads);
        return $this->success($zads, 200, __('dashboard.list_of_zads'));
    }

    public function show($slug)
    {
        $zadElgadel = ZadElgadel::where("slug" , $slug)->firstOrFail();

        return $this->success(new ZadResource($zadElgadel),200, __('dashboard.zad_details'));
    }
}
