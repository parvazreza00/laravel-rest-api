<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Parvaz',
                'email' => 'parvaz@gmail.com',
                'password' => '12345',
            ],
            [
                'name' => 'Rasel',
                'email' => 'Rasel@gmail.com',
                'password' => '12345',
            ],
            [
                'name' => 'Karim',
                'email' => 'Karim@gmail.com',
                'password' => '12345',
            ],
        ];
        User::insert($users);
    }
}
