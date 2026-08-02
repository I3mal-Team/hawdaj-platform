<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Opinion;

class OpinionController extends Controller
{

    // set permission
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-opinion', ['only' => ['index']]);
    }

    public function index()
    {
        $perPage = request('per_page') == -1 ? Opinion::count() : request('per_page' , 10);

        $opinions = Opinion::paginate($perPage);

        return view('dashboard.opinions.index', [
            'title' => __('dashboard.show_all_opinions'),
            'opinions' => $opinions
        ]);
    }

    public function show($id)
    {
        $opinion = Opinion::find($id);
        return view('dashboard.opinions.show', [
            'title' => __('dashboard.show_opinion'),
            'opinion' => $opinion
        ]);
    }
}
