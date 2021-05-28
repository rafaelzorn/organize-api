<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Organize\UserMovement\Requests\UserMovementStoreRequest;

class MovementController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, UserMovementStoreRequest::rules());

        dd('STORE');
    }
}
