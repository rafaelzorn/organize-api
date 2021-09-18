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
            1  => 'Casa',
            2  => 'Educação',
            3  => 'Eletrônicos',
            4  => 'Lazer',
            5  => 'Outros',
            6  => 'Restaurante',
            7  => 'Saúde',
            8  => 'Serviços',
            9  => 'Supermercado',
            10 => 'Transporte',
            11 => 'Vestuário',
            12 => 'Viagem',
        ];

        foreach ($movementCategories as $id => $movementCategory) {
            if ($this->movementCategoryRepository->find($id)) {
                continue;
            }

            $this->movementCategoryRepository->create(['id' => $id, 'name' => $movementCategory]);
        }
    }
}
