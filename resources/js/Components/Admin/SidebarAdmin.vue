<template>
  <aside
    :class="[
      'fixed top-0 left-0 h-full bg-gray-800 dark:bg-gray-900 z-40 border-r border-gray-700 transition-all duration-300',
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
          Spectra Panel
        </h1>
      </transition>
    </div>

    <!-- Menu -->
    <nav class="flex-1 mt-2 px-2">
      <ul>
        <li v-for="item in menuItems" :key="item.label" class="group mb-1">
          <a
            :href="item.url"
            class="flex items-center px-3 py-3 rounded-lg transition-all duration-200 relative"
            :class="item.active
              ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold shadow-lg'
              : 'text-gray-300 hover:bg-gray-700'"
          >
            <i :class="item.icon + ' w-5 text-center text-lg'"></i>
            <!-- Render label only jika sidebar terbuka -->
            <span v-if="!collapsed" class="ml-3 text-sm">{{ item.label }}</span>

            <!-- Tooltip untuk collapsed -->
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
  </aside>
</template>

<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  collapsed: { type: Boolean, default: false },
  menu: { type: Array, required: true }
})

const menuItems = ref(props.menu)

// Update active menu otomatis
const { url } = usePage()
const updateActiveState = () => {
  menuItems.value.forEach(item => {
    item.active = url.startsWith(item.url)
  })
}
updateActiveState()
watch(() => url, () => updateActiveState())
</script>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active { transition: all 0.3s ease; }
.fade-slide-enter-from, .fade-slide-leave-to { opacity:0; transform: translateX(-10px); }
</style>
