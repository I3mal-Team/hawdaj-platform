<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CategoryOfSwalef;
use App\Models\Gallery;
use App\Models\Swalef;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SwalefController extends Controller
{

    // set permission
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-swalefs', ['only' => ['index']]);
        $this->middleware('permission:create-swalefs', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-swalefs', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-swalefs', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Swalef::query();

        if ($request->user_places) {
            $query->whereNotNull('user_id');
        }

        if(request('per_page' , 10) > 0){
            $swalefs = $query->paginate(request('per_page' , 10));
        }else{
            $swalefs = $query->get();
        }

        return view('dashboard.swalef.index', [
            'title' => trans('dashboard.swalefs'),
            'swalefs' => $swalefs
        ]);
    }

    public function create()
    {
        return view('dashboard.swalef.create', [
            'types' => Swalef::TYPES,
            'categories' => CategoryOfSwalef::all(),
            'title' => __('dashboard.create_swalef')
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->except('_token');

            \DB::beginTransaction();


            if ($request->active == 'on') {
                $data['active'] = 1;
            } else {
                $data['active'] = 0;
            }

            if ($request->featured == 'on') {
                $data['featured'] = 1;
            } else {
                $data['featured'] = 0;
            }

            if (request('categories')) {
                $data['categories'] = collect($request->categories)->map(fn($i) => (int)$i);
            }

            if (request('mainCharacters')) {
                unset($data['mainCharacters']);
                $mainCharactersRaw = json_decode($request->mainCharacters, true);
                
                // Check if it's valid JSON (from old Tagify) or plain text
                if (is_array($mainCharactersRaw) && !empty($mainCharactersRaw)) {
                    // Old format: JSON array from Tagify
                    $mainCharacters = array_map(function($item) {
                        return is_array($item) && isset($item['value']) ? $item['value'] : $item;
                    }, $mainCharactersRaw);
                    $data['mainCharacters'] = json_encode($mainCharacters);
                } else {
                    // New format: plain text
                    $data['mainCharacters'] = $request->mainCharacters;
                }
            }

            if (request('categories')) {
                $data['categories'] = collect($request->categories)->map(fn($i) => (int)$i);
            }

            $data['order_id'] = (int) $request->input('order_id', 0);

            $swalef = Swalef::create($data);

            if($request->has('images') && count($request->images)>0){
                foreach ($request->images as $image){

                    Gallery::create([
                        'parent_id' => $swalef->id,
                        'file'=>$image,
                        'type' => 'swalefs',
                        'mime_type' => detectMimeType($image),
                    ]);
                }
            }

            \DB::commit();
            return redirect(route('dashboard.swalefs.index'))->with([
                'message' => trans('dashboard.swalef_added_successfully')
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Swalef $swalef)
    {
        return view('dashboard.swalef.edit', [
            'title' => __('dashboard.edit_swalef'),
            'types' => Swalef::TYPES,
            'categories' => CategoryOfSwalef::all(),
            'swalef' => $swalef
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param swalef $swalef
     * @return \Illuminate\Http\Response
     */
    public function update(Swalef $swalef, Request $request)
    {
        try {
            $data = $request->except(['_token', ...locales()]);

            DB::beginTransaction();

//            if ($request->hasFile('content')) {
//                UploadService::delete($swalef->content);
//                $data['content'] = UploadService::store($request['content'], 'swalefs');
//            }
            if (isset($request->active) && $request->active == 'on') {
                $data['active'] = 1;
            } else {
                $data['active'] = 0;
            }

            if (isset($request->featured) && $request->featured == 'on') {
                $data['featured'] = 1;
            } else {
                $data['featured'] = 0;
            }

            $data['order_id'] = (int) $request->input('order_id', 0);

            $swalef->update($data);

            if ($request->hasFile('image')) {
                $swalef->clearMediaCollection('image');
                $swalef->addMediaFromRequest('image')->toMediaCollection('image');
            }

//            $swalef->updateTranslation();
            DB::commit();

            return redirect(route('dashboard.swalefs.index'))->with([
                'message' => trans('dashboard.updated_successfully')
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param swalef $swalef
     * @return \Illuminate\Http\Response
     */
    public function destroy(Swalef $swalef)
    {
        if($swalef->image) {
            UploadService::delete($swalef->image);
        }

        $swalef->delete();

        return response()->json([
            'message' => trans('dashboard.swalef_delete_successfully')
        ]);
    }



    public function showInHome(Request $request)
    {
        $active = $request->input('checked');
        $swalef_id = $request->input('swalef_id');

        if ($swalef_id) {
            $swalef = Swalef::find($swalef_id);

            if ($active == 1) {
                $swalef->update([
                    'show_in_home' => 1,
                ]);
            } else {
                $swalef->update([
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

}
