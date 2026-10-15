
<div>
    <div class="mt-16"></div>
    <section id="bottom-navigation" class="fixed inset-x-0 -bottom-[1px] z-10 bg-white shadow-top rounded-md py-1.5 pr-4 max-w-lg mx-auto text-gray-400">
        <div id="tabs" class="flex justify-between">
            <a href="{{route('home')}}?trial={{ request()->trial }}" class="w-full focus:text-teal-500 hover:text-[#1169a8] justify-center inline-block text-center pt-2 pb-1  border-[#E0E0E0] {{ !Request::segment(1) ? 'text-secondary' : '' }}">
                <i class="fa-solid fa-house text-xl"></i>
                <span class="tab tab-home block text-xs">Home</span>
            </a>
            <a href="{{route('materi_saya')}}?trial={{ request()->trial }}" class=" {{ Request::segment(1) == 'materiku' ? 'text-secondary' : '' }} w-full focus:text-teal-500 hover:text-[#1169a8] justify-center inline-block text-center pt-2 pb-1  border-[#E0E0E0]">
                <i class="fa-solid fa-book-open text-xl"></i>
                <span class="tab tab-kategori block text-xs">Materi Saya</span>
            </a>
            <a href="{{route('history_belajar')}}?trial={{ request()->trial }}" class="{{ Request::segment(1) == 'history-belajar' ? 'text-secondary' : '' }} w-full focus:text-teal-500 hover:text-[#1169a8] justify-center inline-block text-center pt-2 pb-1  border-[#E0E0E0]">
                <i class="fa-solid fa-book-bookmark text-xl"></i>            
                <span class="tab tab-explore block text-xs">Hustory Belajar</span>
            </a>
            <div class="relative w-full" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.prevent.stop="open = false; $refs.menuButton.focus()">
                <button type="button" x-ref="menuButton" @click="open = !open"
                        :aria-expanded="open.toString()" aria-controls="navigation-features" aria-label="Menu fitur"
                        class="w-full focus:text-teal-500 hover:text-[#1169a8] inline-flex flex-col items-center pt-2 pb-1 {{ Request::segment(1) == 'profile' ? 'text-secondary' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7" rx="1.5" />
                        <rect x="14" y="3" width="7" height="7" rx="1.5" />
                        <rect x="3" y="14" width="7" height="7" rx="1.5" />
                    </svg>
                    <span class="block text-xs">Menu</span>
                </button>
                <div id="navigation-features" x-show="open" x-transition style="display: none;"
                     class="absolute bottom-full right-0 mb-3 w-48 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden text-gray-700">
                    <a href="{{ route('profile', ['trial' => request()->trial]) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-green-100 focus:bg-green-100">
                        <i class="fa-solid fa-user-gear" aria-hidden="true"></i>
                        <span>Akun</span>
                    </a>
                    @auth
                        <a href="{{ route('premium.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-green-100 focus:bg-green-100">
                            <i class="fa-solid fa-crown" aria-hidden="true"></i>
                            <span>Member Premium</span>
                        </a>
                        <a href="{{ route('premium.videos') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-green-100 focus:bg-green-100">
                            <i class="fa-solid fa-circle-play" aria-hidden="true"></i>
                            <span>Materi Video</span>
                        </a>
                        <a href="{{ route('premium.ebooks') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-green-100 focus:bg-green-100">
                            <i class="fa-solid fa-book" aria-hidden="true"></i>
                            <span>Ebook Materi</span>
                        </a>
                    @endauth
                    @if(auth()->user()?->jenis_user?->nama_jenis === 'Admin')
                        <a href="{{ route('filament.admin.pages.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-green-100 focus:bg-green-100">
                            <i class="fa-solid fa-user-shield" aria-hidden="true"></i>
                            <span>Admin Panel</span>
                        </a>
                    @endif
                </div>
            </div>
            
        </div>
    </section>
</div>
