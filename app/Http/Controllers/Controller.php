<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Constants\HttpStatusConstant;

class Controller extends BaseController
{
    /**
     * @param string $message
     * @param mixed $data
     * @param int $code
     *
     * @return JsonResponse
     */
    public function responseAdapter(
        string $message = '',
        mixed $data     = false,
        int $code       = HttpStatusConstant::OK): JsonResponse
    {
        $response['code'] = $code;

        if (Str::of($message)->isNotEmpty()) {
            $response['message'] = $message;
        }

        if ($data) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}
