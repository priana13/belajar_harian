<?php

namespace Tests\Feature;

use App\Models\JenisUser;
use App\Models\User;
use Tests\TestCase;

class FrontNavigationTest extends TestCase
{
    public function test_admin_sees_account_and_admin_panel_in_feature_menu(): void
    {
        $user = new User(['id' => 1]);
        $user->setRelation('jenis_user', new JenisUser(['nama_jenis' => 'Admin']));
        $this->actingAs($user);

        $html = view('components.front-bottom-nav')->render();
        $this->assertStringContainsString('Menu fitur', $html);
        $this->assertStringContainsString('Akun', $html);
        $this->assertStringContainsString('Admin Panel', $html);
        $this->assertStringContainsString(route('filament.admin.pages.dashboard'), $html);
    }

    public function test_participant_sees_account_without_admin_panel(): void
    {
        $user = new User(['id' => 2]);
        $user->setRelation('jenis_user', new JenisUser(['nama_jenis' => 'Peserta']));
        $this->actingAs($user);

        $html = view('components.front-bottom-nav')->render();
        $this->assertStringContainsString('Akun', $html);
        $this->assertStringNotContainsString('Admin Panel', $html);
    }

    public function test_guest_does_not_see_admin_panel(): void
    {
        $this->assertStringNotContainsString('Admin Panel', view('components.front-bottom-nav')->render());
    }
}
