<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Categories\SwalefCategoryResource;
use App\Models\CategoryOfSwalef;

class SwalefCategoryController extends ApiModalController
{
    public function index()
    {
        $categories = SwalefCategoryResource::collection(
            applyListingOrder(CategoryOfSwalef::whereNull('parent_id'))->get()
        );
        return $this->success($categories,200, __('dashboard.list_of_swalef_categories'));
    }
}
