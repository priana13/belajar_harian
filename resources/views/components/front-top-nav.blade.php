<nav class="bg-primary-700 z-40 fixed inset-x-0 top-0 lg:sticky shadow-lg">
    <div class="max-w-lg lg:max-w-7xl mx-auto px-5 lg:px-8 py-3 grid grid-cols-2 lg:grid-cols-3 lg:items-center">
        <div class="flex items-center">
            <a href="{{ route('home') }}" >
                <img class="h-8 w-auto drop-shadow-[0_0_2px_rgba(255,255,255,0.8)]" src="{{ asset('/img/logo-trf.png') }}" alt="">
            </a>
        </div>

        <div class="hidden lg:flex items-center justify-center gap-6 text-sm text-white">
            <a href="{{ route('home') }}" class="hover:text-secondary {{ !Request::segment(1) ? 'text-secondary font-semibold' : '' }}">
                <i class="fa-solid fa-house mr-1"></i> Home
            </a>
            <a href="{{ route('materi_saya') }}" class="hover:text-secondary {{ Request::segment(1) == 'materiku' ? 'text-secondary font-semibold' : '' }}">
                <i class="fa-solid fa-book-open mr-1"></i> Materi Saya
            </a>
            <a href="{{ route('history_belajar') }}" class="hover:text-secondary {{ Request::segment(1) == 'history-belajar' ? 'text-secondary font-semibold' : '' }}">
                <i class="fa-solid fa-book-bookmark mr-1"></i> History Belajar
            </a>
        </div>

        @auth
            <div x-data="{ open: false }" class="flex items-center justify-end"  @click.away="open = false">
                <button @click="open = !open"  class="flex items-center justify-center">
                    <div class="text-3xl text-gray-400">
                        @if(auth()->user()->foto_profil)
                            <img class="h-6 w-6 rounded-full" src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="" onerror="this.onerror=null; this.src='{{ asset('/img/user.jpeg') }}'">
                        @else
                            <i class="fa-solid fa-circle-user"></i>
                        @endif


                    </div>
                    <span class="text-sm ml-1 text-white capitalize">{{ auth()->user()->name }}</span>
                    <i class="fa-solid fa-caret-down text-gray-400 ml-0.5"></i>
                </button>

                <div x-show="open" class="z-40 mt-48 bg-white border rounded-md shadow-md absolute">
                    <ul class="py-2 text-primary">
                        <li class="px-4 py-2 hover:bg-green-100">
                            <a href="{{route('materi_saya')}}" class="block"><i class="fa-solid fa-book-open mr-1"></i> Materi Saya</a>
                        </li>
                        <li class="px-4 py-2 hover:bg-green-100">
                            <a href="{{route('profile')}}" class="block"><i class="fa-solid fa-user mr-1"></i> Akun</a>
                        </li>

                        <li class="px-4 py-2 hover:bg-green-100">
                            <a href="https://drive.google.com/file/d/1XxrvPeybGL4kjt-M10doS9Zw1AD_fPMM/view?usp=sharing" class="block" target="_blank"><i class="fa-solid fa-download mr-1"></i> Download APK</a>
                        </li>

                        {{-- tombol logout di hide --}}
                        <li class="px-4 py-2 hover:bg-green-100 {{ config('app.can_logout') ? '' : 'hidden' }}">
                            <form action="{{ route('logout') }}" method="POST" >
                                @csrf

                                <button wire:ignore.self type="submit" class="block w-full text-left"><i class="fa-solid fa-right-from-bracket mr-1"></i> Log Out</button>

                            </form>

                        </li>
                    </ul>
                </div>
            </div>
        @endauth
    </div>
</nav>