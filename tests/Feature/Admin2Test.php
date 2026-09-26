<?php

namespace Tests\Feature;

use App\Admin\Resources;
use App\Models\Angkatan;
use App\Models\Banner;
use App\Models\Gelombang;
use App\Models\Group;
use App\Models\JadwalRoadmap;
use App\Models\JenisUser;
use App\Models\KategoriMateri;
use App\Models\Materi;
use App\Models\MateriDetail;
use App\Models\Roadmap;
use App\Models\Sertifikat;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Livewire\Livewire;
use Tests\TestCase;

class Admin2Test extends TestCase
{
    use RefreshDatabase;

    private function user(string $role = 'Admin'): User
    {
        $type = JenisUser::firstOrCreate(['nama_jenis' => $role]);
        $batch = Gelombang::firstOrCreate(['gel' => '2026'], ['tanggal_mulai' => '2026-09-01']);

        return User::factory()->create(['jenis_user_id' => $type->id, 'jenis_kelamin' => 'L', 'gelombang_id' => $batch->id]);
    }

    public function test_guests_and_participants_cannot_access_admin2(): void
    {
        $this->get('/admin2')->assertRedirect('/admin2/login');
        $this->post('/admin2/roadmap', [])->assertRedirect('/admin2/login');
        $this->actingAs($this->user('Peserta'));
        foreach (['/admin2', '/admin2/peserta', '/admin2/konfigurasi', '/admin2/peserta/export', '/admin2/peserta/options/jenis_user_id'] as $url) {
            $this->get($url)->assertForbidden();
        }
        $this->post('/admin2/roadmap', [])->assertForbidden();
        $this->delete('/admin2/peserta/1')->assertForbidden();
    }

    public function test_all_resource_lists_and_forms_render_without_livewire(): void
    {
        $this->actingAs($this->user())->withoutVite();
        $this->get('/admin2')->assertOk()->assertInertia(fn (Assert $p) => $p->component('Admin2/Dashboard')->has('stats', 4))->assertDontSee('livewire.js');
        foreach (config('admin2') as $key => $definition) {
            $url = Resources::url($key);
            $this->get($url)->assertOk()->assertInertia(fn (Assert $p) => $p->component('Admin2/Records')->where('resourceKey', $key));
            $this->get($url.'/create')->assertOk()->assertInertia(fn (Assert $p) => $p->component('Admin2/Edit'));
            foreach ($definition['fields'] as $field) {
                if ($field['type'] === 'reference') {
                    $this->get($url.'/options/'.$field['name'])->assertOk();
                }
            }
        }
        $this->get('/admin2/konfigurasi')->assertOk()->assertInertia(fn (Assert $p) => $p->component('Admin2/Settings'));
        $this->get('/admin')->assertOk();
        // The legacy panel renders Livewire; reset its static state between simulated requests.
        Livewire::flushState();
    }

    public function test_crud_validation_search_export_and_relations(): void
    {
        $this->actingAs($this->user())->withoutVite();
        $this->post('/admin2/roadmap', ['nama_roadmap' => '', 'relations' => ['materi' => [], 'gelombangs' => []]])->assertSessionHasErrors('nama_roadmap');
        $data = ['nama_roadmap' => 'Dasar', 'detail' => 'Materi dasar', 'relations' => ['materi' => [], 'gelombangs' => [Gelombang::first()->id]]];
        $this->post('/admin2/roadmap', $data)->assertRedirect('/admin2/roadmap');
        $roadmap = Roadmap::firstOrFail();
        $this->assertEquals(1, $roadmap->gelombangs()->count());
        $this->get('/admin2/roadmap/'.$roadmap->id.'/edit')->assertOk()->assertInertia(fn (Assert $p) => $p->where('record.nama_roadmap', 'Dasar')->has('relations.gelombangs', 1));
        $this->get('/admin2/roadmap?search=tidak-ada')->assertInertia(fn (Assert $p) => $p->where('records.total', 0));
        $data['nama_roadmap'] = '=SUM(A1)';
        $data['relations']['gelombangs'] = [];
        $this->post('/admin2/roadmap/'.$roadmap->id, $data)->assertSessionHasNoErrors();
        $this->assertEquals(0, $roadmap->gelombangs()->count());
        $csv = $this->get('/admin2/roadmap/export')->assertOk()->streamedContent();
        $this->assertStringContainsString("'=SUM(A1)", $csv);
        $this->delete('/admin2/roadmap/'.$roadmap->id)->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('roadmaps', ['id' => $roadmap->id]);
    }

