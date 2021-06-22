<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Organize\UserMovement\Requests\UserMovementIndexRequest;
use App\Organize\UserMovement\Requests\UserMovementStoreRequest;
use App\Organize\UserMovement\Requests\UserMovementUpdateRequest;
use App\Organize\UserMovement\Services\Contracts\UserMovementServiceInterface;

class MovementController extends Controller
{
    /**
     * @var $userMovementService
     */
    private $userMovementService;

    /**
     * @param UserMovementServiceInterface $userMovementService
     *
     * @return void
     */
    public function __construct(UserMovementServiceInterface $userMovementService)
    {
        $this->userMovementService = $userMovementService;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->validate($request, UserMovementIndexRequest::rules());

        $request = $this->userMovementService->getAllUserMovements($request->all());

        return $this->responseAdapter($request);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate(
            $request,
            UserMovementStoreRequest::rules(),
            UserMovementStoreRequest::messages()
        );

        $request = $this->userMovementService->store($request->all());

        return $this->responseAdapter($request);
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $request = $this->userMovementService->getUserMovement($id);

        return $this->responseAdapter($request);
    }

    /**
     * @param int $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $this->validate(
            $request,
            UserMovementUpdateRequest::rules(),
            UserMovementUpdateRequest::messages()
        );

        $request = $this->userMovementService->updateUserMovement($id, $request->all());

        return $this->responseAdapter($request);
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $request = $this->userMovementService->deleteUserMovement($id);

        return $this->responseAdapter($request);
    }
}
