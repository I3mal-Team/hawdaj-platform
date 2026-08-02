<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\UpdateCeoRequest;
use App\Models\Ceo;
use Illuminate\Support\Facades\DB;

class CeoController extends Controller
{
    public function save(UpdateCeoRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_token');
            if (array_key_exists('ceo_title', $data)) {
                $data['title'] = $data['ceo_title']; // Add the new key with the value
                unset($data['ceo_title']); // Remove the old key
            }
            if (array_key_exists('ceo_description', $data)) {
                $data['description'] = $data['ceo_description']; // Add the new key with the value
                unset($data['ceo_description']); // Remove the old key
            }

            Ceo::updateOrCreate(['parent_id' => $data['parent_id']], $data);
            DB::commit();
            return redirect()->back()->with([
                'message' => trans('dashboard.ceo_updated_successfully'),
            ]);
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }
}
