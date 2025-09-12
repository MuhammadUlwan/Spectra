<template>
  <div :class="['min-h-screen flex flex-col', bgClass]">
    <!-- Headbar -->
    <Headbar />

    <!-- Main content -->
    <main class="flex-grow p-6">
      <slot />
    </main>

    <!-- Footbar -->
    <Footbar />
  </div>
</template>

<script setup>
import Headbar from "@/Layouts/Headbar.vue";
import Footbar from "@/Layouts/Footbar.vue";
import { defineProps } from "vue";

const props = defineProps({
  bgClass: {
    type: String,
    default: "bg-gradient-to-r from-blue-600 to-green-400", 
  },
});
</script>
