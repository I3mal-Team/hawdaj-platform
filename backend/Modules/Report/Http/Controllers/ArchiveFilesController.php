<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Report\Entities\ArchiveFile;

class ArchiveFilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-archive_file', ['only' => ['index', 'download']]);
        $this->middleware('permission:delete-archive_file', ['only' => ['destroy']]);
    }

    public function index($modalType)
    {
        $files = ArchiveFile::where('model_type', $modalType)->when(request()->has('site_id'), function ($query) {
            $query->where('site_id', request()->site_id);
        })->with('site')->latest()->get();

        return view('report::archive.index', [
            'title' => __('dashboard.archive_files'),
            'files' => $files,
            'model_type' => $modalType,
        ]);
    }

    public function download($file): RedirectResponse
    {
        $file = ArchiveFile::findOrFail((int)$file);

        if (Storage::disk('public')->exists($file->url)) {
            return redirect()->back()->with([
                'message' => __('dashboard.files_prepared_successfully'),
                'file' => Storage::disk('public')->url($file->url)
            ]);
        }

        return redirect()->back()->with([
            'message' => __('dashboard.file_not_found'),
            'file' => null
        ]);
    }

    public function destroy($id): JsonResponse
    {
        ArchiveFile::where('id', $id)->delete();

        return response()->json([
            'message' => trans('dashboard.file_delete_successfully')
        ]);

    }

    public function downloadFile($file_id)
    {
        $file = ArchiveFile::find($file_id);
        if (\Storage::disk('public')->exists($file->url)) {

            $file_path = \Storage::disk('public')->url($file->url);
            $headers = ['Content-Type' => 'application/pdf'];

            return response()->download($file_path, $file->name, $headers);

        }

        return response()->json(['message' => __('dashboard.file_will_prepare_soon')], 400);
    }
}
