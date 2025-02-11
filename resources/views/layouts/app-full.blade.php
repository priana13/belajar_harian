<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=0, user-scalable=yes">  <title>@yield('title', 'BISI')</title>
  @stack('head')

  @vite('resources/css/app.css')
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-alpha1/html2canvas.min.js"></script>

  @livewireStyles
</head>
<body class="">
    <div  class="">
        @yield('content')
    </div>
    @livewireScripts
</body>
</html>