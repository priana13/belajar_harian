<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'BISI Online')</title>
  <link rel="icon" type="image/x-icon" href="/img/icon.png">
  @stack('head')

  @vite('resources/css/app.css')
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>

  @livewireStyles
</head>
<body class="bg-gray-200">
    <div  class="mx-auto max-w-lg bg-white min-h-screen shadow-md">
      <div class="">

        <x-impersonate::banner style='light'/>

      </div>
     
        @yield('content')
    </div>
    @livewireScripts
</body>
</html>