<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikatUser extends Model
{
    use HasFactory;

    protected $table = 'sertifikat_user';

    protected $guarded = [];

    public function user(){

        return $this->belongsTo(User::class);
    }

    public function sertifikat(){

        return $this->belongsTo(Sertifikat::class);
    }

    public function materi(){

        return $this->belongsTo(Materi::class);
    }

    /**
     * Hapus sertifikat yang duplicate berdasarkan user_id dan materi_id
     * Menyimpan satu record dan menghapus sisanya
     */
    public static function removeDuplicates(): int
    {
        $duplicates = self::select('user_id', 'materi_id')
            ->groupBy('user_id', 'materi_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();
      
        $deletedCount = 0;

        foreach ($duplicates as $duplicate) {
            // Ambil semua record dengan kombinasi user_id, materi_id yang sama
            $records = self::where('user_id', $duplicate->user_id)
                ->where('materi_id', $duplicate->materi_id)
                ->orderBy('id')
                ->get();

            // Simpan yang pertama, hapus sisanya
            if ($records->count() > 1) {
                $firstId = $records->first()->id;
                $idsToDelete = $records->skip(1)->pluck('id')->toArray();
                $deletedCount += self::whereIn('id', $idsToDelete)->delete();
            }
        }

        return $deletedCount;
    }
}
