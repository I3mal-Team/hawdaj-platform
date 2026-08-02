<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Menus\MenuRequest;
use App\Http\Requests\Dashboard\Menus\UpdateMenuRequest;
use App\Models\ZadElgadel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use App\Services\UploadService;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        // $this->middleware('permission:read-menus', ['only' => ['index']]);
        // $this->middleware('permission:create-menus', ['only' => ['create', 'store']]);
        // $this->middleware('permission:update-menus', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete-menus', ['only' => ['destroy']]);
    }

    public function index(int $zadId = null): View
    {
        $menus = Menu::query()->with('zad');

        if ($zadId) {
            $menus->where('zad_id', $zadId);
        }

        $menus->when(request()->title, function ($item) {
            $item->whereTranslationLike('title', '%' . request()->title . '%');
        });

        $menus = applyListingOrder($menus);

        if(request('per_page' , 10) > 0){
            $menus = $menus->paginate(request('per_page' , 10));
        }else{
            $menus = $menus->get();
        }
        
        return view('dashboard.menus.index', [
            'title' => __('dashboard.Menus'),
            'menus' => $menus,
        ]);
    }


    public function create(): View
    {
        $zads = ZadElgadel::all();

        return view('dashboard.menus.create', compact('zads'));
    }


    public function store(MenuRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['order_id'] = (int) ($data['order_id'] ?? 0);
        if (request()->hasFile('image')) {
            $data['image'] = UploadService::store($request->image, 'menus');
        }

        $res = Menu::create($data);
        if ($res) {
            return redirect(route('dashboard.menu.index'))->with([
                'message' => trans('dashboard.menu_added_successfully'),
            ]);
        } else {
            abort(404);
        }
    }

    public function edit($id): View
    {
        $zads = ZadElgadel::all();
        $menu = Menu::findOrFail($id);

        return view('dashboard.menus.edit', compact('zads', 'menu'));
    }


    public function update(UpdateMenuRequest $request, $id): RedirectResponse
    {

        $menu = Menu::findOrFail($id);

        $data = $request->validated();
        $data['order_id'] = (int) ($data['order_id'] ?? 0);
        if ($request->has('image')) {
            if (file_exists(public_path('uploads/' . $menu->image))) {
                unlink(public_path('uploads/' . $menu->image));

            }
            $data['image'] = UploadService::store($request->image, 'menus');
            $menu->update($data);
            $menu->updateTranslation();
        } else {
            $menu->update($data);
            $menu->updateTranslation();
        }

        return redirect(route('dashboard.menu.index'))->with([
            'message' => trans('dashboard.menu_updated_successfully'),
        ]);

    }


    public function destroy($id): JsonResponse
    {
        DB::beginTransaction();
        try {
            if (request('archive')) {
                $menu = Menu::withTrashed()->find($id);
                if (file_exists(public_path('uploads/' . $menu->image))) {
                    unlink(public_path('uploads/' . $menu->image));
                }
                $menu->forceDelete();
            } else {
                $menu = Menu::findOrFail($id);
                if (file_exists(public_path('uploads/' . $menu->image))) {
                    unlink(public_path('uploads/' . $menu->image));
                }
                $menu->delete();
            }
            DB::commit();

            return response()->json([
                'message' => trans('dashboard.delete_successfully'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return unKnownError($e->getMessage());
        }
    }

}
