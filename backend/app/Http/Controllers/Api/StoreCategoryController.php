<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Categories\StoreCategoryResource;
use App\Models\CategoryOfStore;

class StoreCategoryController extends ApiModalController
{
    public function index()
    {
        $categories = StoreCategoryResource::collection(
            applyListingOrder(CategoryOfStore::whereNull('parent_id'))->get()
        );
        return $this->success($categories,200, __('dashboard.list_of_store_categories'));
    }
}
