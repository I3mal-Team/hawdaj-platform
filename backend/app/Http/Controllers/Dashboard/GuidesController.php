<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Event;
use App\Models\Guide;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class GuidesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-guides', ['only' => ['index']]);
        $this->middleware('permission:delete-guides', ['only' => ['destroy']]);
    }

    public function index()
    {
        $guides = Guide::query();
        $guides->when(request()->name, function ($item) {
            $item->whereTranslationLike('name', '%' . request()->name . '%');
        });

        $perPage = request('per_page') == -1 ? $guides->count() : request('per_page' , 10);

        $guides = $guides->latest()->paginate($perPage);

        $title = __('dashboard.guides');
        return view('dashboard.guides.index', compact('guides', 'title'));
    }

    public function show(int $id)
    {
        $guide = Guide::find($id);

        if (!$guide) {
            return redirect()->route('dashboard.guides.index')->with('error', __('dashboard.not_found'));
        }

        $title = __('dashboard.guides');
        return view('dashboard.guides.show', compact('guide', 'title'));
    }


    public function active(Request $request)
    {
        $active = $request->input('checked');
        $guide_id = $request->input('guide_id');

        if ($guide_id) {
            $guide = Guide::find($guide_id);

            if ($active == 1) {
                $guide->update([
                    'active' => 1,
                ]);
            } else {
                $guide->update([
                    'active' => 0,
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => "Add to active successfully",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "not found",
            ]);
        }
    }

    public function showInHome(Request $request)
    {
        $active = $request->input('checked');
        $guide_id = $request->input('guide_id');

        if ($guide_id) {
            $guide = Guide::find($guide_id);

            if ($active == 1) {
                $guide->update([
                    'show_in_home' => 1,
                ]);
            } else {
                $guide->update([
                    'show_in_home' => 0,
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => "Add to home successfully",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "not found",
            ]);
        }
    }

    public function destroy($id)
    {
        $guide = Guide::findOrFail($id);
        $guide->delete();

        return response()->json([
            'message' => trans('dashboard.delete_successfully'),
        ]);
    }
}
