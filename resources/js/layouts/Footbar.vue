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
        :class="active === 'karir' ? 'text-blue-600' : 'text-gray-600'"
        @click="goTo('karir')"
      >
        <i class="fas fa-briefcase text-xl"></i>
        <span class="text-xs">Karir</span>
      </div>

      <!-- Menu -->
      <div
        class="flex flex-col items-center cursor-pointer"
        :class="active === 'menu' ? 'text-blue-600' : 'text-gray-600'"
        @click="goTo('menu')"
      >
        <i class="fas fa-bars text-xl"></i>
        <span class="text-xs">Menu</span>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref, getCurrentInstance } from 'vue'

const active = ref('dashboard')

// akses $inertia dari instance
const { proxy } = getCurrentInstance()

const goTo = (page) => {
  active.value = page

  switch (page) {
    case 'akademi': proxy.$inertia.visit('/akademi'); break
    case 'chat': proxy.$inertia.visit('/chat'); break
    case 'dashboard': proxy.$inertia.visit('/dashboard'); break
    case 'karir': proxy.$inertia.visit('/karir'); break
    case 'menu': proxy.$inertia.visit('/menu'); break
  }
}
</script>
