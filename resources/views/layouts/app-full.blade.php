<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=0, user-scalable=yes">  <title>@yield('title', 'BISI')</title>
  {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> --}}
  @stack('head')

  @vite('resources/css/app.css')
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-alpha1/html2canvas.min.js"></script>

  {{-- <style>

  html, body {
  zoom: 100%;       /* untuk Chrome/Edge */
  -moz-transform: scale(1); /* untuk Firefox */
  -moz-transform-origin: 0 0;
  overflow-x: hidden;
}

  </style> --}}

  @livewireStyles
</head>
<body class="">
    <div  class="">
        @yield('content')
    </div>
    @livewireScripts
</body>
</html>