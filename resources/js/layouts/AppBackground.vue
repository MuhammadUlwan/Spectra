<template>
  <div :class="['min-h-screen flex flex-col', bgClass]">
    <!-- Konten halaman -->
    <slot />
  </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
  bgClass: {
    type: String,
    default: "bg-gradient-to-r from-blue-600 to-green-400", 
  },
});
</script>