    public function test_user_password_and_self_protection(): void
    {
        $admin = $this->user();
        $this->actingAs($admin)->withoutVite();
        $data = ['name' => 'Peserta Baru', 'email' => 'baru@example.test', 'password' => 'secret123', 'jenis_kelamin' => 'P', 'gelombang_id' => $admin->gelombang_id, 'jenis_user_id' => $admin->jenis_user_id];
        $this->post('/admin2/peserta', $data)->assertSessionHasNoErrors();
        $created = User::where('email', $data['email'])->firstOrFail();
        $this->assertTrue(Hash::check('secret123', $created->password));
        $this->get('/admin2/peserta')->assertInertia(fn (Assert $p) => $p->missing('records.data.0.password'));
        $data['password'] = '';
        $this->post('/admin2/peserta/'.$created->id, $data)->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('secret123', $created->fresh()->password));
        $this->delete('/admin2/peserta/'.$admin->id)->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_settings_and_uploads_are_persisted_and_validated(): void
    {
        Storage::fake('public');
        $this->actingAs($this->user());
        $this->post('/admin2/konfigurasi', ['pengumuman' => 'Selamat belajar', 'pengumuman_aktif' => true, 'logo' => UploadedFile::fake()->image('logo.png')])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('setting', ['key' => 'pengumuman', 'value' => 'Selamat belajar', 'is_active' => 1]);
        Storage::disk('public')->assertExists(Setting::where('key', 'logo')->value('value'));
        $this->post('/admin2/konfigurasi/banner', ['title' => 'Banner', 'status' => true, 'image' => UploadedFile::fake()->image('banner.png')])->assertSessionHasNoErrors();
        $banner = Banner::firstOrFail();
        $path = $banner->image;
        $this->post('/admin2/konfigurasi/banner/'.$banner->id, ['title' => 'Baru', 'status' => false])->assertSessionHasNoErrors();
        $this->assertEquals($path, $banner->fresh()->image);
        $this->post('/admin2/konfigurasi', ['pengumuman_aktif' => true, 'logo' => UploadedFile::fake()->create('bad.php', 1)])->assertSessionHasErrors('logo');
    }

