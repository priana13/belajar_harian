<?php

namespace App\Http\Controllers\Admin2;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        $announcement = Setting::where('key', 'pengumuman')->first();

        return Inertia::render('Admin2/Settings', ['values' => [
            'pengumuman' => $announcement?->value ?? '',
            'pengumuman_aktif' => (bool) $announcement?->is_active,
            'logo_url' => Setting::getLogoUrl(),
        ]]);
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'pengumuman' => ['nullable', 'string', 'max:65535'],
            'pengumuman_aktif' => ['required', 'boolean'],
            'logo' => ['nullable', 'image', 'max:5120'],
            'remove_logo' => ['boolean'],
        ]);
        $path = $request->file('logo')?->store('settings', 'public');
        try {
            DB::transaction(function () use ($data, $path) {
                Setting::updateOrCreate(['key' => 'pengumuman'], ['value' => $data['pengumuman'], 'is_active' => $data['pengumuman_aktif']]);
                if ($path || ($data['remove_logo'] ?? false)) {
                    Setting::updateOrCreate(['key' => 'logo'], ['value' => $path]);
                }
            });
        } catch (\Throwable $e) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            throw $e;
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
