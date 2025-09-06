<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\JenisUser;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenisUser = [
            [
                "nama_jenis" => "Admin"
            ],
            [
                "nama_jenis" => "Member"
            ], 
        ];

        JenisUser::insert($jenisUser);
     
        // insert data user umum
        for ($i = 0; $i < 1; $i++) {
            User::create([
                'name' => fake()->firstName(),
                'email' => fake()->email(),
                'password' => fake()->password(),
                'no_hp' => fake()->numerify('8##########'),
                'jenis_user_id' => 2,
                'gelombang_id' => 1
            ]);
        }  
  

        User::create(
            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => '$2y$10$ytuak4BDuMqFSKe8PqrKSez9RSROiyUffRZEJ8cPIh5TbK6.Rlq5e', // bismillah
                'jenis_user_id' => 1,
                // 'kelompok_id' => 2
            ]
        );
    }
}
