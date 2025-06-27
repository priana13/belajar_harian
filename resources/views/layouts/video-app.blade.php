<!DOCTYPE html>
<html lang="id" x-data="{ openSidebar: false }" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bisi Online Video</title>

  @vite('resources/css/app.css')
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>
  @livewireStyles

</head>
<body class="bg-gray-50 text-gray-800">
  

  <div class=""> 

        @yield('content')    
    
  </div>

 @livewireScripts

</body>
</html>
