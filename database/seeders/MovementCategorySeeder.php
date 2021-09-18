<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Organize\MovementCategory\Repositories\Contracts\MovementCategoryRepositoryInterface;

class MovementCategorySeeder extends Seeder
{
    /**
     * @var $movementCategoryRepository
     */
    private $movementCategoryRepository;

    /**
     * @param MovementCategoryRepositoryInterface $movementCategoryRepository
     *
     * @return void
     */
    public function __construct(MovementCategoryRepositoryInterface $movementCategoryRepository)
	{
        $this->movementCategoryRepository = $movementCategoryRepository;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $movementCategories = [
            1  => trans('movement_categories.home'),
            2  => trans('movement_categories.education'),
            3  => trans('movement_categories.electronics'),
            4  => trans('movement_categories.leisure'),
            5  => trans('movement_categories.others'),
            6  => trans('movement_categories.restaurant'),
            7  => trans('movement_categories.health'),
            8  => trans('movement_categories.services'),
            9  => trans('movement_categories.supermarket'),
            10 => trans('movement_categories.transport'),
            11 => trans('movement_categories.clothing'),
            12 => trans('movement_categories.travel'),
        ];

        foreach ($movementCategories as $id => $movementCategory) {
            if ($this->movementCategoryRepository->find($id)) {
                continue;
            }

            $this->movementCategoryRepository->create(['id' => $id, 'name' => $movementCategory]);
        }
    }
}
