<?php

namespace App\Services;

use App\Models\JenisUser;
use App\Models\PremiumBook;
use App\Models\PremiumBookClaim;
use App\Models\PremiumBookClaimItem;
use App\Models\PremiumPlan;
use App\Models\PremiumSubscription;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PremiumMembership
{
    public function order(User $user, int $planId): PremiumSubscription
    {
        return DB::transaction(function () use ($user, $planId) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $plan = PremiumPlan::whereKey($planId)->where('is_active', true)->lockForUpdate()->firstOrFail();
            if ($plan->duration_months < 1 || $plan->price < 0 || blank($plan->payment_instructions)) {
                throw ValidationException::withMessages(['plan' => 'Paket belum siap dibeli. Hubungi admin.']);
            }
            // Keep one outstanding order; snapshot the price and duration for verification.
            $pending = $user->premiumSubscriptions()->where('status', 'pending')->first();
            if ($pending) {
                throw ValidationException::withMessages(['plan' => 'Selesaikan atau batalkan pesanan yang masih menunggu verifikasi.']);
            }

            return PremiumSubscription::create([
                'user_id' => $user->id, 'premium_plan_id' => $plan->id,
                'plan_name' => $plan->name, 'duration_months' => $plan->duration_months,
                'price' => $plan->price, 'payment_instructions' => $plan->payment_instructions,
                'status' => 'pending',
            ]);
        });
    }

    public function approve(PremiumSubscription $subscription, User $admin): void
    {
        abort_unless($admin->isAdmin(), 403);
        DB::transaction(function () use ($subscription, $admin) {
            $user = User::whereKey($subscription->user_id)->lockForUpdate()->firstOrFail();
            $order = PremiumSubscription::whereKey($subscription->id)->lockForUpdate()->firstOrFail();
            if ($order->status === 'paid') {
                return;
            }
            if ($order->status !== 'pending' || blank($order->payment_reference)) {
                throw ValidationException::withMessages(['payment_reference' => 'Pesanan harus menunggu verifikasi dan memiliki referensi transfer.']);
            }
            $latestEnd = $user->premiumSubscriptions()->where('status', 'paid')->max('ends_at');
            $start = $latestEnd && Carbon::parse($latestEnd)->isFuture() ? Carbon::parse($latestEnd) : now();
            $order->update([
                'status' => 'paid', 'starts_at' => $start, 'ends_at' => $start->copy()->addMonthsNoOverflow($order->duration_months),
                'approved_at' => now(), 'approved_by' => $admin->id,
            ]);
            if (! $user->isAdmin()) {
                $type = JenisUser::firstOrCreate(['nama_jenis' => 'Member Premium']);
                $user->update(['jenis_user_id' => $type->id]);
            }
        });
    }

    public function reject(PremiumSubscription $subscription, User $admin): void
    {
        abort_unless($admin->isAdmin(), 403);
        DB::transaction(function () use ($subscription) {
            User::whereKey($subscription->user_id)->lockForUpdate()->firstOrFail();
            $order = PremiumSubscription::whereKey($subscription->id)->lockForUpdate()->firstOrFail();
            abort_unless($order->status === 'pending', 422);
            $order->update(['status' => 'cancelled']);
        });
    }

    public function submitPayment(User $user, int $id, string $reference): void
    {
        DB::transaction(function () use ($user, $id, $reference) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $order = $user->premiumSubscriptions()->whereKey($id)->lockForUpdate()->firstOrFail();
            abort_unless($order->status === 'pending', 422);
            $order->update(['payment_reference' => $reference]);
        });
    }

    public function cancel(User $user, int $id): void
    {
        DB::transaction(function () use ($user, $id) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $order = $user->premiumSubscriptions()->whereKey($id)->lockForUpdate()->firstOrFail();
            abort_unless($order->status === 'pending' && blank($order->payment_reference), 422);
            $order->update(['status' => 'cancelled']);
        });
    }

    public function claimBooks(User $user, array $bookIds, array $address): PremiumBookClaim
    {
        return DB::transaction(function () use ($user, $bookIds, $address) {
            $user = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            abort_unless($user->hasActivePremium(), 403);
            $ids = array_values(array_unique(array_map('intval', $bookIds)));
            $books = PremiumBook::whereIn('id', $ids)->where('is_available', true)->lockForUpdate()->get();
            if (! count($ids) || $books->count() !== count($ids) || PremiumBookClaimItem::where('user_id', $user->id)->whereIn('premium_book_id', $ids)->exists()) {
                throw ValidationException::withMessages(['selectedBooks' => 'Pilih buku yang tersedia dan belum pernah Anda klaim.']);
            }
            $validatedAddress = validator($address, [
                'recipient_name' => 'required|string|max:255', 'phone' => 'required|string|max:30',
                'address' => 'required|string|min:10|max:2000', 'city' => 'required|string|max:255',
                'province' => 'required|string|max:255', 'postal_code' => 'required|digits:5',
            ])->validate();
            $claim = PremiumBookClaim::create($validatedAddress + ['user_id' => $user->id, 'status' => 'requested']);
            foreach ($books as $book) {
                $claim->items()->create(['user_id' => $user->id, 'premium_book_id' => $book->id, 'book_title' => $book->title]);
            }

            return $claim;
        });
    }

    public function quoteShipping(PremiumBookClaim $claim, User $admin, int $fee, string $instructions): void
    {
        abort_unless($admin->isAdmin(), 403);
        DB::transaction(function () use ($claim, $fee, $instructions) {
            $claim = PremiumBookClaim::whereKey($claim->id)->lockForUpdate()->firstOrFail();
            abort_unless($claim->status === 'requested' && $fee > 0 && filled($instructions), 422);
            $claim->update(['shipping_fee' => $fee, 'shipping_payment_instructions' => $instructions, 'status' => 'awaiting_payment']);
        });
    }

    public function submitShippingPayment(User $user, int $id, string $reference): void
    {
        DB::transaction(function () use ($user, $id, $reference) {
            $claim = PremiumBookClaim::where('user_id', $user->id)->whereKey($id)->lockForUpdate()->firstOrFail();
            abort_unless($claim->status === 'awaiting_payment' && $claim->shipping_fee > 0, 422);
            $claim->update(['shipping_payment_reference' => $reference, 'status' => 'payment_review']);
        });
    }

    public function ship(PremiumBookClaim $claim, User $admin, string $courier, string $tracking): void
    {
        abort_unless($admin->isAdmin(), 403);
        DB::transaction(function () use ($claim, $courier, $tracking) {
            $claim = PremiumBookClaim::whereKey($claim->id)->lockForUpdate()->firstOrFail();
            abort_unless($claim->status === 'payment_review' && filled($claim->shipping_payment_reference) && filled($courier) && filled($tracking), 422);
            $claim->update(['status' => 'shipped', 'courier' => $courier, 'tracking_number' => $tracking, 'shipped_at' => now()]);
        });
    }
}
