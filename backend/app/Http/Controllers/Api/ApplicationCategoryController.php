<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Categories\ApplicationCategoryResource;
use App\Models\CategoryOfApplication;

class ApplicationCategoryController extends ApiModalController
{
    public function index()
    {
        $categories = ApplicationCategoryResource::collection(
            applyListingOrder(CategoryOfApplication::whereNull('parent_id'))->get()
        );
        return $this->success($categories,200, __('dashboard.list_of_application_categories'));
    }
}
