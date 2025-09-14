<template>
  <nav class="bg-white dark:bg-gray-800 shadow-md p-4 flex justify-between items-center sticky top-0 z-50 h-16 border-b border-gray-200 dark:border-gray-700">
    <!-- Logo -->
    <div class="flex items-center">
      <!-- Toggle sidebar button -->
      <button @click="$emit('toggle-sidebar')" class="md:hidden mr-3 text-gray-500 dark:text-gray-400">
        <i class="fas fa-bars text-xl"></i>
      </button>
      <div class="flex items-center">
        <img src="@/assets/logo.png" alt="Spectra Logo" class="w-8 h-8 mr-2" />
        <span class="text-lg font-bold text-gray-800 dark:text-white">
          Spectra Admin
        </span>
      </div>
    </div>

    <!-- User Menu -->
    <div class="relative flex items-center space-x-4">
      <div class="hidden md:flex flex-col text-right">
        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $t('helloAdmin') }}</span>
        <button @click="toggleDropdown" class="font-semibold text-gray-800 dark:text-white focus:outline-none flex items-center text-sm">
          {{ user.name }}
          <i class="fas fa-caret-down ml-1 text-gray-400"></i>
        </button>
      </div>

      <button @click="toggleDropdown" class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-green-500 flex items-center justify-center text-white font-bold shadow-md">
        {{ userInitials }}
      </button>

      <!-- Dropdown -->
      <transition
        enter-active-class="transition duration-100 ease-out"
        enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition duration-75 ease-in"
        leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform scale-95 opacity-0"
      >
        <div v-if="dropdownOpen" class="absolute right-0 mt-12 w-48 bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden z-50 border border-gray-200 dark:border-gray-700">
          <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-800 dark:text-white">{{ user.name }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ user.email }}</p>
          </div>
          <!-- Profile Link Admin -->
          <a :href="profileUrl" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
            <i class="fas fa-user-circle mr-2"></i> Profil
          </a>
          <!-- Logout -->
          <button @click="logout" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
            <i class="fas fa-sign-out-alt mr-2"></i> {{ $t('logout') }}
          </button>
        </div>
      </transition>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  user: { type: Object, default: () => ({ name: 'Admin', email: 'admin@example.com' }) },
  profileUrl: { type: String, default: '/admin/profile' },
  logoutUrl: { type: String, default: '/logout' }
})

const emit = defineEmits(['toggle-sidebar'])
const dropdownOpen = ref(false)

const userInitials = computed(() => {
  if (!props.user.name) return 'A'
  return props.user.name.split(' ').map(w => w[0]).join('').toUpperCase().substring(0,2)
})

function toggleDropdown() { dropdownOpen.value = !dropdownOpen.value }
function logout() { router.post(props.logoutUrl) }

function handleClickOutside(event) {
  if (!event.target.closest('.relative')) dropdownOpen.value = false
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
</style>
