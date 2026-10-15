@extends('premium.layout')
@section('premium-content')
<header>
    <h1 class="text-2xl font-bold text-green-800">Member Premium</h1>
    <p class="mt-2 text-gray-600">Akses Materi Video, Ebook Materi, dan buku gratis untuk materi yang menyediakan buku. Ongkir ditanggung pengguna.</p>
    @if(auth()->user()->hasActivePremium())
        <p class="mt-3 text-green-700 font-semibold">Premium aktif hingga {{ \Illuminate\Support\Carbon::parse($activeUntil)->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB.</p>
    @else
        <p class="mt-3 text-gray-600">Langganan Premium belum aktif. Pilih paket dan konfirmasikan transfer untuk mengaktifkannya.</p>
    @endif
</header>
<div class="grid grid-cols-2 gap-3">
    <a href="{{ route('premium.videos') }}" class="p-4 rounded-lg bg-green-100 text-green-800 font-semibold">▶ Materi Video</a>
    <a href="{{ route('premium.ebooks') }}" class="p-4 rounded-lg bg-blue-100 text-blue-800 font-semibold">📖 Ebook Materi</a>
</div>
<section class="space-y-3">
    <h2 class="text-lg font-bold">Paket langganan</h2>
    <p class="text-sm text-gray-600">Pembayaran melalui transfer manual. Langganan aktif setelah admin memverifikasi pembayaran. Perpanjangan menambah masa aktif; tidak ada penagihan otomatis.</p>
    @forelse($plans as $plan)
        <article class="p-4 bg-white border rounded-lg">
            <h3 class="font-bold">{{ $plan->name }}</h3>
            <p>{{ $plan->duration_months }} bulan · Rp {{ number_format($plan->price, 0, ',', '.') }}</p>
            <form method="POST" action="{{ route('premium.order') }}" class="mt-3">@csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <button class="px-4 py-2 rounded bg-green-700 text-white" type="submit">{{ auth()->user()->hasActivePremium() ? 'Perpanjang' : 'Pilih paket' }}</button>
            </form>
        </article>
    @empty <p class="text-gray-500">Paket Premium belum tersedia.</p> @endforelse
</section>
<section class="space-y-3">
    <h2 class="text-lg font-bold">Pesanan langganan</h2>
    @forelse($subscriptions as $subscription)
        <article class="p-4 bg-white border rounded-lg space-y-2">
            <h3 class="font-semibold">#{{ $subscription->id }} · {{ $subscription->plan_name }}</h3>
            <p>{{ $subscription->duration_months }} bulan · Rp {{ number_format($subscription->price, 0, ',', '.') }}</p>
            <p class="text-sm">{{ ['pending' => 'Menunggu pembayaran / verifikasi', 'paid' => 'Pembayaran terverifikasi', 'cancelled' => 'Dibatalkan'][$subscription->status] ?? $subscription->status }}</p>
            @if($subscription->status === 'paid')
                <p class="text-sm">{{ $subscription->starts_at->timezone('Asia/Jakarta')->format('d M Y H:i') }} – {{ $subscription->ends_at->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</p>
            @elseif($subscription->status === 'pending')
                <p class="text-sm whitespace-pre-line">{{ $subscription->payment_instructions }}</p>
                @if($subscription->payment_reference)
                    <p class="text-sm text-amber-700">Referensi transfer: {{ $subscription->payment_reference }}. Menunggu verifikasi admin.</p>
                @endif
                <form method="POST" action="{{ route('premium.payment', $subscription->id) }}" class="space-y-2">@csrf
                    <label for="payment-{{ $subscription->id }}" class="block text-sm">Konfirmasi transfer (nama pengirim, bank, waktu / nomor referensi)</label>
                    <input id="payment-{{ $subscription->id }}" name="payment_reference" required minlength="5" maxlength="255" class="w-full rounded border-gray-300" value="{{ $subscription->payment_reference }}">
                    <button type="submit" class="px-4 py-2 rounded bg-green-700 text-white">Kirim konfirmasi</button>
                </form>
                @if(!$subscription->payment_reference)
                    <form method="POST" action="{{ route('premium.cancel', $subscription->id) }}">@csrf
                        <button type="submit" class="text-sm text-red-700">Batalkan pesanan</button>
                    </form>
                @endif
            @endif
        </article>
    @empty <p class="text-gray-500">Belum ada pesanan.</p> @endforelse
</section>
<section class="space-y-3" id="klaim-buku">
    <h2 class="text-lg font-bold">Klaim buku gratis</h2>
    <p class="text-sm text-gray-600">Satu buku per materi, satu kali per akun. Pilih beberapa buku untuk satu pengiriman. Alamat wajib diisi. Admin menginformasikan ongkir sebelum buku dikirim.</p>
    @if(auth()->user()->hasActivePremium() && $books->isNotEmpty())
        <form method="POST" action="{{ route('premium.claim') }}" class="p-4 border rounded-lg bg-white space-y-3">@csrf
            <fieldset class="space-y-2"><legend class="font-semibold mb-2">Pilih buku</legend>
                @foreach($books as $book)
                    <label class="flex gap-2 items-start"><input type="checkbox" name="books[]" value="{{ $book->id }}" @checked(in_array($book->id, old('books', []))) class="mt-1 rounded"><span>{{ $book->title }} <small class="block text-gray-500">{{ $book->materi->nama_materi }}</small></span></label>
                @endforeach
            </fieldset>
            @foreach(['recipient_name' => 'Nama penerima', 'phone' => 'Nomor HP / WhatsApp', 'province' => 'Provinsi', 'city' => 'Kota / Kabupaten', 'postal_code' => 'Kode pos'] as $field => $label)
                <label class="block text-sm" for="{{ $field }}">{{ $label }}</label>
                <input id="{{ $field }}" name="{{ $field }}" value="{{ old($field) }}" required maxlength="{{ $field === 'phone' ? 30 : ($field === 'postal_code' ? 5 : 255) }}" class="w-full rounded border-gray-300" @if($field === 'postal_code') inputmode="numeric" pattern="[0-9]{5}" @endif>
            @endforeach
            <label class="block text-sm" for="address">Alamat lengkap (jalan, nomor rumah, RT/RW, kelurahan, kecamatan)</label>
            <textarea id="address" name="address" required minlength="10" maxlength="2000" rows="3" class="w-full rounded border-gray-300">{{ old('address') }}</textarea>
            <label class="flex items-start gap-2 text-sm"><input type="checkbox" name="accept_shipping" value="1" required class="mt-1 rounded"><span>Saya bersedia membayar ongkir yang diinformasikan admin.</span></label>
            <button type="submit" class="px-4 py-2 rounded bg-green-700 text-white">Ajukan klaim buku</button>
        </form>
    @elseif(!auth()->user()->hasActivePremium())
        <p class="text-gray-500">Aktifkan Premium untuk mengklaim buku.</p>
    @else
        <p class="text-gray-500">Belum ada buku yang tersedia untuk diklaim.</p>
    @endif
</section>
<section class="space-y-3">
    <h2 class="text-lg font-bold">Pengiriman buku saya</h2>
    @forelse($claims as $claim)
        <article class="p-4 border rounded-lg bg-white space-y-2">
            <h3 class="font-semibold">Klaim #{{ $claim->id }}</h3>
            <ul class="list-disc list-inside">@foreach($claim->items as $item)<li>{{ $item->book_title }}</li>@endforeach</ul>
            <p class="text-sm">{{ $claim->recipient_name }} · {{ $claim->phone }}</p>
            <p class="text-sm whitespace-pre-line">{{ $claim->address }}, {{ $claim->city }}, {{ $claim->province }} {{ $claim->postal_code }}</p>
            <p class="text-sm font-semibold">{{ ['requested' => 'Menunggu informasi ongkir', 'awaiting_payment' => 'Menunggu pembayaran ongkir', 'payment_review' => 'Ongkir menunggu verifikasi', 'shipped' => 'Sudah dikirim'][$claim->status] ?? $claim->status }}</p>
            @if($claim->shipping_fee) <p>Ongkir: Rp {{ number_format($claim->shipping_fee, 0, ',', '.') }}</p> @endif
            @if($claim->status === 'awaiting_payment')
                <p class="text-sm whitespace-pre-line">{{ $claim->shipping_payment_instructions }}</p>
                <form method="POST" action="{{ route('premium.shipping-payment', $claim->id) }}" class="space-y-2">@csrf
                    <label for="shipping-{{ $claim->id }}" class="block text-sm">Konfirmasi transfer ongkir (nama pengirim, bank, waktu / nomor referensi)</label>
                    <input id="shipping-{{ $claim->id }}" name="payment_reference" required minlength="5" maxlength="255" class="w-full rounded border-gray-300">
                    <button type="submit" class="px-4 py-2 rounded bg-green-700 text-white">Konfirmasi ongkir</button>
                </form>
            @endif
            @if($claim->status === 'shipped') <p class="text-sm">{{ $claim->courier }} · Resi: {{ $claim->tracking_number }}</p> @endif
        </article>
    @empty <p class="text-gray-500">Belum ada klaim buku.</p> @endforelse
</section>
@endsection
