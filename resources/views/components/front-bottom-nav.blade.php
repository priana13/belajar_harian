
<div>
    <div class="mt-16"></div>
    <section id="bottom-navigation" class="fixed inset-x-0 -bottom-[1px] z-10 bg-white shadow-top rounded-md py-1.5 pr-4 max-w-lg mx-auto text-gray-400">
        <div id="tabs" class="flex justify-between">
            <a href="{{route('home')}}" class="w-full focus:text-teal-500 hover:text-[#1169a8] justify-center inline-block text-center pt-2 pb-1  border-[#E0E0E0] {{ !Request::segment(1) ? 'text-secondary' : '' }}">
                <i class="fa-solid fa-house text-xl"></i>
                <span class="tab tab-home block text-xs">Home</span>
            </a>
            <a href="{{route('materi_saya')}}" class=" {{ Request::segment(1) == 'materiku' ? 'text-secondary' : '' }} w-full focus:text-teal-500 hover:text-[#1169a8] justify-center inline-block text-center pt-2 pb-1  border-[#E0E0E0]">
                <i class="fa-solid fa-book-open text-xl"></i>
                <span class="tab tab-kategori block text-xs">Materi Saya</span>
            </a>
            <a href="{{route('history_belajar')}}" class="{{ Request::segment(1) == 'history-belajar' ? 'text-secondary' : '' }} w-full focus:text-teal-500 hover:text-[#1169a8] justify-center inline-block text-center pt-2 pb-1  border-[#E0E0E0]">
                <i class="fa-solid fa-book-bookmark text-xl"></i>            
                <span class="tab tab-explore block text-xs">Hustory Belajar</span>
            </a>
            <a href="{{route('profile')}}" class="{{ Request::segment(1) == 'profile' ? 'text-secondary' : '' }} w-full focus:text-teal-500 hover:text-[#1169a8] justify-center inline-block text-center pt-2 pb-1  border-[#E0E0E0]">
                <i class="fa-solid fa-user-gear text-xl"></i>
                <span class="tab tab-whishlist block text-xs">Akun</span>
            </a>
            
        </div>
    </section>
</div>
