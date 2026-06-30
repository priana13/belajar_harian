<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Angkatan;
use App\Models\AngkatanUser;
use App\Models\Gelombang;
use App\Models\Group;
use App\Models\JenisUser;
use App\Models\Kelas;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Http\Livewire\Homepage\HomeNew;

class HomeNewTest extends TestCase
{
    use RefreshDatabase;

    private function createGelombang(): Gelombang
    {
        return Gelombang::create([
            'gel' => 'Gelombang 1',
            'tanggal_mulai' => now(),
        ]);
    }

    private function createJenisUser(): JenisUser
    {
        return JenisUser::firstOrCreate(
            ['id' => 2],
            ['nama_jenis' => 'Peserta']
        );
    }

    private function createUser(?Gelombang $gelombang = null): User
    {
        $gelombang = $gelombang ?? $this->createGelombang();
        $jenisUser = $this->createJenisUser();

        return User::factory()->create([
            'gelombang_id' => $gelombang->id,
            'jenis_user_id' => $jenisUser->id,
        ]);
    }

    private function createSetting(string $key, ?string $value = null, bool $isActive = true): Setting
    {
        return Setting::create([
            'key' => $key,
            'value' => $value,
            'is_active' => $isActive,
        ]);
    }

    // =========================================================
    // Halaman Home - Guest (Belum Login)
    // =========================================================

