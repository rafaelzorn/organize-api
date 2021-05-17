<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Constants\HttpStatusConstant;

class Controller extends BaseController
{
    /**
     * @param mixed $data
     *
     * @return JsonResponse
     */
    public function responseAdapter($data)
    {
        $response         = [];
        $response['code'] = $data['code'];

        if (isset($data['message'])) {
            $response['message'] = $data['message'];
        }

        if (isset($data['data'])) {
            $response['data'] = $data['data'];
        }

        return response()->json($response, $response['code']);
    }
}
