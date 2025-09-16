<template>
  <InvestorLayout :user="user" pageTitle="Karir & Referral">
    <div class="max-w-4xl mx-auto py-6 space-y-6">
      <h1 class="text-3xl font-bold">Karir & Referral</h1>

      <!-- Kode referral & Link -->
      <div class="bg-white shadow rounded-lg p-6 flex flex-col md:flex-row md:items-center md:space-x-6">
        <div class="flex-1">
          <h2 class="text-xl font-semibold mb-2">Kode Referral Saya</h2>
          <div class="flex items-center space-x-2">
            <input type="text" :value="referralCode" readonly class="border px-3 py-2 rounded w-full" />
            <button @click="copyCode" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
              Salin
            </button>
          </div>

          <p class="mt-2 text-gray-600">
            Link Referral: 
            <a :href="referralLink" class="text-blue-600 underline" target="_blank">{{ referralLink }}</a>
          </p>
        </div>

        <!-- QR Code -->
        <div class="mt-4 md:mt-0">
          <qrcode-vue :value="referralLink" :size="150" />
        </div>
      </div>

      <!-- Statistik referral -->
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Statistik Referral</h2>
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b">
              <th class="px-4 py-2">Level</th>
              <th class="px-4 py-2">Jumlah Orang</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(count, level) in referralSummary" :key="level" class="border-b hover:bg-gray-50">
              <td class="px-4 py-2">Level {{ level }}</td>
              <td class="px-4 py-2">{{ count }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </InvestorLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import InvestorLayout from '@/Layouts/InvestorLayout.vue'
import axios from 'axios'
import QrcodeVue from 'qrcode.vue'

const { props: pageProps } = usePage()
const user = pageProps.auth?.user || {}

const referralCode = ref(pageProps.referralCode || '')

// Link referral berbasis kode
const referralLink = computed(() => `${window.location.origin}/register?ref=${referralCode.value}`)

// summary referral per level
const referralSummary = ref({})

onMounted(async () => {
  try {
    const res = await axios.get('/api/referrals-summary', {
      headers: { 'Accept': 'application/json' },
      withCredentials: true
    })
    referralSummary.value = res.data
  } catch (err) {
    console.error(err)
  }
})

const copyCode = () => {
  navigator.clipboard.writeText(referralCode.value)
  alert('Kode referral disalin!')
}
</script>

<style scoped>
table th, table td { border: 1px solid #e5e7eb; }
</style>