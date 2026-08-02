<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\UpdateEventRequest;
use App\Http\Requests\Dashboard\EventRequest;
use App\Models\City;
use App\Models\Event;
use App\Models\Region;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{

    // set permission
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-event', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-event', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-event', ['only' => ['edit', 'update', 'updateStatus']]);
        $this->middleware('permission:delete-event', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        visit(['ip' => \request()->ip(), 'page' => 'events', 'visits' => 1]);

        // $allCategories = CategoryOfEvent::all();

        $all = Event::with('user')
            ->when($request->title, function ($q) use ($request) {
                return $q->whereTranslationLike('title', '%' . $request->title . '%');
            })->when($request->categories, function ($qq) use ($request) {
                return $qq->where('categories', $request->categories);
            })->when($request->user_places, function ($q) {
                return $q->whereNotNull('user_id');
            });

        if (request('archive', 0)) {
            $all = $all->onlyTrashed();
        }

        $all = $all->latest();

        $perPage = request('per_page') == -1 ? $all->count() : request('per_page' , 10);

        $all = $all->paginate($perPage);


        return view('dashboard.events.index', [
            'title' => trans('dashboard.events'),
            'events' => $all,
            // 'categories' => $allCategories,
        ]);
    }

    public function show(int $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return view('dashboard.events.show', compact('event'))->with([
                'message' => trans('dashboard.event_not_found'),
                'type' => 'error'
            ]);
        }

        return view('dashboard.events.show', compact('event'));
    }

    public function activate(Request $request)
    {
        $active = $request->input('checked');
        $event_id = $request->input('eventId');

        if ($event_id) {
            $event = Event::find($event_id);

            if ($active == 1) {
                $event->update([
                    'active' => 1,
                ]);
            } else {
                $event->update([
                    'active' => 0,
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => "activation updated",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "activation cant be updated",
            ]);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
            'rejected_reason' => 'required_if:status,rejected'
        ]);

        $event = Event::findOrFail($id);

        $event->update([
            'status' => $request->status,
            'rejected_reason' => $request->status === 'rejected' ? $request->rejected_reason : null
        ]);

        return redirect()->back()->with([
            'message' => trans('dashboard.status_updated_successfully'),
            'type' => 'success'
        ]);
    }

    public function create()
    {
        return view('dashboard.events.create', [
            'title' => __('dashboard.new_event'),
            'categories' => [],
            'regions' => Region::all(),
            'cities' => City::all(),
            'events' => Event::all(),
        ]);
    }


    public function store(EventRequest $request)
    {
        try {
            $data = $request->all();

            if (isset($request->active)) {
                $data['active'] = 1;
            } else {
                $data['active'] = 0;
            }

            if (isset($request->featured)) {
                $data['featured'] = 1;
            } else {
                $data['featured'] = 0;
            }

            if ($request->type == 'map') {
                $data['type'] = 'map';
                $data['address'] = request('address');
                $data['lat'] = request('lat');
                $data['long'] = request('long');
            } elseif ($request->type == 'link') {
                $data['type'] = 'link';
                $data['address'] = request('address');
                $link = explode('@', $data['address']);
                if (is_array($link)) {
                    $lat_long = explode(',', end($link));
                    if (is_array($lat_long) && count($lat_long) == 3) {
                        $data['lat'] = isset($lat_long[0]) && is_numeric($lat_long[0]) ? $lat_long[0] : null;
                        $data['long'] = isset($lat_long[1]) && is_numeric($lat_long[1]) ? $lat_long[1] : null;
                    }
                }
            } elseif($request->type == 'latlng') {
                $data['type'] = 'latlng';
                $data['address'] = request('address');
                $data['lat'] = request('lat');
                $data['long'] = request('long');
            }


            if (request('categories')) {
                $data['categories'] = collect($request->categories)->map(fn($i) => (int)$i);
            }

            $data['order_id'] = (int) ($data['order_id'] ?? 0);

            $res = Event::create($data);

            if (request()->hasFile('image')) {
                $res->clearMediaCollection('image');
                $res->addMediaFromRequest('image')->toMediaCollection('image');
            }

            DB::commit();

            if ($res) {
                return redirect(route('dashboard.events.edit', $res->id))->with([
                    'message' => trans('dashboard.events_added_successfully'),
                ]);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return unKnownError($e->getMessage());
        }
    }


    public function edit(Event $event)
    {
        $reg = isset($event->region->cities) ? $event->region->cities : [];

        $inside_map = isset($place['distance']) && $place['distance'] > 0 ? Event::select('id', 'lat', 'long')->where('id', '!=', $event->id)->where('active', 1)->get()->map(function ($i) use ($event) {
            if (round(distance($event['lat'], $event['long'], $i['lat'], $i['long'], "M"), 1) <= $event['distance'] ?? 0) {
                return (string)$i['id'];
            }
        }) : [];

        $all_related_items = array_unique(array_merge(array_filter($inside_map ? $inside_map->toArray() : []) ?? [], $event->related ?? []));

        return view('dashboard.events.edit', [
            'title' => __('dashboard.edit_event'),
            'categories' => [],
            'events' => Event::all(),
            'data' => $event,
            'regions' => Region::all(),
            'mycities' => $reg,
            'all_related_items' => $all_related_items
        ]);
    }


    public function related(Request $request, $id)
    {
        try {

            $request->validate([
                'related' => 'nullable',
            ]);

            $inputs = $request->only(['related']);
            $inputs['related'] = isset($inputs['related']) ? $inputs['related'] : null;
            $data = Event::find($id);
            $data->update($inputs);
            return redirect()->back()->with([
                'message' => trans('dashboard.updated_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }


    public function update(UpdateEventRequest $request, $id)
    {
        try {
            $data = $request->all();

            if ($request->type == 'map') {
                $data['type'] = 'map';
                $data['address'] = request('address');
                $data['lat'] = request('lat');
                $data['long'] = request('long');
            } elseif ($request->type == 'link') {
                $data['type'] = 'link';
                $data['link'] = request('address');
                $data['address'] = request('address');
                $link = explode('@', $data['address']);
                if (is_array($link)) {
                    $lat_long = explode(',', end($link));
                    if (is_array($lat_long) && count($lat_long) == 3) {
                        $data['lat'] = isset($lat_long[0]) && is_numeric($lat_long[0]) ? $lat_long[0] : null;
                        $data['long'] = isset($lat_long[1]) && is_numeric($lat_long[1]) ? $lat_long[1] : null;
                    }
                }
            } else {
                $data['type'] = 'link';
                $data['link'] = request('address');
                $data['address'] = request('address');
                $data['lat'] = request('lat');
                $data['long'] = request('long');
            }

            if (isset($request->active)) {
                $data['active'] = 1;
            } else {
                $data['active'] = 0;
            }

            if (isset($request->featured)) {
                $data['featured'] = 1;
            } else {
                $data['featured'] = 0;
            }

            if (request('categories')) {
                $data['categories'] = collect($request->categories)->map(fn($i) => (int)$i);
            }

            $data['order_id'] = (int) ($data['order_id'] ?? 0);

            $res = Event::where('id', $id)->first();

            if ($res) {
                $res->update($data);

                if (request()->hasFile('image')) {
                    $res->clearMediaCollection('image');
                    $res->addMediaFromRequest('image')->toMediaCollection('image');
                }
                DB::commit();
                return redirect(route('dashboard.events.edit', $res->id))->with([
                    'message' => trans('dashboard.updated_successfully'),
                ]);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {

            DB::rollBack();
            // throw $e;
            return unKnownError($e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            return Event::withTrashed()->find($id)->restore();
        } catch (\Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            if (request('archive')) {
                $event = Event::withTrashed()->find($id);
                $event->forceDelete();
            } else {
                $event = Event::findOrFail($id);
                $event->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    public function destroy_selected(Request $request)
    {
        $arr_ids = $request->array_ids;
        if (request('archive')) {
            Event::withTrashed()->whereIn('id', $arr_ids)->forceDelete();
        } else {
            Event::whereIn('id', $arr_ids)->delete();
        }
        return response()->json([
            'status' => true,
            'message' => "events deleted",
        ]);
    }
}
