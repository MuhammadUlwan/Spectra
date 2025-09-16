<template>
  <!-- Footer Bar di bagian bawah -->
  <footer
    :class="[
      'fixed bottom-0 left-0 right-0 bg-gray-800 z-40 border-t border-gray-700 transition-all duration-300',
      collapsed ? 'h-16 overflow-hidden' : 'h-auto overflow-auto'
    ]"
  >
    <!-- Header footer (bisa dihilangkan atau disederhanakan) -->


    <!-- Menu - Diubah menjadi horizontal -->
    <nav class="flex-1 px-2 py-2">
      <div class="flex flex-wrap justify-center gap-1">
        <div v-for="item in menuItems" :key="item.label" class="group">
          <a
            :href="item.url || '#'"
            @click.prevent="handleClick(item)"
            class="flex flex-col items-center px-3 py-2 rounded-lg transition-all duration-200 relative min-w-[80px]"
            :class="item.active
              ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold shadow-lg'
              : 'text-gray-300 hover:bg-gray-700'"
          >
            <i :class="item.icon + ' text-center text-lg mb-1'"></i>
            <span class="text-xs text-center">{{ item.label }}</span>
          </a>
        </div>
      </div>
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
  </footer>

  <!-- Spacer untuk konten utama agar tidak tertutup footer -->
  <div :class="['transition-all duration-300', collapsed ? 'h-16' : 'h-auto']"></div>
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
    if (item.url.startsWith('/')) {
      router.visit(item.url)
    } else {
      window.open(item.url, '_blank')
    }
  }
}
</script>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active { transition: all 0.3s ease; }
.fade-slide-enter-from, .fade-slide-leave-to { opacity:0; transform: translateX(-10px); }
</style>