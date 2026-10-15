<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\PremiumBook;
use App\Models\PremiumBookClaim;
use App\Models\PremiumBookClaimItem;
use App\Models\PremiumEbook;
use App\Models\PremiumPlan;
use App\Services\PremiumMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PremiumController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $claimedIds = PremiumBookClaimItem::where('user_id', $user->id)->pluck('premium_book_id');

        return view('premium.index', [
            'plans' => PremiumPlan::where('is_active', true)->orderBy('duration_months')->get(),
            'subscriptions' => $user->premiumSubscriptions()->latest()->get(),
            'activeUntil' => $user->premiumSubscriptions()->where('status', 'paid')->where('ends_at', '>', now())->max('ends_at'),
            'books' => PremiumBook::with('materi')->where('is_available', true)->whereNotIn('id', $claimedIds)->get(),
            'claims' => PremiumBookClaim::with('items')->where('user_id', $user->id)->latest()->get(),
        ]);
    }

    public function order(Request $request, PremiumMembership $membership)
    {
        $data = $request->validate(['plan_id' => 'required|integer']);
        $membership->order($request->user(), $data['plan_id']);

        return back()->with('success', 'Pesanan dibuat. Transfer sesuai instruksi lalu kirim konfirmasi pembayaran.');
    }

    public function payment(Request $request, int $subscription, PremiumMembership $membership)
    {
        $data = $request->validate(['payment_reference' => 'required|string|min:5|max:255']);
        $membership->submitPayment($request->user(), $subscription, $data['payment_reference']);

        return back()->with('success', 'Konfirmasi transfer diterima. Langganan aktif setelah admin memverifikasi pembayaran.');
    }

    public function cancel(Request $request, int $subscription, PremiumMembership $membership)
    {
        $membership->cancel($request->user(), $subscription);

        return back()->with('success', 'Pesanan dibatalkan.');
    }

    public function claim(Request $request, PremiumMembership $membership)
    {
        $data = $request->validate([
            'books' => 'required|array|min:1|max:50', 'books.*' => 'required|integer|distinct',
            'recipient_name' => 'required|string|max:255', 'phone' => 'required|string|max:30',
            'address' => 'required|string|min:10|max:2000', 'city' => 'required|string|max:255',
            'province' => 'required|string|max:255', 'postal_code' => 'required|digits:5',
            'accept_shipping' => 'accepted',
        ]);
        $membership->claimBooks($request->user(), $data['books'], $data);

        return back()->with('success', 'Klaim buku diterima. Admin akan menentukan ongkir untuk pengiriman Anda.');
    }

    public function shippingPayment(Request $request, int $claim, PremiumMembership $membership)
    {
        $data = $request->validate(['payment_reference' => 'required|string|min:5|max:255']);
        $membership->submitShippingPayment($request->user(), $claim, $data['payment_reference']);

        return back()->with('success', 'Konfirmasi pembayaran ongkir dikirim ke admin.');
    }

    public function videos()
    {
        return view('premium.videos', ['materials' => Materi::whereHas('materi_detail', fn ($query) => $query->whereNotNull('video_url')->where('video_url', '!=', ''))->orderBy('nama_materi')->paginate(12)]);
    }

    public function ebooks()
    {
        return view('premium.ebooks', ['ebooks' => PremiumEbook::with('materi')->where('is_active', true)->orderBy('title')->paginate(12)]);
    }

    public function download(PremiumEbook $ebook)
    {
        abort_unless($ebook->is_active && str_starts_with($ebook->file_path, 'premium/ebooks/') && ! str_contains($ebook->file_path, '..'), 404);
        abort_unless(Storage::disk('local')->exists($ebook->file_path), 404);

        return Storage::disk('local')->download($ebook->file_path, (Str::slug($ebook->title) ?: 'ebook').'.pdf');
    }
}