    public function test_halaman_home_bisa_diakses_guest()
    {
        $this->createSetting('pengumuman', null, false);

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_guest_melihat_tombol_login_dan_daftar()
    {
        $this->createSetting('pengumuman', null, false);

        $response = $this->get('/');

        $response->assertSee('Masuk');
        $response->assertSee('Daftar');
    }

    public function test_guest_melihat_banner()
    {
        $this->createSetting('pengumuman', null, false);

        $response = $this->get('/');

        $response->assertSeeLivewire('homepage.home-page-banner');
    }

    // =========================================================
    // Halaman Home - Authenticated User
    // =========================================================

    public function test_halaman_home_bisa_diakses_user_login()
    {
        $this->createSetting('pengumuman', null, false);
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }

    public function test_user_login_melihat_salam()
    {
        $this->createSetting('pengumuman', null, false);
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/');

        $response->assertSee('Ahlan wa Sahlan');
        $response->assertSee('Semoga hari ini mendapatkan tambahan ilmu yang bermanfaat');
    }

    public function test_user_login_tidak_melihat_tombol_login()
    {
        $this->createSetting('pengumuman', null, false);
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/');

        $response->assertDontSee('wire:click.prevent="login"', false);
    }

    // =========================================================
    // Pengumuman
    // =========================================================

    public function test_pengumuman_aktif_ditampilkan()
    {
        $this->createSetting('pengumuman', 'Ini adalah pengumuman penting', true);
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/');

        $response->assertSee('Pengumuman!');
        $response->assertSee('Ini adalah pengumuman penting');
    }

    public function test_pengumuman_tidak_aktif_tidak_ditampilkan()
    {
        $this->createSetting('pengumuman', 'Pengumuman tersembunyi', false);
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/');

        $response->assertDontSee('Pengumuman!');
    }

    // =========================================================
    // Livewire Component Actions
    // =========================================================

    public function test_action_login_redirect_ke_halaman_login()
    {
        $this->createSetting('pengumuman', null, false);

        Livewire::test(HomeNew::class)
            ->call('login')
            ->assertRedirect(route('login'));
    }

    public function test_action_register_redirect_ke_halaman_register()
    {
        $this->createSetting('pengumuman', null, false);

        Livewire::test(HomeNew::class)
            ->call('register')
            ->assertRedirect(route('register'));
    }

    // =========================================================
    // Pendaftaran (Mendaftar)
    // =========================================================

    public function test_user_bisa_mendaftar_angkatan()
    {
        $this->createSetting('pengumuman', null, false);

        $gelombang = $this->createGelombang();
        $user = $this->createUser($gelombang);

        $angkatan = Angkatan::create([
            'kode_angkatan' => 'ANG001',
            'kode_daftar' => 'daftar-001',
            'materi_id' => $this->createMateri()->id,
            'tanggal_mulai' => now(),
            'tanggal_akhir' => now()->addMonths(3),
            'tanggal_ujian' => now()->addMonths(2),
            'status' => 'Pendaftaran',
            'is_umum' => true,
            'gelombang_id' => $gelombang->id,
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'Kelas A',
            'angkatan_id' => $angkatan->id,
        ]);

        $this->actingAs($user);

        Livewire::test(HomeNew::class)
            ->call('mendaftar', $angkatan->id);

        $this->assertDatabaseHas('angkatan_users', [
            'user_id' => $user->id,
            'angkatan_id' => $angkatan->id,
            'kelas_id' => $kelas->id,
        ]);
    }

    // =========================================================
    // Angkatan - Scope Pendaftaran
    // =========================================================

    public function test_hanya_angkatan_pendaftaran_dan_umum_ditampilkan()
    {
        $this->createSetting('pengumuman', null, false);
        $materi = $this->createMateri();
        $gelombang = $this->createGelombang();

        Angkatan::create([
            'kode_angkatan' => 'ANG-AKTIF',
            'kode_daftar' => 'daftar-aktif',
            'materi_id' => $materi->id,
            'tanggal_mulai' => now(),
            'tanggal_akhir' => now()->addMonths(3),
            'tanggal_ujian' => now()->addMonths(2),
            'status' => 'Pendaftaran',
            'is_umum' => true,
            'gelombang_id' => $gelombang->id,
        ]);

        Angkatan::create([
            'kode_angkatan' => 'ANG-SELESAI',
            'kode_daftar' => 'daftar-selesai',
            'materi_id' => $materi->id,
            'tanggal_mulai' => now(),
            'tanggal_akhir' => now()->addMonths(3),
            'tanggal_ujian' => now()->addMonths(2),
            'status' => 'Selesai',
            'is_umum' => true,
            'gelombang_id' => $gelombang->id,
        ]);

        Angkatan::create([
            'kode_angkatan' => 'ANG-PRIVATE',
            'kode_daftar' => 'daftar-private',
            'materi_id' => $materi->id,
            'tanggal_mulai' => now(),
            'tanggal_akhir' => now()->addMonths(3),
            'tanggal_ujian' => now()->addMonths(2),
            'status' => 'Pendaftaran',
            'is_umum' => false,
            'gelombang_id' => $gelombang->id,
        ]);

        $result = Angkatan::pendaftaran()->where('is_umum', true)->get();

        $this->assertCount(1, $result);
        $this->assertEquals('ANG-AKTIF', $result->first()->kode_angkatan);
    }

    // =========================================================
    // Tidak ada jadwal (belum ada materi hari ini)
    // =========================================================

    public function test_tampilan_saat_tidak_ada_jadwal_hari_ini()
    {
        $this->createSetting('pengumuman', null, false);
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Materi Hari ini');
    }

    // =========================================================
    // Setting Model
    // =========================================================

    public function test_setting_get_value_membuat_record_jika_belum_ada()
    {
        $setting = Setting::getValue('test_key');

        $this->assertNotNull($setting);
        $this->assertEquals('test_key', $setting->key);
        $this->assertNull($setting->value);
    }

    public function test_setting_get_value_mengembalikan_record_yang_ada()
    {
        Setting::create([
            'key' => 'existing_key',
            'value' => 'existing_value',
            'is_active' => true,
        ]);

        $setting = Setting::getValue('existing_key');

        $this->assertEquals('existing_value', $setting->value);
    }

    // =========================================================
    // Helper
    // =========================================================

    private function createMateri()
    {
        $kategori = \App\Models\KategoriMateri::create([
            'nama_kategori' => 'Kategori Test',
        ]);

        return \App\Models\Materi::create([
            'nama_materi' => 'Materi Test',
            'kategori_id' => $kategori->id,
        ]);
    }
}
