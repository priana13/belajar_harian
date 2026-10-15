<div class="space-y-3">
    @forelse($links as $link)
        <div class="rounded-lg border border-gray-200 p-3 dark:border-gray-700">
            <input
                type="text"
                readonly
                value="{{ route('login-link', $link->token) }}"
                onclick="this.select()"
                class="w-full rounded-md border-gray-300 text-sm font-mono dark:bg-gray-800 dark:border-gray-600"
            >
            <div class="mt-2 flex flex-wrap gap-x-4 text-xs text-gray-500 dark:text-gray-400">
                <span>Berlaku sampai: {{ $link->expires_at?->translatedFormat('d M Y H:i') ?? 'Tidak pernah expired' }}</span>
                <span>Dipakai: {{ $link->use_count }}x</span>
                @if($link->last_used_at)
                    <span>Terakhir dipakai: {{ $link->last_used_at->translatedFormat('d M Y H:i') }}</span>
                @endif
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada link login yang aktif untuk user ini. Gunakan "Buat Link Login" untuk membuat yang baru.</p>
    @endforelse
</div>
