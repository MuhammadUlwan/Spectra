<template>
  <div class="flex min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Sidebar Mobile Overlay -->
    <div
      v-if="!sidebarCollapsed"
      class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
      @click="sidebarCollapsed = true"
    ></div>

    <!-- Sidebar -->
    <SidebarAdmin
      :collapsed="sidebarCollapsed"
      @update:collapsed="sidebarCollapsed = $event"
      class="fixed z-50 h-full transition-transform transform md:relative md:translate-x-0"
      :class="sidebarCollapsed ? '-translate-x-full md:translate-x-0' : 'translate-x-0'"
    />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 transition-all duration-300">
      <!-- Header -->
      <AdminHeader
        :user="user"
        :profileUrl="profileUrl"
        :logoutUrl="logoutUrl"
        @toggle-sidebar="sidebarCollapsed = !sidebarCollapsed"
      />

      <!-- Page Content -->
      <main class="flex-1 overflow-x-hidden p-4 md:p-6">
        <div class="mb-4 md:mb-6">
          <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ pageTitle }}</h1>
        </div>

        <!-- Slot halaman -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 md:p-6">
          <slot />
        </div>
      </main>

      <!-- Footer -->
      <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4 text-center text-sm text-gray-600 dark:text-gray-400">
        © {{ new Date().getFullYear() }} Spectra Admin. All rights reserved.
      </footer>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import SidebarAdmin from '@/Components/Admin/SidebarAdmin.vue'
import AdminHeader from '@/Components/Admin/HeaderAdmin.vue'

const props = defineProps({
  user: { type: Object, required: true },
  profileUrl: { type: String, default: '/profile' },
  logoutUrl: { type: String, default: '/logout' },
  pageTitle: { type: String, default: '' },
})

const sidebarCollapsed = ref(false)

onMounted(() => {
  const saved = localStorage.getItem('sidebarCollapsed')
  sidebarCollapsed.value = saved === 'true'
})

watch(sidebarCollapsed, val => {
  localStorage.setItem('sidebarCollapsed', val)
})
</script>

<style scoped>
main::-webkit-scrollbar { width: 6px; }
main::-webkit-scrollbar-thumb { background-color: rgba(100,100,100,0.3); border-radius: 3px; }
main::-webkit-scrollbar-thumb:hover { background-color: rgba(100,100,100,0.5); }

aside { transition: transform 0.3s ease; }
</style>