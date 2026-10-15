<?php

namespace Tests\Feature;

use App\Filament\Resources\PremiumBookClaimResource\Pages\ManagePremiumBookClaims;
use App\Filament\Resources\PremiumBookResource\Pages\ManagePremiumBooks;
use App\Filament\Resources\PremiumEbookResource\Pages\ManagePremiumEbooks;
use App\Filament\Resources\PremiumPlanResource;
use App\Filament\Resources\PremiumPlanResource\Pages\ManagePremiumPlans;
use App\Filament\Resources\PremiumSubscriptionResource\Pages\ManagePremiumSubscriptions;
use App\Http\Livewire\MateriVideo\HalamanMateriVideo;
use App\Models\JenisUser;
use App\Models\Materi;
use App\Models\PremiumBook;
use App\Models\PremiumEbook;
use App\Models\PremiumPlan;
use App\Models\PremiumSubscription;
use App\Models\User;
use App\Services\PremiumMembership;
use Filament\Facades\Filament;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class PremiumMembershipTest extends TestCase
{
    private User $member;

    private User $admin;

    private PremiumMembership $membership;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        config(['database.default' => 'premium_test', 'database.connections.premium_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true,
        ]]);
        Schema::create('jenis_user', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis');
            $table->timestamps();
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->foreignId('jenis_user_id')->constrained('jenis_user');
            $table->timestamps();
        });
        Schema::create('materi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_materi');
            $table->string('kode_materi')->nullable();
            $table->timestamps();
        });
        Schema::create('setting', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
        });
        $migration = require database_path('migrations/2026_10_14_100000_create_premium_membership_tables.php');
        $migration->up();
        $memberType = JenisUser::create(['nama_jenis' => 'Member']);
        $adminType = JenisUser::create(['nama_jenis' => 'Admin']);
        $this->member = User::create(['name' => 'Member', 'email' => 'member@example.test', 'jenis_user_id' => $memberType->id]);
        $this->admin = User::create(['name' => 'Admin', 'email' => 'admin@example.test', 'jenis_user_id' => $adminType->id]);
        $this->membership = app(PremiumMembership::class);
    }

    protected function tearDown(): void
    {
        $this->travelBack();
        DB::purge('premium_test');
        parent::tearDown();
    }

    private function plan(int $months = 1): PremiumPlan
    {
        return PremiumPlan::create(['name' => 'Paket '.$months.' bulan', 'price' => 50000, 'duration_months' => $months, 'payment_instructions' => 'Transfer ke rekening admin', 'is_active' => true]);
    }

    private function activate(int $months = 1): PremiumSubscription
    {
        $order = $this->membership->order($this->member, $this->plan($months)->id);
        $this->membership->submitPayment($this->member, $order->id, 'Bank A / Member / Ref 123');
        $this->membership->approve($order, $this->admin);

        return $order->fresh();
    }

    private function address(): array
    {
        return ['recipient_name' => 'Penerima', 'phone' => '081234567890', 'address' => 'Jalan Belajar nomor 10, RT 01', 'city' => 'Bandung', 'province' => 'Jawa Barat', 'postal_code' => '40111'];
    }

    private function book(): PremiumBook
    {
        $material = Materi::create(['nama_materi' => 'Materi '.uniqid()]);

        return PremiumBook::create(['materi_id' => $material->id, 'title' => 'Buku '.$material->id, 'is_available' => true]);
    }

    public function test_orders_snapshot_prices_and_duration_without_granting_access(): void
    {
        $plan = $this->plan(3);
        $order = $this->membership->order($this->member, $plan->id);
        $plan->update(['price' => 999000, 'duration_months' => 6]);
        $this->assertSame(50000, (int) $order->fresh()->price);
        $this->assertSame(3, (int) $order->duration_months);
        $this->assertFalse($this->member->hasActivePremium());
        $this->assertNull($order->ends_at);
        $this->expectException(ValidationException::class);
        $this->membership->order($this->member, $plan->id);
    }

    public function test_activation_requires_payment_reference(): void
    {
        $order = $this->membership->order($this->member, $this->plan()->id);
        $this->expectException(ValidationException::class);
        $this->membership->approve($order, $this->admin);
    }

    public function test_approval_is_idempotent_and_renewal_extends_existing_expiry(): void
    {
        $this->travelTo(now()->setDate(2026, 1, 31)->startOfDay());
        $order = $this->activate();
        $this->assertSame('2026-02-28', $order->ends_at->toDateString());
        $this->membership->approve($order, $this->admin);
        $this->assertEquals($order->ends_at, $order->fresh()->ends_at);
        $this->assertSame('Member Premium', $this->member->fresh()->jenis_user->nama_jenis);
        $renewal = $this->activate(3);
        $this->assertEquals($order->ends_at, $renewal->starts_at);
        $this->assertSame('2026-05-28', $renewal->ends_at->toDateString());
        $this->travelTo($renewal->ends_at);
        $this->assertFalse($this->member->hasActivePremium());
    }

    public function test_premium_pages_and_private_download_require_active_membership(): void
    {
        $this->actingAs($this->member)->get(route('premium.videos'))->assertRedirect(route('premium.index'));
        $this->get(route('premium.ebooks'))->assertRedirect(route('premium.index'));
        Storage::fake('local');
        Storage::disk('local')->put('premium/ebooks/example.pdf', '%PDF-1.4 example');
        $material = Materi::create(['nama_materi' => 'Materi Ebook']);
        $ebook = PremiumEbook::create(['materi_id' => $material->id, 'title' => 'Buku Tes', 'file_path' => 'premium/ebooks/example.pdf', 'is_active' => true]);
        $this->get(route('premium.download', $ebook))->assertRedirect(route('premium.index'));
        $order = $this->activate();
        $this->get(route('premium.ebooks'))->assertOk()->assertSee('Buku Tes');
        $this->get(route('premium.download', $ebook))->assertDownload('buku-tes.pdf');
        $this->travelTo($order->ends_at);
        $this->get(route('premium.download', $ebook))->assertRedirect(route('premium.index'));
        $this->actingAs($this->admin)->get(route('premium.download', $ebook))->assertDownload();
        $ebook->update(['is_active' => false]);
        $this->get(route('premium.download', $ebook))->assertNotFound();
    }

    public function test_several_books_are_claimed_once_in_a_single_shipment(): void
    {
        $this->activate();
        $a = $this->book();
        $b = $this->book();
        $claim = $this->membership->claimBooks($this->member, [$a->id, $b->id], $this->address());
        $this->assertCount(2, $claim->items);
        $this->assertDatabaseCount('premium_book_claims', 1);
        $this->assertSame('requested', $claim->status);
        $this->membership->quoteShipping($claim, $this->admin, 25000, 'Transfer ongkir ke rekening admin');
        $this->membership->submitShippingPayment($this->member, $claim->id, 'Ref ongkir 123');
        $this->membership->ship($claim, $this->admin, 'Kurir', 'RESI123');
        $this->assertSame('shipped', $claim->fresh()->status);
        $this->assertSame('RESI123', $claim->fresh()->tracking_number);
        $this->expectException(ValidationException::class);
        $this->membership->claimBooks($this->member, [$a->id], $this->address());
    }

    public function test_claim_form_requires_address_and_shipping_consent(): void
    {
        $this->activate();
        $book = $this->book();
        $this->actingAs($this->member)->post(route('premium.claim'), ['books' => [$book->id]])
            ->assertSessionHasErrors(['recipient_name', 'phone', 'address', 'city', 'province', 'postal_code', 'accept_shipping']);
        $this->assertDatabaseCount('premium_book_claims', 0);
    }

    public function test_inactive_and_unavailable_books_cannot_be_claimed(): void
    {
        $this->activate();
        $book = $this->book();
        $book->update(['is_available' => false]);
        $this->expectException(ValidationException::class);
        $this->membership->claimBooks($this->member, [$book->id], $this->address());
    }

    public function test_members_cannot_approve_payments_or_administer_plans(): void
    {
        $this->actingAs($this->member);
        $this->assertFalse(PremiumPlanResource::canViewAny());
        $order = $this->membership->order($this->member, $this->plan()->id);
        $this->expectException(HttpException::class);
        $this->membership->approve($order, $this->member);
    }

    public function test_payment_confirmation_cannot_modify_another_users_order(): void
    {
        $order = $this->membership->order($this->member, $this->plan()->id);
        $this->actingAs($this->admin)->post(route('premium.payment', $order->id), ['payment_reference' => 'Wrong user ref'])->assertNotFound();
        $this->assertNull($order->fresh()->payment_reference);
    }

    public function test_dashboard_renders_packages_books_and_orders(): void
    {
        $this->plan();
        $this->actingAs($this->member)->get(route('premium.index'))->assertOk()->assertSee('Paket 1 bulan');
        $this->activate();
        $this->book();
        $this->get(route('premium.index'))->assertOk()->assertSee('Premium aktif hingga')->assertSee('Pilih buku');
    }

    public function test_admin_can_create_flexible_packages_from_the_admin_form(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs($this->admin);
        Livewire::test(ManagePremiumPlans::class)
            ->callAction('create', data: [
                'name' => 'Paket 6 bulan', 'duration_months' => 6, 'price' => 250000,
                'payment_instructions' => 'Transfer ke rekening admin', 'is_active' => true,
            ])->assertHasNoActionErrors();
        $this->assertDatabaseHas('premium_plans', ['name' => 'Paket 6 bulan', 'duration_months' => 6, 'price' => 250000]);
    }

    public function test_expired_membership_is_rechecked_on_video_interactions(): void
    {
        $order = $this->activate();
        $this->actingAs($this->member);
        $this->travelTo($order->ends_at);
        $this->expectException(HttpException::class);
        (new HalamanMateriVideo)->selectVideo(1);
    }

    public function test_admin_verifies_subscription_through_table_action(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $order = $this->membership->order($this->member, $this->plan()->id);
        $this->membership->submitPayment($this->member, $order->id, 'Ref transfer 123');
        $this->actingAs($this->admin);
        Livewire::test(ManagePremiumSubscriptions::class)
            ->callTableAction('approve', $order->fresh())->assertHasNoTableActionErrors();
        $this->assertSame('paid', $order->fresh()->status);
    }

    public function test_admin_creates_books_and_processes_combined_shipping(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->activate();
        $material = Materi::create(['nama_materi' => 'Materi Buku']);
        $this->actingAs($this->admin);
        Livewire::test(ManagePremiumBooks::class)
            ->callAction('create', data: ['materi_id' => $material->id, 'title' => 'Buku Gratis', 'is_available' => true])
            ->assertHasNoActionErrors();
        $book = PremiumBook::firstOrFail();
        $claim = $this->membership->claimBooks($this->member, [$book->id], $this->address());
        Livewire::test(ManagePremiumBookClaims::class)
            ->callTableAction('quote', $claim, data: ['fee' => 30000, 'instructions' => 'Transfer ke rekening pengiriman'])
            ->assertHasNoTableActionErrors();
        $this->assertSame('awaiting_payment', $claim->fresh()->status);
        $this->actingAs($this->member)->get(route('premium.index'))->assertOk()->assertSee('Transfer ke rekening pengiriman');
        $this->membership->submitShippingPayment($this->member, $claim->id, 'Transfer ongkir 123');
        $this->actingAs($this->admin);
        Livewire::test(ManagePremiumBookClaims::class)
            ->callTableAction('ship', $claim->fresh(), data: ['courier' => 'Kurir', 'tracking' => 'RESI456'])
            ->assertHasNoTableActionErrors();
        $this->assertSame('shipped', $claim->fresh()->status);
    }

    public function test_admin_ebook_form_renders(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs($this->admin);
        Livewire::test(ManagePremiumEbooks::class)
            ->mountAction('create')->assertActionMounted('create');
    }

    public function test_guest_must_login_to_visit_premium_pages(): void
    {
        $this->get(route('premium.index'))->assertRedirect(route('login'));
        $this->get(route('premium.ebooks'))->assertRedirect(route('login'));
    }
}
