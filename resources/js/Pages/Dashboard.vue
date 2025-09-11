<template>
  <div class="min-h-screen bg-gray-100 flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white shadow p-4 flex justify-between items-center">
      <h1 class="text-xl font-bold text-gray-800">Dashboard</h1>
      <form :action="route('logout')" method="POST">
        <input type="hidden" name="_token" :value="csrf">
        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
          Logout
        </button>
      </form>
    </nav>

    <!-- Konten utama -->
    <main class="flex-1 p-6">
      <h2 class="text-2xl font-semibold mb-4">Selamat datang, {{ user.name }}!</h2>
      <p class="text-gray-700">Ini adalah halaman dashboardmu.</p>
    </main>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
const { props } = usePage()
const user = props.auth.user
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
</script>
