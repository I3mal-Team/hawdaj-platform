<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Mail\CreateSuggestPlace;
use App\Models\SuggestPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SuggestController extends Controller
{

    // set permission
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-suggest', ['only' => ['index']]);
    }

    public function index()
    {
        $suggests = SuggestPlace::get();

        return view('dashboard.suggest.index', [
            'title' => trans('dashboard.suggests'),
            'suggests' => $suggests
        ]);
    }

    public function activate($id)
    {
        $active = request('active');

        if ($id) {
            $place = SuggestPlace::find($id);

            if ($active == 1) {
                $place->update([
                    'active' => 0,
                ]);
            } else {
                $place->update([
                    'active' => 1,
                ]);

                try {
                    Mail::to($place['email'])->send(new CreateSuggestPlace($place));
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        }
        return back()->with('message', 'updated successfully');
    }
}
