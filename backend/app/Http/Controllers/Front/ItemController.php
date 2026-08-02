<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\SuggestPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{

    public function add_suggest(Request $request)
    {

        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => [Rule::requiredIf(Auth::guest()), 'email'],
            'name' => [Rule::requiredIf(Auth::guest()), 'string', 'max:255'],
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toArray(), 422);
        }

        $data = $request->except('_token');

        if (auth()->check()) {
            $data['name'] = auth()->user()->first_name;
            $data['email'] = auth()->user()->email;
        }

        SuggestPlace::firstOrCreate($data);

        return response()->json([]);
    }

    public function get_all_events()
    {
        $place = [];
        return view('front.evnevts.all', compact('place'));
    }
}
