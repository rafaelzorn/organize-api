<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Organize\Auth\Services\Contracts\AuthServiceInterface;
use App\Organize\Auth\Requests\LoginRequest;

class AuthController extends Controller
{
    /**
     * @var $authService
     */
    private $authService;

    /**
     * @param AuthServiceInterface $authService
     *
     * @return void
     */
    public function __construct(AuthServiceInterface $authService)
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
