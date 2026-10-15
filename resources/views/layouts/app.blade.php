<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'BISI Online')</title>
  @stack('head')

  @vite('resources/css/app.css')
  @livewireStyles
  <x-tracking-scripts />
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