<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    /**
     * @param mixed $data
     *
     * @return JsonResponse
     */
    public function responseAdapter(mixed $data)
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
