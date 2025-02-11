<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 3; $i++) { 

           Banner::create([
            'title' => fake()->text(100),
            'image'=>fake()->imageUrl(),
            'url' => fake()->url(),
            'status' => true
           ]);

        }
    }
}
