<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Sliders\StoreSliderRequest;
use App\Http\Requests\Dashboard\Sliders\UpdateSliderRequest;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

    }

    public function index(): View
    {
        $query = Slider::query();
        $query->when(request('title'), function ($q) {
            $q->whereTranslationLike('title', '%' . request('title') . '%');
        });

        $perPage = request('per_page') == -1 ? $query->count() : request('per_page', 10);
        $sliders = $query->ordered()->paginate($perPage);
        $title = __('dashboard.sliders');

        return view('dashboard.sliders.index', compact('sliders', 'title'));
    }

    public function create(): View
    {
        $title = __('dashboard.create_slider');

        return view('dashboard.sliders.create', compact('title'));
    }

    public function store(StoreSliderRequest $request)
    {
        $data = [
            'order_id' => (int) ($request->input('order_id', 0)),
            'active' => $request->has('active'),
            'link' => $request->filled('link') ? $request->input('link') : null,
        ];

        $slider = Slider::create($data);

        if ($request->hasFile('image')) {
            $slider->clearMediaCollection('image');
            $slider->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return redirect()->route('dashboard.sliders.index')->with([
            'message' => trans('dashboard.slider_added_successfully'),
        ]);
    }

    public function edit(int $id): View
    {
        $slider = Slider::findOrFail($id);
        $title = __('dashboard.edit_slider');

        return view('dashboard.sliders.edit', compact('slider', 'title'));
    }

    public function update(UpdateSliderRequest $request, int $id)
    {
        $slider = Slider::findOrFail($id);

        $slider->update([
            'order_id' => (int) ($request->input('order_id', $slider->order_id)),
            'active' => $request->has('active'),
            'link' => $request->filled('link') ? $request->input('link') : null,
        ]);

        if ($request->hasFile('image')) {
            $slider->clearMediaCollection('image');
            $slider->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return redirect()->route('dashboard.sliders.index')->with([
            'message' => trans('dashboard.slider_updated_successfully'),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $slider = Slider::findOrFail($id);
            $slider->clearMediaCollection('image');
            $slider->delete();
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
