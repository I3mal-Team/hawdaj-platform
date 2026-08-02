<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

abstract class ApiModalController extends Controller
{
    public function success($data, $code = 200, $message = 'success'): JsonResponse
    {
        return $this->response($code, $message, $data);
    }

    public function error($code = 400, $message = 'error', $data = []): JsonResponse
    {
        return $this->response($code, $message, $data);
    }

    public function ok($mesage = 'Success'): JsonResponse
    {
        return $this->response(200, $mesage);
    }

    public function response($code, $message, $data = []): JsonResponse
    {
        return response()->json(array_merge(['code' => $code, 'message' => $message  , 'code' => $code],
            !empty($data) ? ['data' => $data] : []), $code);
    }

}
