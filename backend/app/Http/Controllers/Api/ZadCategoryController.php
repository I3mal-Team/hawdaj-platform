<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\CategoryOfZad;
use App\Http\Resources\Categories\ZadCategoryResource;

class ZadCategoryController extends ApiModalController
{
    public function index(Request $request)
    {
        $categories = CategoryOfZad::whereNull('parent_id');

        if (request('search')) {
            $categories = $categories->where(function ($q) use ($request) {
                $q->whereTranslationLike('name', '%' . $request->search . '%')
                    ->orWhereTranslationLike('notes', '%' . $request->search . '%');
            });
        }

        $categories = ZadCategoryResource::collection(applyListingOrder($categories)->get());

        return $this->success($categories,200, __('dashboard.list_of_zad_categories'));
    }
}
