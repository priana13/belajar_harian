<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title inertia>BISI Online</title>

  @viteReactRefresh
  @vite(['resources/css/app.css', 'resources/js/app.jsx'])
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>
  @routes
  @inertiaHead
</head>
<body class="bg-gray-200">
    <div class="mx-auto max-w-lg bg-white min-h-screen shadow-md">

      <x-impersonate::banner style='light'/>

      @inertia
    </div>
</body>
</html>
