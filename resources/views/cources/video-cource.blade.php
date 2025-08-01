<!DOCTYPE html>
<html lang="id" x-data="{ openSidebar: false }" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Belajar C++ - Komparasi</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800">

  <div class="lg:flex min-h-screen">
    <!-- Sidebar -->
    <aside class="bg-white shadow-lg p-6 w-full lg:w-72" :class="{ 'hidden': !openSidebar && window.innerWidth < 1024 }">
      <div class="flex justify-between items-center mb-4 lg:hidden">
        <h2 class="text-xl font-bold text-green-700">CODEPOLITAN</h2>
        <button class="text-sm text-red-500 font-semibold" @click="openSidebar = false">Tutup ✖</button>
      </div>
      <h3 class="text-sm font-semibold text-gray-700 mb-4 hidden lg:block">MODULE BELAJAR</h3>
      <ul class="space-y-3 text-sm">
        <li class="flex justify-between items-center hover:text-green-700 cursor-pointer bg-gray-100 p-3 rounded-lg">
          <span>1. Apa itu C++</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#63E6BE" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>         </li>
        <li class="flex justify-between items-center hover:text-green-700 cursor-pointer bg-gray-100 p-3 rounded-lg">
          <span>2. Cara Kerja C++</span>         
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#63E6BE" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>         
        </li>
        <li class="flex justify-between items-center hover:text-green-700 cursor-pointer bg-blue-400 text-white p-3 rounded-lg">
          <span>3. Instalasi Codeblocks</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#63E6BE" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>         </li>
        <li class="flex justify-between items-center hover:text-green-700 cursor-pointer bg-gray-100 p-3 rounded-lg">
          <span>4. Instalasi Codeblocks</span>         
        </li>
        <li class="flex justify-between items-center hover:text-green-700 cursor-pointer bg-gray-100 p-3 rounded-lg">
          <span>5. Instalasi Codeblocks</span>         
        </li>
        <!-- Tambah lainnya -->
      </ul>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
      <!-- Toggle Sidebar (mobile) -->
      <div class="lg:hidden mb-4">
        <button @click="openSidebar = !openSidebar" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
          📚 Lihat Materi
        </button>
      </div>

      <nav class="text-sm text-gray-500 mb-2">
        Kelas Saya / Belajar C++ Dasar / <span class="text-gray-800 font-semibold">Komparasi</span>
      </nav>

      <h1 class="text-2xl font-bold mb-6">Komparasi (Relasi) Boolean</h1>

      <!-- Video -->
      <div class="aspect-w-16 aspect-h-9 mb-6 text-center">
        <iframe width="800" height="400" src="https://www.youtube.com/embed/36iA7VRW1ts?si=g1_uCktgXzzNQ8mJ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
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
    </main>
  </div>

</body>
</html>
