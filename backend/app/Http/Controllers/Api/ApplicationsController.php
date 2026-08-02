<?php

namespace App\Http\Controllers\Api;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Resources\Applications\ApplicationResource;
use App\Http\Resources\Applications\ApplicationCollection;

class ApplicationsController extends ApiModalController
{
    public function index(Request $request)
    {
        $applications = Application::where('active', 1);

        if (request('search')) {
            $applications = $applications->where(function ($q) use ($request) {
                $q->whereTranslationLike('title', '%' . $request->search . '%')
                    ->orWhereTranslationLike('description', '%' . $request->search . '%');
            });
        }

        if (request('category_id')) {
            $categories_id = explode(',' , request('category_id'));
            $applications->where(function($query)use($categories_id){
                foreach ($categories_id as $category_id) {
                    $query->orWhereRaw("JSON_CONTAINS(categories, '" . $category_id . "' )");
                }
            });
        }

        $applications = applyListingOrder($applications)->paginate(request('per_page' , 10))->onEachSide(2);

        $applications = new ApplicationCollection($applications);

        return $this->success($applications,200, __('dashboard.list_of_applications'));
    }

    public function show($slug)  {
        $application = Application::where("slug" , $slug)->firstOrFail();
        return $this->success(new ApplicationResource($application),200, __('dashboard.application_details'));
    }
}
