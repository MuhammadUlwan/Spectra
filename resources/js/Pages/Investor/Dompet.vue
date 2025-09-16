<template>
  <InvestorLayout :user="user" :profileUrl="profileUrl" :logoutUrl="logoutUrl">

    <!-- Saldo -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      <div class="bg-white rounded-xl p-5 shadow-md flex justify-between items-center border border-gray-200">
        <div class="flex items-center gap-3">
          <div class="bg-green-100 p-3 rounded-lg">
            <i class="fas fa-wallet text-green-600"></i>
          </div>
          <span class="text-gray-600 font-medium">Saldo Saat Ini</span>
        </div>
        <span class="text-xl md:text-2xl font-semibold text-gray-800">
          USDT {{ formatCurrency(user.wallet || 0) }}
        </span>
      </div>
    </div>

    <!-- Riwayat Withdrawal -->
    <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
      <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-2">
        <h2 class="text-lg md:text-xl font-semibold text-gray-800 mb-2 md:mb-0">Riwayat Withdrawal</h2>

        <div class="flex gap-2 w-full md:w-auto">
          <!-- Dropdown Filter -->
          <select v-model="activeType"
                  class="border px-4 py-2 rounded-lg flex-1 md:flex-none">
            <option :value="null">Semua</option>
            <option v-for="type in transactionTypes" :key="type" :value="type">
              {{ capitalize(type) }}
            </option>
          </select>

          <!-- Withdraw Button -->
          <button @click="$inertia.visit('/withdraw')"
                  class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm flex items-center justify-center shadow-sm transition-all duration-200">
            <i class="fas fa-money-bill-wave mr-2"></i> Withdraw
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-xl overflow-hidden table-fixed">
          <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
              <th class="px-4 py-2 border">Tanggal</th>
              <th class="px-4 py-2 border">Jenis</th>
              <th class="px-4 py-2 border">Jumlah</th>
              <th class="px-4 py-2 border">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="w in filteredWithdrawals" :key="w.id" class="border-b last:border-b-0">
              <td class="px-4 py-2">{{ formatDate(w.created_at) }}</td>
              <td class="px-4 py-2 capitalize">{{ w.type }}</td>
              <td class="px-4 py-2">USDT {{ formatCurrency(w.amount) }}</td>
              <td class="px-4 py-2">
                <span :class="statusClass(w.status)">{{ w.status }}</span>
              </td>
            </tr>
            <tr v-if="filteredWithdrawals.length === 0">
              <td class="px-4 py-4 border text-center" colspan="4">Belum ada data</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </InvestorLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import InvestorLayout from '@/Layouts/InvestorLayout.vue'

const { props } = usePage()
const user = props.auth?.user || {}
const withdrawals = props.withdrawals || []
const settings = props.settings || [] 
const profileUrl = '/profile'
const logoutUrl = '/logout'

// Softcode filter dari settings
const transactionTypes = ref(['withdraw']); 

settings.forEach(s => {
  const key = s.key_name // harus key_name, bukan key
  if (key.includes('profit_percent')) transactionTypes.value.push('profit')
  if (key.includes('profit_sharing_level')) transactionTypes.value.push('profit sharing')
  if (key.includes('bonus_profit')) transactionTypes.value.push('bonus profit')
  if (key.includes('sponsor_fee')) transactionTypes.value.push('referal qr')
})
transactionTypes.value = [...new Set(transactionTypes.value)] // unik

// Filter
const activeType = ref(null)
const filteredWithdrawals = computed(() => {
  if (!activeType.value) return withdrawals
  return withdrawals.filter(w => w.type === activeType.value)
})

// Helpers
function formatCurrency(amount) {
  return new Intl.NumberFormat('id-ID').format(amount)
}

function formatDate(dateStr) {
  const date = new Date(dateStr)
  return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' })
}

function statusClass(status) {
  switch(status) {
    case 'pending': return 'bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs'
    case 'completed': return 'bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs'
    case 'reject': return 'bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs'
    default: return 'bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs'
  }
}

function capitalize(str) {
  if (!str) return ''
  return str.charAt(0).toUpperCase() + str.slice(1)
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
.table-fixed td, .table-fixed th { text-align: center; word-wrap: break-word; }
.table-fixed th:nth-child(1), .table-fixed td:nth-child(1) { width: 25%; }
.table-fixed th:nth-child(2), .table-fixed td:nth-child(2) { width: 25%; }
.table-fixed th:nth-child(3), .table-fixed td:nth-child(3) { width: 25%; }
.table-fixed th:nth-child(4), .table-fixed td:nth-child(4) { width: 25%; }
</style>