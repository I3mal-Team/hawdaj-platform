<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\SiteRequest;
use App\Models\Site;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class SiteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('dashboard.settings.sites.index', [
            'title' => trans('dashboard.sites'),
            'sites' => Site::all()
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('dashboard.settings.sites.create', [
            'title' => __('dashboard.create_site')
        ]);
    }

    /**
     * @param SiteRequest $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function store(SiteRequest $request)
    {
        try {
            $site = Site::create($request->only(['name', 'address', 'location_name', 'latitude', 'longitude']));

            userRoot()->sites()->attach($site->id);

            return redirect(route('dashboard.sites.index'))->with([
                'message' => trans('dashboard.site_added_successfully')
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param Site $site
     * @return Application|Factory|View
     */
    public function edit(Site $site)
    {
        return view('dashboard.settings.sites.edit', [
            'title' => __('dashboard.edit_site'),
            'site' => $site
        ]);
    }

    /**
     * @param SiteRequest $request
     * @param Site $site
     * @return Application|RedirectResponse|Redirector
     */
    public function update(SiteRequest $request, Site $site)
    {
        $site->update([
            'name' => $request->name,
            'address' => $request->address,
            'location_name' => $request->location_name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect(route('dashboard.sites.index'))->with([
            'message' => trans('dashboard.site_updated_successfully')
        ]);
    }

    /**
     * @param Site $site
     * @return JsonResponse
     */
    public function destroy(Site $site): JsonResponse
    {
        $site->delete();

        return response()->json([
            'message' => trans('dashboard.site_delete_successfully')
        ]);
    }
}
