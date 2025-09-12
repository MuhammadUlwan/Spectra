<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">Admin Dashboard</h1>
    <p class="text-gray-600 mb-6">Halo, {{ user.name }}! Anda login sebagai admin.</p>

    <!-- Contoh tombol kontrol admin -->
    <div class="space-x-3">
      <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Kelola User</button>
      <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Laporan Keuangan</button>
      <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Settings</button>
    </div>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
const { props } = usePage()
const user = props.auth.user
</script>
