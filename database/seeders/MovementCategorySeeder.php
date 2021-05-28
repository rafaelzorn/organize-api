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
        $movementCategory = $this->movementCategoryRepository->find(1);

        if (empty($movementCategory)) {
            $this->movementCategoryRepository->create(['id' => 1, 'name' => 'Casa']);
        }

        $movementCategory = $this->movementCategoryRepository->find(2);

        if (empty($movementCategory)) {
            $this->movementCategoryRepository->create(['id' => 2, 'name' => 'Educação']);
        }

        $movementCategory = $this->movementCategoryRepository->find(3);

        if (empty($movementCategory)) {
            $this->movementCategoryRepository->create(['id' => 3, 'name' => 'Eletrônicos']);
        }

        $movementCategory = $this->movementCategoryRepository->find(4);

        if (empty($movementCategory)) {
            $this->movementCategoryRepository->create(['id' => 4, 'name' => 'Lazer']);
        }

        $movementCategory = $this->movementCategoryRepository->find(5);

        if (empty($movementCategory)) {
            $this->movementCategoryRepository->create(['id' => 5, 'name' => 'Outros']);
        }

        $movementCategory = $this->movementCategoryRepository->find(6);

        if (empty($movementCategory)) {
            $this->movementCategoryRepository->create(['id' => 6, 'name' => 'Restaurante']);
        }

        $movementCategory = $this->movementCategoryRepository->find(7);

        if (empty($movementCategory)) {
            $this->movementCategoryRepository->create(['id' => 7, 'name' => 'Saúde']);
        }

        $movementCategory = $this->movementCategoryRepository->find(8);

        if (empty($movementCategory)) {
            $this->movementCategoryRepository->create(['id' => 8, 'name' => 'Serviços']);
        }

        $movementCategory = $this->movementCategoryRepository->find(9);

        if (empty($movementCategory)) {
            $this->movementCategoryRepository->create(['id' => 9, 'name' => 'Supermercado']);
        }

        $movementCategory = $this->movementCategoryRepository->find(10);

        if (empty($movementCategory)) {
            $this->movementCategoryRepository->create(['id' => 10, 'name' => 'Transporte']);
        }

        $movementCategory = $this->movementCategoryRepository->find(11);

        if (empty($movementCategory)) {
            $this->movementCategoryRepository->create(['id' => 11, 'name' => 'Vestuário']);
        }

        $movementCategory = $this->movementCategoryRepository->find(12);

        if (empty($movementCategory)) {
            $this->movementCategoryRepository->create(['id' => 12, 'name' => 'Viagem']);
        }
    }
}
