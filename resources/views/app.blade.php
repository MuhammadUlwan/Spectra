<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <title inertia>{{ config('app.name', 'Laravel') }}</title>

      @vite(['resources/css/app.css', 'resources/js/app.js'])
      @inertiaHead

      <!-- ✅ Tambahkan di sini -->
      <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>
  <body class="font-sans antialiased">
      @inertia
  </body>
</html>
