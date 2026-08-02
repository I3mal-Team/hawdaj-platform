<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends ApiModalController
{
    public function getSocialMedia(Request $request)  {
        $items = Setting::where("group" , 'social_media')->get();

        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'key' => $item->key,
                'value' => $item->value,
                'group' => $item->group,
            ];
        }

        return $this->success($data,200, __('dashboard.settings_data'));
    }
}
