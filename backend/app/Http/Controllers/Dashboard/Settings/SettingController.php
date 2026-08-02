<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    // set permission
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:update-setting', ['only' => ['getAllSettings']]);
    }

    /**
     * @return Application|Factory|View
     */
    public function getAllSettings()
    {
        $settings = Setting::where('editable', true)->get();
        $allSettings = $settings->groupBy('group');
        $notifies = array();

        foreach ($settings as $setting) {
            if (strpos($setting->key, 'NOTIFY')) {
                $notifies[] = $setting;
            }
        }

        return view('dashboard.settings.edit', [
            'title' => __('dashboard.general_setting'),
            'settings' => $allSettings,
            'notifies' => $notifies
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function updateAllSettings(Request $request): RedirectResponse
    {
        $data = $request->all();
        // dd($data);
        if (isset($request->SECTION_CONTENT)) {
            Setting::where('type', 'repeater')->delete();
            $i = null;
            // $i = Setting::where('type', 'repeater')->count();
            foreach ($request->SECTION_CONTENT as $key => $value) {
                $k = 'SECTION_CONTENT' . $i++;
                $data[$k] = ($value['SECTION_CONTENT']);
                $setting = Setting::firstOrCreate([
                    'key' => $k,
                    'type' => 'repeater'
                ], [
                    'user_id' => 2,
                    'isEnv' => false,
                    'group' => 'main_services',
                    'editable' => true,
                ]);

                $setting->updateTranslation($data[$k]);
            }
            unset($data['SECTION_CONTENT']);
        }
        try {
            DB::beginTransaction();
            foreach ($data as $key => $item) {
                $setting = Setting::where('key', $key)->first();
                if ($setting) {
                    if ($key == 'APP_LOGO' || $key == 'APP_LOGO_SM') {
                        if (!empty($item))
                            $destination_path = 'public/media';

                        $file = $item;
                        $fileName = time() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs($destination_path, $fileName);
                        $path = '' . str_replace('public/', '', $path);
                        $setting->updateTranslation($path);

                    } else {
                        $setting->updateTranslation($item);
                    }
                }
            }
            if (config('app.edit_env', 0)) {
                $envdata = '';
                foreach (Setting::all() as $setting) {
                    if (!$setting->isEnv) continue;
                    if (!$setting->editable)
                        $envdata .= $setting->key . "=" . $setting->translate('en')->value . PHP_EOL;
                    else {
                        if (!in_array($setting->value, ['true', 'false']))
                            $value = '"' . $setting->translate('en')->value . '"';
                        $envdata .= $setting->key . "=" . $value . PHP_EOL;
                    }
                }
                $file = fopen(app()->environmentFilePath(), 'w');
                fwrite($file, $envdata);
                fclose($file);
            }

            DB::commit();

            return redirect()->back()->with([
                'message' => trans('dashboard.setting_updated_successfully')
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
