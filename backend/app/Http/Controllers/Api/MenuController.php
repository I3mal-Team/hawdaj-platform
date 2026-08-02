<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Menu\MenuCollection;
use App\Http\Resources\Menu\MenuResource;
use App\Models\Menu;

class MenuController extends ApiModalController
{
    public function getMenusByZad($id)
    {
        
        $menus = Menu::query()
                 ->with('zad')->where('zad_id' , $id);
        
        $menus->when(request()->title, function($item){
                    $item->whereTranslationLike('title', '%' . request()->title . '%');
                });
                
        $menus = applyListingOrder($menus)->paginate(request('per_page' , 10))->onEachSide(2);

        $menus = new MenuCollection($menus);

        return $this->success($menus,200, __('dashboard.list_of_menus'));
    }

    public function show(Menu $menu)
    {
        return $this->success(new MenuResource($menu),200, __('dashboard.menu_details'));
    }
}
