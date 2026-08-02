<?php

namespace Modules\CarModel\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\CarModel\Entities\CarNotes;

class CarNotesController  extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $site_id = request()->site_id ?? '';
        $title = __('dashboard.car_notes');
        $notes = CarNotes::with('owner','car','car.site')->latest();

        if(!empty($site_id)){
            $notes->whereHas('car',function ($q) use ($site_id){
                $q->where('site_id',$site_id);
            });
        }
        $notes = $notes->paginate(10);

        return view('CarModel::notes.index', [
            'title' => $title,
            'notes' => $notes,
        ]);
    }

    public function show($id)
    {
        $title = __('dashboard.car_notes');
        $notes = CarNotes::find($id);

        return view('CarModel::notes.show', [
            'title' => $title,
            'notes' => $notes,
        ]);
    }

    Public function destroy($id): JsonResponse
    {
        CarNotes::where('id',$id)->delete();

        return response()->json([
            'message' => trans('dashboard.delete_successfully')
        ]);
    }
}
