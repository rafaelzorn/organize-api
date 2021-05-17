<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Organize\Auth\Requests\LoginRequest;
use App\Organize\Auth\Services\AuthService;

class AuthController extends Controller
{
    /**
     * @var $authService
     */
    private $authService;

    /**
     * @param AuthService $authService
     *
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $this->validate($request, LoginRequest::rules());

        $login = $this->authService->login($request->only(['email', 'password']));

        return $this->responseAdapter($login);
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $me = $this->authService->me();

        return $this->responseAdapter($me);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $logout = $this->authService->logout();

        return $this->responseAdapter($logout);
    }

    /**
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $token = $this->authService->refresh();

        return $this->responseAdapter($token);
    }
}
