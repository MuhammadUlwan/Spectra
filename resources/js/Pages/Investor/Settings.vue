<template>
  <InvestorLayout
    :user="user"
    :profileUrl="profileUrl"
    :logoutUrl="logoutUrl"
    :sidebarMenu="sidebarMenu"
    pageTitle="Pengaturan"
    :userPref="userPref"
  >
    <div class="max-w-4xl mx-auto py-6">

      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Pengaturan Investor</h1>
        <p class="mt-2 text-gray-700">Atur preferensi akun Anda di sini.</p>
      </div>

      <!-- Settings card -->
      <form @submit.prevent="submit" class="bg-white rounded-xl shadow-lg divide-y divide-gray-200 p-6 space-y-6">

        <!-- Pengaturan Umum -->
        <div>
          <h2 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-cog mr-2 text-blue-500"></i> Pengaturan Umum
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium mb-1">Bahasa</label>
              <select v-model="form.language" class="border px-3 py-2 rounded-md w-full">
                <option value="id">Indonesia</option>
                <option value="en">English</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Tema</label>
              <select v-model="form.theme" class="border px-3 py-2 rounded-md w-full">
                <option value="light">Light</option>
                <option value="dark">Dark</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Informasi Bank -->
        <div>
          <h2 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-university mr-2 text-green-500"></i> Informasi Bank
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium mb-1">Nama Bank</label>
              <input type="text" v-model="form.bank_name" placeholder="Nama bank" class="border px-3 py-2 rounded-md w-full" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Nomor Rekening</label>
              <input type="text" v-model="form.bank_number" placeholder="Nomor rekening" class="border px-3 py-2 rounded-md w-full" />
            </div>
          </div>
        </div>

        <!-- Submit -->
        <div class="pt-4">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
            Simpan Pengaturan
          </button>
        </div>

      </form>
    </div>
  </InvestorLayout>
</template>

<script setup>
import { reactive } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import InvestorLayout from '@/Layouts/InvestorLayout.vue'

// Ambil props Inertia
const { props: pageProps } = usePage()
const user = pageProps.auth?.user || {}
const userPref = pageProps.userPref || {}
const profileUrl = pageProps?.profileUrl || '/profile'
const logoutUrl = pageProps?.logoutUrl || '/logout'


// Reactive form
const form = reactive({
  language: userPref?.language || 'id',
  theme: userPref?.theme || 'light',
  bank_name: userPref?.bank_name || '',
  bank_number: userPref?.bank_number || '',
})

// Submit handler
function submit() {
  router.post('/settings/update', form)
}
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");
</style>