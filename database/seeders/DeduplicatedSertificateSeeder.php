<?php

namespace Database\Seeders;

use App\Models\SertifikatUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeduplicatedSertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deletedCount = SertifikatUser::removeDuplicates();
        $this->command->info("Berhasil menghapus {$deletedCount} sertifikat duplikat.");
    }
}
