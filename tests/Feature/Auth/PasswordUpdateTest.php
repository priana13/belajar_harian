<?php

namespace Tests\Feature\Auth;

use App\Models\JenisUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordUpdateTest extends TestCase
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

    public function test_password_can_be_updated(): void
    {
        $user = $this->createUser();

        $response = $this
            ->actingAs($user)
            ->from('/profile/user')
            ->put('/password', [
                'current_password' => 'password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile/user');

        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
    }

    public function test_correct_password_must_be_provided_to_update_password(): void
    {
        $user = $this->createUser();

        $response = $this
            ->actingAs($user)
            ->from('/profile/user')
            ->put('/password', [
                'current_password' => 'wrong-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('updatePassword', 'current_password')
            ->assertRedirect('/profile/user');
    }
}
