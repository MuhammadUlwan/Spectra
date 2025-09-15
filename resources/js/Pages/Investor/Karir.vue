<template>
  <InvestorLayout :user="user" pageTitle="Karir & Referral">
    <div class="max-w-4xl mx-auto py-6">
      <h1 class="text-3xl font-bold mb-4">Karir & Referral</h1>

      <!-- Kode referral -->
      <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-2">Kode Referral Saya</h2>
        <div class="flex items-center space-x-2">
          <input type="text" :value="referralCode" readonly class="border px-3 py-2 rounded w-full" />
          <button @click="copyCode" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Salin
          </button>
        </div>
      </div>

      <!-- Statistik referral -->
      <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Riwayat Referral</h2>
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b">
              <th class="px-4 py-2">Referral ID</th>
              <th class="px-4 py-2">Level</th>
              <th class="px-4 py-2">Tanggal Bergabung</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="ref in referrals" :key="ref.id" class="border-b hover:bg-gray-50">
              <td class="px-4 py-2">{{ ref.referral_id }}</td>
              <td class="px-4 py-2">{{ ref.level }}</td>
              <td class="px-4 py-2">{{ formatDate(ref.created_at) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </InvestorLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import InvestorLayout from '@/Layouts/InvestorLayout.vue'

const { props: pageProps } = usePage()
const user = pageProps.auth?.user || {}

const referralCode = ref(pageProps.referralCode || '')
const referrals = ref([])

onMounted(async () => {
  try {
    const res = await axios.get('/api/referrals')
    referralCode.value = res.data.code
    referrals.value = res.data.list
  } catch (err) {
    console.error(err)
  }
})

const copyCode = () => {
  navigator.clipboard.writeText(referralCode.value)
  alert('Kode referral disalin!')
}

const formatDate = (dateStr) => {
  const date = new Date(dateStr)
  return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })
}
</script>

<style scoped>
table th, table td { border: 1px solid #e5e7eb; }
</style>
