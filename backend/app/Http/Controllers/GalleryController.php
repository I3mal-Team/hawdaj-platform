<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiModalController;
use App\Http\Resources\ItemGallery\ItemGalleryCollection;
use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Services\UploadService;
use Illuminate\Support\Facades\Validator;

class GalleryController extends ApiModalController
{
    public function saveGallery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required'],
            'parent_id' => ['required', 'numeric'],
            'type' => ['required', 'string', 'in:guides,places'],
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $mimeType = $request->file('file')->getMimeType();

        if (str_starts_with($mimeType, 'image/')) {
            $mimeType = 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            $mimeType = 'video';
        } elseif ($mimeType === 'application/pdf') {
            $mimeType = 'pdf';
        } elseif (in_array($mimeType, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
            $mimeType = 'word';
        } elseif (in_array($mimeType, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
            $mimeType = 'excel';
        }

        Gallery::create([
            'parent_id' => $request->parent_id,
            'file' => UploadService::storeFile($request->file, 'gallery'),
            'type' => $request->type,
            'active' => 0,
            'mime_type' => $mimeType,
        ]);

        $itemGallery = Gallery::where('parent_id', $request->parent_id)->where('type', $request->type);

        $itemGallery = $itemGallery->paginate(request('per_page' , 10))->onEachSide(2);

        $itemGallery = new ItemGalleryCollection($itemGallery);

        return $this->success($itemGallery,200,'List item gallery');
    }

    public function listGallery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => ['required', 'numeric'],
            'type' => ['required', 'string', 'in:guides,places'],
        ]);

        if ($validator->fails()) {
            return $this->error(422 , $validator->errors()->first());
        }

        $itemGallery = Gallery::where('parent_id', $request->parent_id)->where('type', $request->type);

        $itemGallery = $itemGallery->paginate(request('per_page' , 10))->onEachSide(2);

        $itemGallery = new ItemGalleryCollection($itemGallery);

        return $this->success($itemGallery,200,'List item gallery');
    }
}
