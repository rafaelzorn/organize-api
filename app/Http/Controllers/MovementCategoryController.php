<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Organize\MovementCategory\Services\Contracts\MovementCategoryServiceInterface;

class MovementCategoryController extends Controller
{
    /**
     * @var $movementCategoryService
     */
    private $movementCategoryService;

    /**
     * @param MovementCategoryServiceInterface $movementCategoryService
     *
     * @return void
     */
    public function __construct(MovementCategoryServiceInterface $movementCategoryService)
    {
        $this->movementCategoryService = $movementCategoryService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = $this->movementCategoryService->index();

        return $this->responseAdapter($data);
    }
}
