<?php

namespace Tests\Feature\Auth;

use App\Mail\ResetPasswordMail;
use App\Models\JenisUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetTest extends TestCase
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

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Mail::fake();

        $user = $this->createUser();

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        Mail::assertSent(ResetPasswordMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        $response->assertSessionHas('status');
    }

    public function test_reset_password_link_not_sent_for_unknown_email(): void
    {
        Mail::fake();

        $this->createUser();

        $response = $this->post('/forgot-password', ['email' => 'unknown@example.com']);

        Mail::assertNotSent(ResetPasswordMail::class);

        $response->assertSessionHas('status');
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $response = $this->get('/reset_password/some-token');

        $response->assertStatus(200);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = $this->createUser();

        $token = hash('sha256', 'test-token');
        $user->remember_token = $token;
        $user->save();

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect('/login');

        $this->assertTrue(
            \Illuminate\Support\Facades\Hash::check('new-password', $user->fresh()->password)
        );
    }
}
