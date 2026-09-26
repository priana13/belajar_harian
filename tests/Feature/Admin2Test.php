<?php

namespace Tests\Feature;

use App\Models\{Gelombang, JenisUser, User, Roadmap, KategoriMateri};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
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
        $this->get('/admin2')->assertRedirect(route('login'));
        $this->post('/admin2/roadmap', [])->assertRedirect(route('login'));
        $this->actingAs($this->user('Peserta'));
        foreach (['/admin2', '/admin2/peserta', '/admin2/konfigurasi', '/admin2/peserta/export', '/admin2/peserta/options/jenis_user_id'] as $url) $this->get($url)->assertForbidden();
        $this->post('/admin2/roadmap', [])->assertForbidden();
        $this->delete('/admin2/peserta/1')->assertForbidden();
    }

    public function test_all_resource_lists_and_forms_render_without_livewire(): void
    {
        $this->actingAs($this->user())->withoutVite();
        $this->get('/admin2')->assertOk()->assertInertia(fn (Assert $p) => $p->component('Admin2/Dashboard')->has('stats', 4))->assertDontSee('livewire.js');
        foreach (config('admin2') as $key => $definition) {
            $url = \App\Admin\Resources::url($key);
            $this->get($url)->assertOk()->assertInertia(fn (Assert $p) => $p->component('Admin2/Records')->where('resourceKey', $key));
            $this->get($url.'/create')->assertOk()->assertInertia(fn (Assert $p) => $p->component('Admin2/Edit'));
            foreach ($definition['fields'] as $field) {
                if ($field['type'] === 'reference') $this->get($url.'/options/'.$field['name'])->assertOk();
            }
        }
        $this->get('/admin2/konfigurasi')->assertOk()->assertInertia(fn (Assert $p) => $p->component('Admin2/Settings'));
        $this->get('/admin')->assertOk();
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
        Storage::disk('public')->assertExists(\App\Models\Setting::where('key', 'logo')->value('value'));
        $this->post('/admin2/konfigurasi/banner', ['title' => 'Banner', 'status' => true, 'image' => UploadedFile::fake()->image('banner.png')])->assertSessionHasNoErrors();
        $banner = \App\Models\Banner::firstOrFail();
        $path = $banner->image;
        $this->post('/admin2/konfigurasi/banner/'.$banner->id, ['title' => 'Baru', 'status' => false])->assertSessionHasNoErrors();
        $this->assertEquals($path, $banner->fresh()->image);
        $this->post('/admin2/konfigurasi', ['pengumuman_aktif' => true, 'logo' => UploadedFile::fake()->create('bad.php', 1)])->assertSessionHasErrors('logo');
    }
}
