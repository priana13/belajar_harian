<nav class="bg-primary-700 py-3 z-40 px-5 grid grid-cols-2 fixed inset-x-0 max-w-lg mx-auto -top-[1px]">
    <div class="flex items-center">
        <a href="{{ route('home') }}" >
            <img class="h-8 w-auto" src="{{ asset('storage/icon/logo_ksi_putih.png') }}" alt="">
        </a>
    </div>
    @auth
        <div x-data="{ open: false }" class="flex items-center justify-end"  @click.away="open = false">
            <button @click="open = !open"  class="flex items-center justify-center">
                <div class="text-3xl text-gray-400">
                    @if(auth()->user()->foto_profil)
                        <img class="h-6 w-6 rounded-full" src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="">
                    @else
                        <i class="fa-solid fa-circle-user"></i>
                    @endif
                   
                    
                </div>
                <span class="text-sm ml-1 text-white">{{ auth()->user()->name }}</span>
                <i class="fa-solid fa-caret-down text-gray-400 ml-0.5"></i>
            </button>
            
            <div x-show="open" class="z-40 mt-48 bg-white border rounded-md shadow-md absolute">
                <ul class="py-2 text-primary">
                    <li class="px-4 py-2 hover:bg-green-100">
                        <a href="{{route('materi_saya')}}" class="block"><i class="fa-solid fa-book-open mr-1"></i> Materiku</a>
                    </li>
                    <li class="px-4 py-2 hover:bg-green-100">
                        <a href="{{route('profile')}}" class="block"><i class="fa-solid fa-user mr-1"></i> Akun</a>
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


</nav>
<p class="py-8"></p>