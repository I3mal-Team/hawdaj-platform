<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Categories\CategoryResource;
use App\Models\FoodCategoryOfZad;
use Illuminate\Http\Request;

class ZadFoodCategoryController extends ApiModalController
{
    public function index(Request $request)
    {
        $categories = FoodCategoryOfZad::whereNull('parent_id');

        if (request('search')) {
            $categories = $categories->where(function ($q) use ($request) {
                $q->whereTranslationLike('name', '%' . $request->search . '%')
                    ->orWhereTranslationLike('notes', '%' . $request->search . '%');
            });
        }

        $categories = CategoryResource::collection(applyListingOrder($categories)->get());

        return $this->success($categories,200, __('dashboard.list_of_zad_food_categories'));
    }
}
