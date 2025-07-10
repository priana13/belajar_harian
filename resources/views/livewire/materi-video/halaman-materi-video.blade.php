<div class="lg:flex min-h-screen" x-data="{ openSidebar: true }">
   
    <!-- Sidebar -->
    <aside x-show="openSidebar" class="bg-white shadow-lg p-6 w-full sm:w-1/3 relative" :class="{ 'hidden': !openSidebar && window.innerWidth < 1024, 'lg:block': openSidebar }">
      <button class="hidden lg:flex items-center justify-center absolute -right-3 top-1 w-10 h-10 bg-white border border-gray-200 shadow rounded-lg z-20 hover:bg-gray-100 transition p-3" @click="openSidebar = false" title="Sembunyikan Sidebar" style="line-height:1;">
       
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160zm352-160l-160 160c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L301.3 256 438.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0z"/></svg>
        
      </button>
      <div class="flex justify-between items-center mb-4 lg:hidden">
        <h2 class="text-xl font-bold text-green-700">BISI</h2>
        <button class="text-sm text-red-500 font-semibold" @click="openSidebar = false">✖</button>
      </div>
      <h3 class="text-sm font-semibold text-gray-700 mb-4 hidden lg:block">MODULE BELAJAR</h3>
      <ul class="space-y-3 text-sm">

        @foreach($materiDetail as $row)       

        <li class="flex justify-between items-center hover:text-green-700 cursor-pointer bg-gray-100 p-3 rounded-lg" wire:click="selectVideo({{ $row->id }})">
          <span>{{ $loop->iteration }}. {{ $row->judul }}</span>         
          {{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 512 512"><path fill="#63E6BE" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>          --}}
        </li>

        @endforeach
       
      </ul>
    </aside>

    <!-- Main Content -->
    <div class="p-6 w-full mt-2">
      <!-- Toggle Sidebar (mobile & desktop) -->
      <div class="mb-4 flex gap-2">
        <button @click="openSidebar = !openSidebar" class="lg:hidden bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
          📚
        </button>
        <button @click="openSidebar = true" class="hidden lg:flex items-center justify-center absolute -left-1 top-1 w-10 h-10 bg-white border border-gray-200 shadow rounded-lg z-20 hover:bg-gray-100 transition p-3" x-show="!openSidebar" style="line-height:1;" title="Tampilkan Sidebar">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M470.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 256 265.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160zm-352 160l160-160c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L210.7 256 73.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0z"/></svg>
        </button>
      </div>

      {{-- breadcrumb --}}
      <nav class="text-sm text-gray-500 mb-2 flex items-center gap-1">
        <span>{{ $materi->kategori->nama_kategori }}</span>
        
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 320 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>

        <span>{{ $materi->nama_materi }}</span>     
        
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 320 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
        
        <span class="text-gray-800 font-semibold">{{ $selectedVideo->judul }}</span>
      </nav>

      <h1 class="text-2xl font-bold mb-6">{{ $selectedVideo->judul }}</h1>    

      <!-- Video -->
      <div class="aspect-w-16 aspect-h-9 mb-6 text-center p-4">
        <iframe class="w-full min-h-screen rounded-lg shadow-lg" src="{{ $selectedVideo->video_url }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
      </div>
    

      <!-- Info -->
      <div class="text-gray-500 italic mb-8 text-center">
        Tidak ada materi teks untuk video ini.
      </div>

      <!-- Actions -->
      <div class="flex flex-col sm:flex-row justify-center gap-4 mb-12">
        <button class="bg-gray-700 text-white px-6 py-2 rounded hover:bg-gray-800 transition">💬 Tanyakan di Forum</button>
        <button class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">✅ Ya, Saya Sudah Paham</button>
      </div>

      <!-- Komentar -->
      <section class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-lg font-semibold mb-4">💬 Tinggalkan Komentar</h2>
        <form action="#" method="POST" class="space-y-4">
          <input type="text" name="nama" placeholder="Nama Anda" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:ring-green-200" required />
          <textarea name="komentar" rows="4" placeholder="Tulis komentar Anda..." class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:ring-green-200" required></textarea>
          <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">Kirim Komentar</button>
        </form>
      </section>
    </div>
   


  </div>
