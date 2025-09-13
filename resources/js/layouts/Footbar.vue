<template>
  <footer class="bg-white shadow-inner border-t px-6 py-3">
    <div class="flex justify-around">
      <!-- Akademi -->
      <div
        class="flex flex-col items-center cursor-pointer"
        :class="active === 'akademi' ? 'text-blue-600' : 'text-gray-600'"
        @click="goTo('akademi')"
      >
        <i class="fas fa-graduation-cap text-xl"></i>
        <span class="text-xs">Akademi</span>
      </div>

      <!-- Chat -->
      <div
        class="flex flex-col items-center cursor-pointer"
        :class="active === 'chat' ? 'text-blue-600' : 'text-gray-600'"
        @click="goTo('chat')"
      >
        <i class="fas fa-comments text-xl"></i>
        <span class="text-xs">Chat</span>
      </div>

      <!-- Beranda -->
      <div
        class="flex flex-col items-center cursor-pointer"
        :class="active === 'dashboard' ? 'text-blue-600' : 'text-gray-600'"
        @click="goTo('dashboard')"
      >
        <i class="fas fa-home text-xl"></i>
        <span class="text-xs">Beranda</span>
      </div>

      <!-- Karir -->
      <div
        class="flex flex-col items-center cursor-pointer"
        :class="active === 'career' ? 'text-blue-600' : 'text-gray-600'"
        @click="goTo('career')"
      >
        <i class="fas fa-briefcase text-xl"></i>
        <span class="text-xs">Karir</span>
      </div>

      <!-- Pengaturan -->
      <div
        class="flex flex-col items-center cursor-pointer"
        :class="active === 'pengaturan' ? 'text-blue-600' : 'text-gray-600'"
        @click="goTo('pengaturan')"
      >
        <i class="fas fa-bars text-xl"></i>
        <span class="text-xs">Pengaturan</span>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref, getCurrentInstance, onMounted, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const active = ref('dashboard')
const { proxy } = getCurrentInstance()

// Deteksi halaman aktif berdasarkan URL saat ini
onMounted(() => {
  updateActiveTab()
})

// Listen to page changes
watch(() => usePage().url, () => {
  updateActiveTab()
})

const updateActiveTab = () => {
  const path = window.location.pathname;
  if (path.includes('/akademi')) active.value = 'akademi'
  else if (path.includes('/chat')) active.value = 'chat'
  else if (path.includes('/dashboard')) active.value = 'dashboard'
  else if (path.includes('/career')) active.value = 'career'
  else if (path.includes('/pengaturan')) active.value = 'pengaturan'
  else active.value = 'dashboard'
}

const goTo = (page) => {
  active.value = page

  switch (page) {
    case 'akademi': 
      proxy.$inertia.visit('/akademi'); 
      break
    case 'chat': 
      proxy.$inertia.visit('/chat'); 
      break
    case 'dashboard': 
      proxy.$inertia.visit('/dashboard'); 
      break
    case 'career': 
      proxy.$inertia.visit('/career'); 
      break
    case 'pengaturan': 
      proxy.$inertia.visit('/pengaturan'); 
      break
  }
}
</script>