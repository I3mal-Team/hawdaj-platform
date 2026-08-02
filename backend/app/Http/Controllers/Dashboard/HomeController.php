<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Place;
use App\Models\Site;
use App\Models\Store;
use App\Models\VisitSite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
//        $this->middleware('permission:read-dashboard', ['only' => ['index']]);
    }

    public function index()
    {
        visit(['ip' => \request()->ip(), 'page' => 'home', 'visits' => 1]);
        $title = __('dashboard.home_page');
        $data = [];
        $data['stores'] = Store::where('active', 1)->count() ?? 0;
        $data['places'] = Place::where('active', 1)->count() ?? 0;
        $data['visits'] = VisitSite::count() ?? 0;
        return view('dashboard.index', [
            'title' => __('dashboard.show_title', ['title' => $title ?? __('dashboard.home')]),
            'data' => $data,
        ]);
    }

    public function checkSite()
    {
        $user = auth()->user()->load('sites', 'gates');
        $sites = $user->sites;
        $gates = $user->gates;
        $success = false;

        if (count($sites) === 1 && !auth()->user()->hasRole('guard')) {

            session([
                'site_id' => $sites->first()->id,
                'site_name' => $sites->first()->name,
            ]);

            $success = true;
        }

        if (auth()->id() == 1 || $success == true) {
            return redirect(url('dashboard'))->with(['message' => __('dashboard.enter_site_successfully')]);
        }

        return view('dashboard.sites', [
            'title' => __('dashboard.sites'),
            'sites' => $sites,
            'gates' => $gates,
        ]);
    }

    public function saveSite(Request $request)
    {
        $sites = auth()->user()->sites();

        $request->validate([
            'site_id' => ['required', Rule::in([...$sites->pluck('id')->toArray()])],
        ]);

        $site = Site::find($request->site_id);

        session([
            'site_id' => $site->id,
            'site_name' => $site->name,
        ]);

        return redirect(url('dashboard'))->with(['message' => __('dashboard.enter_site_successfully')]);
    }

    public function updateNotification()
    {
        return Notification::where('notifiable_id', \auth()->user()->id)->update(['read_at' => Carbon::now()]);
    }
}
