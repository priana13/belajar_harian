<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AbsensiKegiatan;
use App\Models\Belajar;
use App\Models\User;
use App\Models\Materi;
use App\Models\Kegiatan;
use App\Models\Kelompok;
use App\Models\JenisUser;
use App\Models\JenisKelompok;
use App\Models\KategoriKegiatan;
use App\Models\KategoriMateri;
use App\Models\MateriDetail;
use App\Models\MateriDetailUser;
use App\Models\Soal;
use App\Models\SoalUjian;
use App\Models\Ujian;
use App\Models\Angkatan;
use App\Models\JenisUjian;
use Illuminate\Database\Seeder;
use Faker\Provider\en_US\Text;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Provider\en_US\Person;
use Illuminate\Support\Facades\Http;


use function PHPUnit\Framework\isTrue;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    
        $this->call([

            GelombangSeeder::class,
            UserSeeder::class,
            MateriSeeder::class,
            JenisUjianSeeder::class,
            SoalSeeder::class,  
            AngkatanSeeder::class, 
            // KelasSeeder::class,         
            // UjianSeeder::class,
            // BelajarSeeder::class, 
            SertifikatSeeder::class,
            BannerSeeder::class,

        ]);   



    }

}
