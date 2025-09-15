<template>
  <aside
    :class="[
      'fixed top-0 left-0 h-full bg-gray-800 z-40 border-r border-gray-700 transition-all duration-300',
      collapsed ? 'w-16 overflow-hidden' : 'w-64 overflow-auto'
    ]"
  >
    <!-- Header sidebar -->
    <div class="flex items-center h-16 px-4 border-b border-gray-700">
      <button @click="$emit('update:collapsed', !collapsed)" class="flex flex-col justify-center items-center h-8 w-8 focus:outline-none group">
        <span class="block h-0.5 w-6 bg-gray-400 transition-all duration-300" :class="{'rotate-45 translate-y-2.5': collapsed}"></span>
        <span class="block h-0.5 w-6 bg-gray-400 my-1.5 transition-all duration-300" :class="{'opacity-0': collapsed}"></span>
        <span class="block h-0.5 w-6 bg-gray-400 transition-all duration-300" :class="{'-rotate-45 -translate-y-2.5': collapsed}"></span>
      </button>
      <transition name="fade-slide">
        <h1 v-if="!collapsed" class="flex items-center text-white font-bold text-lg ml-3 whitespace-nowrap">
          Spectra Investor
        </h1>
      </transition>
    </div>

    <!-- Menu -->
    <nav class="flex-1 mt-2 px-2">
      <ul>
        <li v-for="item in menuItems" :key="item.label" class="group mb-1">
          <a
            :href="item.url || '#'"
            @click.prevent="handleClick(item)"
            class="flex items-center px-3 py-3 rounded-lg transition-all duration-200 relative"
            :class="item.active
              ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold shadow-lg'
              : 'text-gray-300 hover:bg-gray-700'"
          >
            <i :class="item.icon + ' w-5 text-center text-lg'"></i>
            <span v-if="!collapsed" class="ml-3 text-sm">{{ item.label }}</span>
            <span
              v-if="collapsed"
              class="absolute left-16 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap"
            >
              {{ item.label }}
            </span>
          </a>
        </li>
      </ul>
    </nav>

    <!-- Simple modal untuk coming soon -->
    <div v-if="showComingSoon" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 text-center shadow-lg">
        <p class="text-gray-800 font-semibold text-lg mb-4">Coming Soon!</p>
        <button @click="showComingSoon = false" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
          Tutup
        </button>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  collapsed: { type: Boolean, default: false },
})

const { props: pageProps } = usePage()
const urls = pageProps.urls || { academy: '', tutorial: '', chatbot: '' }

const showComingSoon = ref(false)

const menuItems = ref([
  { label: 'Dashboard', url: '/dashboard', icon: 'fas fa-home', active: false },
  { label: 'Deposit', url: '/deposit', icon: 'fas fa-plus', active: false },
  { label: 'Dompet', url: '/dompet', icon: 'fas fa-wallet', active: false },
  { label: 'Karir', url: '/karir', icon: 'fas fa-user-friends', active: false },

  { 
    label: 'Akademi', 
    url: urls.academy || null, 
    icon: 'fas fa-graduation-cap', 
    active: false, 
    comingSoon: !urls.academy 
  },
  { 
    label: 'Tutorial', 
    url: urls.tutorial || null, 
    icon: 'fas fa-video', 
    active: false, 
    comingSoon: !urls.tutorial 
  },
  { 
    label: 'ChatBot', 
    url: urls.chatbot || null, 
    icon: 'fas fa-robot', 
    active: false, 
    comingSoon: !urls.chatbot 
  },
  { label: 'Settings', url: '/settings', icon: 'fas fa-cog', active: false }
])

// Update active menu otomatis
const { url } = usePage()
const updateActiveState = () => {
  menuItems.value.forEach(item => item.active = url.startsWith(item.url))
}
updateActiveState()
watch(() => url, () => updateActiveState())

// Handle klik item
const handleClick = (item) => {
  if (item.comingSoon) {
    showComingSoon.value = true
  } else if (item.url) {
    // Jika url internal (dimulai dengan "/"), pakai Inertia visit
  if (item.url.startsWith('/')) {
    router.visit(item.url) // pakai Inertia
  } else {
    window.open(item.url, '_blank') // eksternal
  }
  }
}
</script>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active { transition: all 0.3s ease; }
.fade-slide-enter-from, .fade-slide-leave-to { opacity:0; transform: translateX(-10px); }
</style>