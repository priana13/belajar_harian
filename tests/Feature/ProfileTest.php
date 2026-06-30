<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\JenisUser;
use App\Http\Livewire\User\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private function createJenisUser(): JenisUser
    {
        return JenisUser::firstOrCreate(
            ['id' => 2],
            ['nama_jenis' => 'Peserta']
        );
    }

    private function createUser(array $attributes = []): User
    {
        $jenisUser = $this->createJenisUser();

        return User::factory()->create(array_merge(
            ['jenis_user_id' => $jenisUser->id],
            $attributes
        ));
    }

    public function test_profile_page_is_displayed(): void
    {
        $user = $this->createUser();

        $response = $this
            ->actingAs($user)
            ->get('/profile/user');

        $response->assertOk();
        $response->assertSeeLivewire(Profile::class);
    }

    public function test_profile_page_requires_authentication(): void
    {
        $response = $this->get('/profile/user');

        $response->assertRedirect('/login');
    }

    public function test_profile_loads_user_data(): void
    {
        $user = $this->createUser([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'no_hp' => '081234567890',
            'kota' => 'Jakarta',
        ]);

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->assertSet('name', 'Test User')
            ->assertSet('email', 'test@example.com')
            ->assertSet('no_hp', '081234567890')
            ->assertSet('kota', 'Jakarta');
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = $this->createUser([
            'no_hp' => '081234567890',
        ]);

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('name', 'Updated Name')
            ->set('email', 'updated@example.com')
            ->set('no_hp', '089876543210')
            ->call('submit')
            ->assertHasNoErrors();

        $user->refresh();

        $this->assertSame('Updated Name', $user->name);
        $this->assertSame('updated@example.com', $user->email);
        $this->assertSame('089876543210', $user->no_hp);
    }

    public function test_name_is_required(): void
    {
        $user = $this->createUser([
            'no_hp' => '081234567890',
        ]);

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('name', '')
            ->set('no_hp', '081234567890')
            ->call('submit')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_email_is_required(): void
    {
        $user = $this->createUser([
            'no_hp' => '081234567890',
        ]);

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('email', '')
            ->set('no_hp', '081234567890')
            ->call('submit')
            ->assertHasErrors(['email' => 'required']);
    }

    public function test_email_must_be_valid(): void
    {
        $user = $this->createUser([
            'no_hp' => '081234567890',
        ]);

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('email', 'not-an-email')
            ->set('no_hp', '081234567890')
            ->call('submit')
            ->assertHasErrors(['email' => 'email']);
    }

    public function test_password_must_be_confirmed(): void
    {
        $user = $this->createUser([
            'no_hp' => '081234567890',
        ]);

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('password', 'newpassword123')
            ->set('password_confirmation', 'differentpassword')
            ->set('no_hp', '081234567890')
            ->call('submit')
            ->assertHasErrors(['password' => 'confirmed']);
    }

    public function test_password_can_be_updated(): void
    {
        $user = $this->createUser([
            'no_hp' => '081234567890',
        ]);

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('password', 'newpassword123')
            ->set('password_confirmation', 'newpassword123')
            ->set('no_hp', '081234567890')
            ->call('submit')
            ->assertHasNoErrors();

        $user->refresh();

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword123', $user->password));
    }
}
