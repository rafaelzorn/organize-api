<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Organize\User\Repositories\Contracts\UserRepositoryInterface;

class UserSeeder extends Seeder
{
    /**
     * @var $userRepository
     */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $userRepository)
	{
        $this->userRepository = $userRepository;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $user = $this->userRepository->find(1);

        if (empty($user)) {
            $this->userRepository->create([
                'id'       => 1,
                'name'     => 'Usuário Teste',
                'email'    => 'usuario.teste@gmail.com.br',
                'password' => Hash::make(123456),
            ]);
        }
    }
}