    public function test_schedules_are_created_atomically_and_not_duplicated(): void
    {
        $admin = $this->user();
        $this->actingAs($admin)->withoutVite();
        $category = KategoriMateri::create(['nama_kategori' => 'Dasar']);
        $certificate = Sertifikat::create(['nama' => 'Template', 'bg' => 'test.png']);
        $material = Materi::create(['nama_materi' => 'Belajar', 'materi_per_pekan' => 2, 'kategori_id' => $category->id, 'sertifikat_id' => $certificate->id]);
        foreach ([1, 2, 3] as $number) {
            MateriDetail::create(['materi_id' => $material->id, 'pertemuan' => $number, 'judul' => 'Pertemuan '.$number]);
        }
        $data = ['kode_angkatan' => 'A1', 'materi_id' => $material->id, 'mulai_pendaftaran' => '2026-09-01', 'akhir_pendaftaran' => '2026-09-06', 'tanggal_mulai' => '2026-09-07', 'tanggal_akhir' => '2026-10-01', 'tanggal_ujian' => '2026-10-01', 'kuota' => 100, 'sertifikat_id' => $certificate->id, 'gelombang_id' => $admin->gelombang_id, 'status' => 'Persiapan', 'is_umum' => false];
        $this->post('/admin2/angkatan', $data)->assertRedirect('/admin2/angkatan')->assertSessionHasNoErrors();
        $batch = Angkatan::firstOrFail();
        $this->assertEquals(1, $batch->kelas()->count());
        $this->assertEquals(1, $batch->angkatan_user()->count());
        $this->assertEquals(3, $batch->jadwal_belajar()->count());
        $this->assertEquals(8, $batch->jadwal_ujian()->count());
        $this->assertEquals(['2026-09-07', '2026-09-10', '2026-09-14'], $batch->jadwal_belajar()->orderBy('tanggal')->pluck('tanggal')->map(fn ($date) => substr($date, 0, 10))->all());
        $roadmap = Roadmap::create(['nama_roadmap' => 'Roadmap']);
        $schedule = JadwalRoadmap::create(['roadmap_id' => $roadmap->id, 'gelombang_id' => $admin->gelombang_id, 'materi_id' => $material->id, 'judul' => 'Jadwal', 'tanggal_mulai' => '2026-09-07', 'tanggal_ujian' => '2026-10-01']);
        $this->post('/admin2/jadwal-roadmap/'.$schedule->id.'/generate')->assertSessionHasNoErrors();
        $this->assertEquals(3, $schedule->jadwal_belajar()->count());
        $this->post('/admin2/jadwal-roadmap/'.$schedule->id.'/generate')->assertSessionHasErrors('schedule');
        $this->assertEquals(3, $schedule->jadwal_belajar()->count());
        $material->update(['materi_per_pekan' => 0]);
        $data['kode_angkatan'] = 'INVALID';
        $this->post('/admin2/angkatan', $data)->assertSessionHasErrors('materi_id');
        $this->assertDatabaseMissing('angkatan', ['kode_angkatan' => 'INVALID']);
        $this->assertDatabaseCount('kelas', 1);
    }

    public function test_reports_and_invalid_relations(): void
    {
        $admin = $this->user();
        $participant = $this->user('Peserta');
        $this->actingAs($admin)->withoutVite();
        $this->get('/admin2/keaktifan-peserta')->assertOk()->assertInertia(fn (Assert $p) => $p->where('records.total', 1)->where('records.data.0.id', $participant->id));
        $this->get('/admin2/link-materi-harian')->assertOk()->assertInertia(fn (Assert $p) => $p->component('Admin2/Reports'));
        $this->get('/admin2/keaktifan-peserta?export=1')->assertOk()->assertDownload('keaktifan-peserta.csv');
        $this->post('/admin2/roadmap', ['nama_roadmap' => 'Invalid', 'relations' => ['materi' => [999999]]])->assertSessionHasErrors('relations.materi.0');
        $this->assertDatabaseMissing('roadmaps', ['nama_roadmap' => 'Invalid']);
        $this->get('/admin2/peserta/options/password')->assertNotFound();
        $this->get('/admin2/unknown')->assertNotFound();
        $this->post('/admin2/konfigurasi/jenis-user/'.$admin->jenis_user_id, ['nama_jenis' => 'Changed'])->assertSessionHasErrors('nama_jenis');
        $group = Group::create(['nama_group' => 'Kelompok', 'kode_group' => 'K1']);
        $data = ['group_id' => $group->id, 'user_id' => $participant->id];
        $this->post('/admin2/anggota-kelompok', $data)->assertSessionHasNoErrors();
        $this->post('/admin2/anggota-kelompok', $data)->assertSessionHasErrors('user_id');
    }

    public function test_inertia_login_rejects_non_admin_and_logs_admin_out(): void
    {
        $this->withoutVite();
        $admin = $this->user();
        $participant = $this->user('Peserta');
        $this->get('/admin2/login')->assertOk()->assertInertia(fn (Assert $p) => $p->component('Admin2/Login'))->assertDontSee('livewire.js');
        $this->post('/admin2/login', ['login' => $participant->email, 'password' => 'password'])->assertSessionHasErrors('login');
        $this->assertGuest();
        $this->post('/admin2/login', ['login' => $admin->email, 'password' => 'password'])->assertRedirect('/admin2');
        $this->assertAuthenticatedAs($admin);
        $this->post('/admin2/logout')->assertRedirect('/admin2/login');
        $this->assertGuest();
    }
}
