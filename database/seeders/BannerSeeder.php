<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $source = public_path('img/banner1.jpg'); // file sumber
        $destination = 'banner1.jpg'; // di storage/app/public/banner1.jpg

        if (file_exists($source)) {
            Storage::disk('public')->put(
                $destination,
                file_get_contents($source)
            );
        }


        for ($i=0; $i < 1; $i++) { 

            // 

           Banner::create([
            'title' => fake()->text(100),
            'image'=> $destination,
            'url' => fake()->url(),
            'status' => true
           ]);

        }
    }
}
