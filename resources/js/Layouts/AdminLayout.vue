<template>
  <div class="flex min-h-screen bg-gray-50 dark:bg-gray-900 overflow-x-hidden">
    <!-- Sidebar -->
    <SidebarAdmin :menu="sidebarMenu" v-model:collapsed="sidebarCollapsed" />

    <!-- Main content -->
    <div
      class="flex-1 flex flex-col transition-all duration-300"
      :class="sidebarCollapsed ? 'ml-16' : 'ml-64'"
    >
      <!-- Header -->
      <AdminHeader
        :user="user"
        :profileUrl="profileUrl"
        :logoutUrl="logoutUrl"
        :pageTitle="pageTitle"
        @toggle-sidebar="sidebarCollapsed = !sidebarCollapsed"
      />

      <!-- Page content -->
      <main class="flex-1 overflow-auto p-6 transition-all duration-300">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import AdminHeader from '@/Components/Admin/HeaderAdmin.vue'
import SidebarAdmin from '@/Components/Admin/SidebarAdmin.vue'

const props = defineProps({
  user: { type: Object, required: true },
  profileUrl: { type: String, default: '/profile' },
  logoutUrl: { type: String, default: '/logout' },
  sidebarMenu: { type: Array, required: true },
  pageTitle: { type: String, default: '' },
})

// Persist sidebar collapsed state using localStorage
const sidebarCollapsed = ref(false)

onMounted(() => {
  const saved = localStorage.getItem('sidebarCollapsed')
  sidebarCollapsed.value = saved === 'true'
})

watch(sidebarCollapsed, (val) => {
  localStorage.setItem('sidebarCollapsed', val)
})
</script>

<style scoped>
/* Scrollbar untuk main content */
main::-webkit-scrollbar { width: 6px; }
main::-webkit-scrollbar-thumb { background-color: rgba(100,100,100,0.3); border-radius: 3px; }
main::-webkit-scrollbar-thumb:hover { background-color: rgba(100,100,100,0.5); }
</style>
