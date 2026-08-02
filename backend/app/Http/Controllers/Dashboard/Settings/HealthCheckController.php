<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\HealthCheckRequest;
use App\Models\HealthCheck;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class HealthCheckController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('dashboard.settings.questions.index', [
            'title' => trans('dashboard.questions'),
            'questions' => HealthCheck::all()
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('dashboard.settings.questions.create', ['title' => __('dashboard.create_question')]);
    }

    /**
     * @param HealthCheckRequest $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function store(HealthCheckRequest $request)
    {
        try {
            HealthCheck::create($request->only('question'));

            return redirect(route('dashboard.questions.index'))->with([
                'message' => trans('dashboard.question_added_successfully')
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param HealthCheck $question
     * @return Application|Factory|View
     */
    public function edit(HealthCheck $question)
    {
        return view('dashboard.settings.questions.edit', [
            'title' => __('dashboard.edit_question'),
            'question' => $question
        ]);
    }

    /**
     * @param HealthCheckRequest $request
     * @param HealthCheck $question
     * @return Application|RedirectResponse|Redirector
     */
    public function update(HealthCheckRequest $request, HealthCheck $question)
    {
        $question->update([
            'question' => $request->question,
        ]);

        return redirect(route('dashboard.questions.index'))->with([
            'message' => trans('dashboard.question_updated_successfully')
        ]);
    }

    /**
     * @param HealthCheck $question
     * @return JsonResponse
     */
    public function destroy(HealthCheck $question): JsonResponse
    {
        $question->delete();

        return response()->json([
            'message' => trans('dashboard.question_delete_successfully')
        ]);
    }
}
