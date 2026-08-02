<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\UpdateMailTemplateRequest;
use App\Models\MailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MailTemplateController extends Controller
{


    // set permission
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-mailTemplate', ['only' => ['index']]);
        $this->middleware('permission:create-mailTemplate', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-mailTemplate', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-mailTemplate', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $title = __('dashboard.all_template');
            $templates = MailTemplate::select('id', 'title', 'subject', 'type')->get();
            return view('dashboard.mail_template.index', compact('title', 'templates'));
        } catch (\Exception $e) {
            return abort(500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = __('dashboard.mail_template_update');
        $template = MailTemplate::find($id);
        if ($template) {
            return view('dashboard.mail_template.update', compact('template', 'title'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMailTemplateRequest $request, $id)
    {
        $template = MailTemplate::find($id);
        $data = array();
        if ($template) {
            DB::beginTransaction();
            try {
                $data = $request->except('_method', '_token', 'id');
                MailTemplate::updateOrCreate(['id' => $id], $data);
                DB::commit();
                return redirect()->route('dashboard.mail_template.index')->with('success', __('dashboard.updateMailTemplate'));
        } catch (\Exception $e) {
                dd($e);
                DB::rollBack();
                return unKnownError();
            }
        } else {
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
