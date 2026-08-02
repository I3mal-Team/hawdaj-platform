<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Caravan;
use App\Models\Event;
use App\Models\Feature;
use App\Models\Place;
use App\Models\Store;
use App\Models\Swalef;
use App\Models\ZadElgadel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class GetAllModulesDataController extends Controller
{
    public function index()
    {
        // Fetch all
        $places = Place::all()->pluck('slug')->toArray();
        $stores = Store::all()->pluck('slug')->toArray();
        $events = Event::all()->pluck('slug')->toArray();
        $swalefs = Swalef::all()->pluck('slug')->toArray();
        $zadElgadels = ZadElgadel::all()->pluck('slug')->toArray();

        $content = "All Places:\n";

        foreach ($places as $place) {
            if($place) {
                $content.= "https://hawdaj.net/ar/places/details/$place" . "\n";
                $content.= "https://hawdaj.net/en/places/details/$place" . "\n";
            }
        }

        $content.= "---------------------------------------------------------------------------------------------------\n
         All Stores:\n";

        foreach ($stores as $store) {
            if($store) {
                $content.= "https://hawdaj.net/ar/stores/$store" . "\n";
                $content.= "https://hawdaj.net/en/stores/$store" . "\n";
            }
        }

        $content.= "---------------------------------------------------------------------------------------------------\n
         All Events:\n";

        foreach ($events as $event) {
            if($event) {
                $content.= "https://hawdaj.net/ar/events/event-details/$event" . "\n";
                $content.= "https://hawdaj.net/en/events/event-details/$event" . "\n";
            }
        }

        $content.= "---------------------------------------------------------------------------------------------------\n
         All Swalefs:\n";

        foreach ($swalefs as $swalef) {
            if($swalef) {
                $content.= "https://hawdaj.net/ar/stories/$swalef" . "\n";
                $content.= "https://hawdaj.net/en/stories/$swalef" . "\n";
            }
        }

        $content.= "---------------------------------------------------------------------------------------------------\n
         All Restaurants:\n";

        foreach ($zadElgadels as $zadElgadel) {
            if($zadElgadel) {
                $content.= "https://hawdaj.net/ar/restaurants/$zadElgadel" . "\n";
                $content.= "https://hawdaj.net/en/restaurants/$zadElgadel" . "\n";
            }
        }

        // Return as a text file download response
        return Response::make($content, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="all_links.txt"'
        ]);
    }
}
