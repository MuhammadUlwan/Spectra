<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import SidebarInvestor from '@/Components/Investor/SidebarInvestor.vue';
import HeaderInvestor from '@/Components/Investor/HeaderInvestor.vue';

const props = defineProps({
  user: { type: Object, default: () => ({}) },
  profileUrl: { type: String, default: '/profile' },
  logoutUrl: { type: String, default: '/logout' },
  sidebarMenu: { type: Array, default: () => [] },
  userPref: { type: Object, default: () => ({}) },
});

const sidebarCollapsed = ref(false);
onMounted(() => {
  const saved = localStorage.getItem('sidebarCollapsed');
  sidebarCollapsed.value = saved === 'true';
});
watch(sidebarCollapsed, val => {
  localStorage.setItem('sidebarCollapsed', val);
});

// Theme berdasarkan userPref, fallback ke light
const themeClass = computed(() => {
  const theme = props.userPref?.theme ?? 'light';
  return theme === 'dark' ? 'bg-gray-900 text-white' : 'bg-gray-50 text-gray-900';
});
</script>

<template>
  <div :class="['flex min-h-screen overflow-x-hidden', themeClass]">
    <SidebarInvestor v-model:collapsed="sidebarCollapsed" :menuItems="sidebarMenu" />

    <div class="flex-1 flex flex-col transition-all duration-300" :class="sidebarCollapsed ? 'ml-16' : 'ml-64'">
      <HeaderInvestor
        :user="user"
        :profileUrl="profileUrl"
        :logoutUrl="logoutUrl"
        @toggle-sidebar="sidebarCollapsed = !sidebarCollapsed"
      />
      <main class="flex-1 overflow-auto p-6 transition-all duration-300">
        <slot />
      </main>
    </div>
  </div>
</template>