<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Organize\User\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::find(1);

        if (empty($user)) {
            User::create([
                'id'       => 1,
                'name'     => 'Usuário Teste',
                'email'    => 'usuario.teste@gmail.com.br',
                'password' => Hash::make(123456),
            ]);
        }
    }
}
