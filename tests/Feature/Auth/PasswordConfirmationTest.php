<?php

namespace Tests\Feature\Auth;

use App\Models\JenisUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(array $attributes = []): User
    {
        JenisUser::firstOrCreate(['id' => 2], ['nama_jenis' => 'Peserta']);

        return User::factory()->create(array_merge(
            ['jenis_user_id' => 2],
            $attributes
        ));
    }

    public function test_confirm_password_screen_can_be_rendered(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/confirm-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
