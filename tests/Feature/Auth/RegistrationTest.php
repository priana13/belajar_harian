<?php

namespace Tests\Feature\Auth;

use App\Models\Gelombang;
use App\Models\JenisUser;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function setupRegistrationData(): array
    {
        JenisUser::firstOrCreate(['id' => 2], ['nama_jenis' => 'Peserta']);

        $provinsi = Provinsi::create(['nama_provinsi' => 'Jawa Barat']);
        $kota = Kota::create([
            'nama_kota' => 'Bandung',
            'provinsi_id' => $provinsi->id,
        ]);

        Gelombang::create([
            'gel' => 'Gel 1',
            'tanggal_mulai' => now()->toDateString(),
        ]);

        return [
            'provinsi' => $provinsi,
            'kota' => $kota,
        ];
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/daftar');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $data = $this->setupRegistrationData();

        $response = $this->post('/register', [
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'no_hp' => '081234567890',
            'password' => 'password123',
            'umur' => 25,
            'jenis_kelamin' => 'Laki-laki',
            'provinsi' => $data['provinsi']->id,
            'kota' => $data['kota']->id,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    public function test_registration_requires_required_fields(): void
    {
        $this->setupRegistrationData();

        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['nama', 'email', 'no_hp', 'password', 'umur', 'jenis_kelamin', 'provinsi', 'kota']);
    }

    public function test_registration_requires_unique_email(): void
    {
        $data = $this->setupRegistrationData();

        User::factory()->create([
            'jenis_user_id' => 2,
            'email' => 'existing@example.com',
        ]);

        $response = $this->post('/register', [
            'nama' => 'Test User',
            'email' => 'existing@example.com',
            'no_hp' => '081234567890',
            'password' => 'password123',
            'umur' => 25,
            'jenis_kelamin' => 'Laki-laki',
            'provinsi' => $data['provinsi']->id,
            'kota' => $data['kota']->id,
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}
