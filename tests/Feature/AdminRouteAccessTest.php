<?php

namespace Tests\Feature;

use App\Models\JenisUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRouteAccessTest extends TestCase
{
    use RefreshDatabase;

    /** Route bermiddleware admin harus mengarahkan guest ke login, bukan error 500. */
    public function test_guest_diarahkan_ke_login(): void
    {
        $this->get('/email/pesan')->assertRedirect(route('login'));
        $this->get('/koreksi-nilai')->assertRedirect(route('login'));
    }

    /** Peserta yang sudah login tetap tidak boleh menyentuh route admin. */
    public function test_peserta_ditolak(): void
    {
        $peserta = JenisUser::create(['nama_jenis' => 'Peserta']);
        $user = User::factory()->create(['jenis_user_id' => $peserta->id]);

        $this->actingAs($user)->get('/email/pesan')->assertForbidden();
        $this->actingAs($user)->get('/koreksi-nilai')->assertForbidden();
    }
}
