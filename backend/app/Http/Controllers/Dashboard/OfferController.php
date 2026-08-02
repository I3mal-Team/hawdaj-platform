<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Offers\UpdateOfferRequest;
use App\Http\Requests\Dashboard\Offers\OfferRequest;
use App\Models\ZadElgadel;
use App\Services\UploadService;
use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;


class OfferController extends Controller
{

    // set permission
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-zadElgadelOffer', ['only' => ['index']]);
        $this->middleware('permission:create-zadElgadelOffer', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-zadElgadelOffer', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-zadElgadelOffer', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offer::query()->with('menu');
        $offers->when(request()->title, function ($item) {
            $item->whereTranslationLike('title', '%' . request()->title . '%');
        });

        $perPage = request('per_page') == -1 ? $offers->count() : request('per_page' , 10);

        $offers = $offers->paginate($perPage);

        $title = __('dashboard.Offers');
        return view('dashboard.offers.index', compact('offers', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menus = ZadElgadel::all();
        $title = __('dashboard.create_offer');
        return view('dashboard.offers.create', compact('menus', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfferRequest $request)
    {
        $data = $request->validated();
        $data['order_id'] = (int) ($data['order_id'] ?? 0);
        if (request()->hasFile('image')) {
            $data['image'] = UploadService::store($request->image, 'offers');
        }

        $res = Offer::create($data);
        if ($res) {
            return redirect(route('dashboard.offer.index'))->with([
                'message' => trans('dashboard.offer_added_successfully'),
            ]);
        } else {
            abort(404);
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
    public function edit($id)
    {
        $offer = Offer::findOrFail($id);
        $menus = ZadElgadel::all();
        $title = __('dashboard.edit_offer');
        return view('dashboard.offers.edit', compact('menus', 'offer', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOfferRequest $request, $id)
    {
        $offer = Offer::findOrFail($id);
        $data = $request->validated();
        $data['order_id'] = (int) ($data['order_id'] ?? 0);
        if ($request->has('image')) {
            if (file_exists(public_path('uploads/' . $offer->image))) {
                unlink(public_path('uploads/' . $offer->image));

            }
            $data['image'] = UploadService::store($request->image, 'offers');

        }

        $offer->update($data);

        return redirect(route('dashboard.offer.index'))->with([
            'message' => trans('dashboard.offer_updated_successfully'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            if (request('archive')) {
                $offer = Offer::withTrashed()->find($id);
                if (file_exists(public_path('uploads/' . $offer->image))) {
                    unlink(public_path('uploads/' . $offer->image));
                }
                $offer->forceDelete();
            } else {
                $offer = Offer::findOrFail($id);
                if (file_exists(public_path('uploads/' . $offer->image))) {
                    unlink(public_path('uploads/' . $offer->image));
                }
                $offer->delete();
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
