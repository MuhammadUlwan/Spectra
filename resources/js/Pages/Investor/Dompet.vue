<template>
  <InvestorLayout :user="user" :profileUrl="profileUrl" :logoutUrl="logoutUrl">

    <!-- Saldo Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
      <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
        <div class="flex justify-between items-center">
          <span class="text-gray-600 font-medium">Saldo Deposit</span>
          <i class="fas fa-coins text-blue-600"></i>
        </div>
        <p class="text-xl font-bold text-gray-800 mt-2">
          USDT {{ formatCurrency(depositBalance) }}
        </p>
      </div>

      <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
        <div class="flex justify-between items-center">
          <span class="text-gray-600 font-medium">Total Profit</span>
          <i class="fas fa-chart-line text-green-600"></i>
        </div>
        <p class="text-xl font-bold text-gray-800 mt-2">
          USDT {{ formatCurrency(totalProfit) }}
        </p>
      </div>

      <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
        <div class="flex justify-between items-center">
          <span class="text-gray-600 font-medium">Extra Bonus</span>
          <i class="fas fa-gift text-yellow-600"></i>
        </div>
        <p class="text-xl font-bold text-gray-800 mt-2">
          USDT {{ formatCurrency(extraBonus) }}
        </p>
      </div>

      <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
        <div class="flex justify-between items-center">
          <span class="text-gray-600 font-medium">Sponsor Fee</span>
          <i class="fas fa-user-friends text-purple-600"></i>
        </div>
        <p class="text-xl font-bold text-gray-800 mt-2">
          USDT {{ formatCurrency(sponsorFee) }}
        </p>
      </div>
    </div>

    <!-- Riwayat Dompet -->
    <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
      <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-2">
        <h2 class="text-lg md:text-xl font-semibold text-gray-800 mb-2 md:mb-0">Riwayat Dompet</h2>

        <div class="flex gap-2 w-full md:w-auto">
          <select v-model="activeType" class="border px-4 py-2 rounded-lg flex-1 md:flex-none">
            <option :value="null">Semua</option>
            <option v-for="type in transactionTypes" :key="type" :value="type">
              {{ capitalize(type) }}
            </option>
          </select>

          <button @click="$inertia.visit('/tarik-tunai')"
                  class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm flex items-center justify-center shadow-sm transition-all duration-200">
            <i class="fas fa-money-bill-wave mr-2"></i> Withdraw
          </button>
        </div>
      </div>

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
            <tr v-for="w in filteredWallets" :key="w.id" class="border-b last:border-b-0">
              <td class="px-4 py-2">{{ formatDate(w.created_at) }}</td>
              <td class="px-4 py-2 capitalize">{{ w.type }}</td>
              <td class="px-4 py-2">USDT {{ formatCurrency(w.amount) }}</td>
              <td class="px-4 py-2">
                <span :class="statusClass(w.status)">{{ capitalize(w.status) }}</span>
              </td>
            </tr>
            <tr v-if="filteredWallets.length === 0">
              <td class="px-4 py-4 border text-center text-gray-500" colspan="4">
                Belum ada data transaksi
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </InvestorLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import InvestorLayout from '@/Layouts/InvestorLayout.vue'

const { props } = usePage()
const user = props.auth?.user || {}
const profileUrl = '/profile'
const logoutUrl = '/logout'

// Saldo
const depositBalance = ref(props.depositBalance ?? 0)
const totalProfit = ref(props.totalProfit ?? 0)
const extraBonus = ref(props.extraBonus ?? 0)
const sponsorFee = ref(props.sponsorFee ?? 0)

// Wallet riwayat
const wallets = ref(props.wallets ?? [])

// Dropdown filter tipe transaksi
const transactionTypes = ref([
  'deposit',
  'profit',
  'extra_bonus',
  'affiliate_bonus',
  'sponsor_fee',
  'withdraw'
])
const activeType = ref(null)

const filteredWallets = computed(() => {
  let data = wallets.value
  if (activeType.value) data = data.filter(w => w.type === activeType.value)
  return data
})

// Helpers
function formatCurrency(amount) {
  return new Intl.NumberFormat('id-ID').format(amount || 0)
}

function formatDate(dateStr) {
  if (!dateStr) return '-'
  const date = new Date(dateStr)
  return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' })
}

function statusClass(status) {
  switch (status) {
    case 'pending': return 'bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs'
    case 'success':
    case 'completed':
    case 'paid': return 'bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs'
    case 'failed':
    case 'reject': return 'bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs'
    default: return 'bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs'
  }
}

function capitalize(str) {
  if (!str) return ''
  return str.charAt(0).toUpperCase() + str.slice(1)
}

// Fetch realtime
async function fetchWalletBalance() {
  try {
    const res = await axios.get('/investor/wallet-balance')
    depositBalance.value = res.data.depositBalance
    totalProfit.value = res.data.totalProfit
    extraBonus.value = res.data.extraBonus
    sponsorFee.value = res.data.sponsorFee
    wallets.value = res.data.wallets ?? []
  } catch (error) {
    console.error('Gagal fetch wallet balance:', error)
  }
}

onMounted(() => {
  fetchWalletBalance()
})
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
.table-fixed td, .table-fixed th { text-align: center; word-wrap: break-word; }
.table-fixed th:nth-child(1), .table-fixed td:nth-child(1) { width: 25%; }
.table-fixed th:nth-child(2), .table-fixed td:nth-child(2) { width: 25%; }
.table-fixed th:nth-child(3), .table-fixed td:nth-child(3) { width: 25%; }
.table-fixed th:nth-child(4), .table-fixed td:nth-child(4) { width: 25%; }
</style>
