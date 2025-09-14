<template>
  <div :class="['flex min-h-screen overflow-x-hidden', themeClass]">
    <!-- Sidebar -->
    <SidebarInvestor v-model:collapsed="sidebarCollapsed" />

    <!-- Main content -->
    <div
      class="flex-1 flex flex-col transition-all duration-300"
      :class="sidebarCollapsed ? 'ml-16' : 'ml-64'"
    >
      <!-- Header -->
      <HeaderInvestor
        :user="user || {}"
        :profileUrl="profileUrl"
        :logoutUrl="logoutUrl"
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
import { ref, watch, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import SidebarInvestor from '@/Components/Investor/SidebarInvestor.vue';
import HeaderInvestor from '@/Components/Investor/HeaderInvestor.vue';

// Props dari parent
const props = defineProps({
  user: { type: Object, required: false, default: () => ({}) },
  profileUrl: { type: String, default: '/profile' },
  logoutUrl: { type: String, default: '/logout' },
});

// Ambil props Inertia (jika perlu untuk theme dll)
const { props: pageProps } = usePage();

// Sidebar collapsed state (persist di localStorage)
const sidebarCollapsed = ref(false);
onMounted(() => {
  const saved = localStorage.getItem('sidebarCollapsed');
  sidebarCollapsed.value = saved === 'true';
});
watch(sidebarCollapsed, val => {
  localStorage.setItem('sidebarCollapsed', val);
});

// Theme berdasarkan user_preferences, fallback ke light
const themeClass = computed(() => {
  const theme = pageProps.user_preferences?.theme ?? 'light';
  return theme === 'dark' ? 'bg-gray-900 text-white' : 'bg-gray-50 text-gray-900';
});
</script>

<style scoped>
/* Scrollbar untuk main content */
main::-webkit-scrollbar { width: 6px; }
main::-webkit-scrollbar-thumb { background-color: rgba(100,100,100,0.3); border-radius: 3px; }
main::-webkit-scrollbar-thumb:hover { background-color: rgba(100,100,100,0.5); }
</style>
