<!DOCTYPE html>
<html lang="id" x-data="{ openSidebar: false }" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bisi Online Video</title>

  @vite('resources/css/app.css')
  @livewireStyles
  <x-tracking-scripts />

</head>
<body class="bg-gray-50 text-gray-800">
  

  <div class=""> 

        @yield('content')    
    
  </div>

 @livewireScripts

</body>
</html>
