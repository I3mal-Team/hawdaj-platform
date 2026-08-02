<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Swalefs\SwalefResource;
use App\Http\Resources\Swalefs\SwalefCollection;
use App\Models\Swalef;
use Illuminate\Http\Request;

class SwalefController extends ApiModalController
{
    /**
     * Index swalefs
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */

    public function index(Request $request)//: \Illuminate\Http\Resources\Json\JsonResource
    {
        $swalefs = Swalef::where('active', 1);


        if (request('search')) {
            $swalefs = $swalefs->where(function ($q) use ($request) {
                $q->whereTranslationLike('title', '%' . $request->search . '%')
                    ->orWhereTranslationLike('description', '%' . $request->search . '%');
            });
        }

        if (request('category_id')) {
            $categories_id = explode(',' , request('category_id'));
            $swalefs->where(function($query)use($categories_id){
                foreach ($categories_id as $category_id) {
                    $query->orWhereRaw("JSON_CONTAINS(categories, '" . $category_id . "' )");
                }
            });
        }

        if(request('top_featured')){
            $swalefs->where('featured', true);
        }

        $swalefs = applyListingOrder($swalefs)->paginate(request('per_page' , 10));

        $swalefs = new SwalefCollection($swalefs);

        return $this->success($swalefs,200, __('dashboard.list_of_swalefs'));
    }

    public function show($slug)
    {
        $swalef = Swalef::where("slug" , $slug)->firstOrFail();
        return $this->success(new SwalefResource($swalef),200, __('dashboard.swalef_details'));
    }
}
