<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class SeedSocialMediaOnSettingTable extends Migration
{
    public function up()
    {
        $keys = ['facebook', 'INSTGRAM', 'TWITTER', 'GOOGLE', 'instagram', 'twitter'];

        Setting::whereIn('key', $keys)->delete();

        Setting::Create([
            'key' => 'facebook',
            'group' => 'social_media',
            'editable' => 1,
            'user_id' =>User::first()->id,
        ],[
            'value' => 'https://www.facebook.com/profile.php?id=100066544040151&name=xhp_nt__fb__action__open_user'
        ]);

        Setting::Create([
            'key' => 'instagram',
            'group' => 'social_media',
            'editable' => 1,
            'user_id' =>User::first()->id,
        ],[
            'value' => 'https://www.instagram.com/hawdaj7?igsh=MXNkaW5wNm52aWQzeg== '
        ]);

        Setting::Create([
            'key' => 'twitter',
            'group' => 'social_media',
            'editable' => 1,
            'user_id' =>User::first()->id,
        ],[
            'value' => 'https://twitter.com/Hawdaj7'
        ]);
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
