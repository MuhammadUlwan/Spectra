<template>
  <header class="bg-white shadow-md px-6 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">Spectra</h1>
  </header>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const user = page.props.auth?.user || { name: "Guest" };

const userInitial = computed(() => user.name?.charAt(0).toUpperCase() || "U");
</script>
