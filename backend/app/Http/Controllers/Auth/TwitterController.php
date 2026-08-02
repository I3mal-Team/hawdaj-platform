<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class TwitterController
{

    public function redirect()
    {
        return Socialite::driver('twitter-oauth-2')->redirect();
    }

    public function callback()
    {
        $user = Socialite::driver('twitter-oauth-2')->user();
        $name = explode(" ", $user->getName());
        $first_name = $name[0];
        $last_name = $name[1] ?? "";
        $user = User::updateOrCreate([
            'provider_id' => $user->id,
            'provider_type' => "twitter",
        ], [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $user->email ?? (str_replace(" ", "", $user->getNickname()) . "@hawdaj.com"),
        ]);

        Auth::login($user);

        return redirect()->to(route("front.index"));
    }

}
