<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Http\Request;

class SertifikatController extends Controller
{
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'nama'         => ['required', 'string', 'max:255'],           
            'bg'           => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
 
            // TTD 1
            'ttd_nama'     => ['required', 'string', 'max:255'],
            'ttd_jabatan'  => ['required', 'string', 'max:255'],
            'ttd_image'    => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
 
            // TTD 2
            'ttd_nama2'    => ['nullable', 'string', 'max:255'],
            'ttd_jabatan2' => ['nullable', 'string', 'max:255'],
            'ttd_image2'   => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);
       
        // ── Upload background (opsional) ──────────────────────────────────
        $bgPath = null;
        if ($request->hasFile('bg')) {
            $bgPath = $request->file('bg')
                ->store('sertifikat/backgrounds', 'public');
        }
 
        // ── Upload gambar TTD 1 (opsional) ────────────────────────────────
        $ttdImagePath = null;
        if ($request->hasFile('ttd_image')) {
            $ttdImagePath = $request->file('ttd_image')
                ->store('sertifikat/ttd', 'public');
        }
 
        // ── Upload gambar TTD 2 (opsional) ────────────────────────────────
        $ttdImage2Path = null;
        if ($request->hasFile('ttd_image2')) {
            $ttdImage2Path = $request->file('ttd_image2')
                ->store('sertifikat/ttd', 'public');
        }
 
        // ── Simpan ke database ────────────────────────────────────────────
        $sertifikat = Sertifikat::create([
            'nama'         => $validated['nama'],          
            'bg'           => $bgPath,
 
            'ttd_nama'     => $validated['ttd_nama'],
            'ttd_jabatan'  => $validated['ttd_jabatan'],
            'ttd_image'    => $ttdImagePath,
 
            'ttd_nama2'    => $validated['ttd_nama2'] ?? null,
            'ttd_jabatan2' => $validated['ttd_jabatan2'] ?? null,
            'ttd_image2'   => $ttdImage2Path,
        ]);
 
        return redirect()
            ->back()
            ->with('success', "Sertifikat untuk \"{$sertifikat->nama}\" berhasil diupload.");
    }
}
