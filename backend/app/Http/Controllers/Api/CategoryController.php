<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Categories\CategoryResource;
use App\Models\Category;

class CategoryController extends ApiModalController
{
    public function index()
    {
        $categories = CategoryResource::collection(
            applyListingOrder(Category::whereNull('parent_id'))->get()
        );

        return $this->success($categories,200, __('dashboard.list_of_categories'));
    }

    public function getSubCategories($parent_id)
    {
        $categories = CategoryResource::collection(
            applyListingOrder(Category::where('parent_id', $parent_id))->get()
        );

        return $this->success($categories,200, __('dashboard.list_of_sub_categories'));

    }
}
