<template>
  <div class="min-h-screen bg-gray-50 p-4 md:p-6 flex flex-col items-center">

    <!-- Header -->
    <div class="w-full max-w-4xl mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Dompet Saya</h1>
        <p class="text-gray-500 text-sm md:text-base">Kelola saldo dan lihat riwayat transaksi</p>
      </div>
      <button
        @click="$inertia.visit('/deposit')"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm flex items-center shadow-sm transition-all duration-200"
      >
        <i class="fas fa-plus mr-2"></i> Deposit
      </button>
    </div>

    <!-- Saldo Card -->
    <div class="w-full max-w-4xl bg-white rounded-xl p-5 mb-6 shadow-md flex justify-between items-center border border-gray-200">
      <div class="flex items-center gap-3">
        <div class="bg-green-100 p-3 rounded-lg">
          <i class="fas fa-wallet text-green-600"></i>
        </div>
        <span class="text-gray-600 font-medium">Saldo Saat Ini</span>
      </div>
      <span class="text-xl md:text-2xl font-semibold text-gray-800">
        Rp {{ formatCurrency(user.wallet || 0) }}
      </span>
    </div>

    <!-- QR Code & Alamat Dompet -->
    <div v-if="walletAddress && qrCodeUrl"
         class="w-full max-w-4xl bg-white rounded-xl p-5 mb-6 shadow-md border border-gray-200 flex flex-col items-center">
      <h3 class="text-gray-700 font-medium mb-3">Alamat Dompet</h3>
      <div class="flex flex-col items-center gap-3">
        <img :src="qrCodeUrl" alt="QR Code Dompet" class="w-40 h-40 object-contain border rounded-lg" />
        <p class="text-sm text-gray-600 break-all text-center">{{ walletAddress }}</p>
        <button @click="copyAddress"
          class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow-sm transition-all duration-200">
          Salin Alamat
        </button>
      </div>
    </div>

    <!-- Tombol Tarik Tunai -->
    <div class="w-full max-w-4xl flex justify-end mb-4">
      <button
        @click="$inertia.visit('/tarik-tunai')"
        class="bg-white hover:bg-gray-100 text-gray-800 px-4 py-2 rounded-lg text-sm flex items-center shadow-sm border border-gray-200 transition-all duration-200 hover:shadow-md"
      >
        <i class="fas fa-money-bill-wave mr-2"></i> Tarik Tunai
      </button>
    </div>

    <!-- Riwayat Transaksi -->
    <div class="w-full max-w-4xl bg-white rounded-xl p-5 shadow-md border border-gray-200">
      <h2 class="text-lg md:text-xl font-semibold text-gray-800 mb-4">Riwayat Transaksi</h2>
      <div v-if="transactions.length === 0" class="text-gray-500 text-sm">
        Belum ada transaksi.
      </div>
      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-700">
          <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
              <th class="px-4 py-2">Tanggal</th>
              <th class="px-4 py-2">Jenis</th>
              <th class="px-4 py-2">Jumlah</th>
              <th class="px-4 py-2">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="tx in transactions" :key="tx.id" class="border-b last:border-b-0">
              <td class="px-4 py-2">{{ formatDate(tx.created_at) }}</td>
              <td class="px-4 py-2 capitalize">{{ tx.type }}</td>
              <td class="px-4 py-2">Rp {{ formatCurrency(tx.amount) }}</td>
              <td class="px-4 py-2">
                <span :class="statusClass(tx.status)">
                  {{ tx.status }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Notifikasi -->
    <transition name="fade">
      <div v-if="message" :class="[ 
        'w-full max-w-4xl mt-4 p-4 rounded-xl text-sm flex items-center',
        messageType === 'success' 
          ? 'bg-green-100 text-green-800 border border-green-200' 
          : 'bg-red-100 text-red-800 border border-red-200'
      ]">
        <i :class="[ 
          'mr-3 text-lg', 
          messageType === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle' 
        ]"></i>
        <div>
          <p class="font-medium">{{ message }}</p>
        </div>
      </div>
    </transition>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

const { props } = usePage()
const user = props.auth?.user || {}
const transactions = props.transactions || []
const walletAddress = props.walletAddress || ''
const qrCodeUrl = props.qrCodeUrl || ''

const message = ref('')
const messageType = ref('')

// Format mata uang
function formatCurrency(amount) {
  return new Intl.NumberFormat('id-ID').format(amount)
}

// Format tanggal
function formatDate(dateStr) {
  const date = new Date(dateStr)
  return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' })
}

// Status badge
function statusClass(status) {
  switch(status) {
    case 'pending':
      return 'bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs'
    case 'success':
      return 'bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs'
    case 'failed':
      return 'bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs'
    default:
      return 'bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs'
  }
}

// Salin alamat dompet
function copyAddress() {
  if (!walletAddress) return
  navigator.clipboard.writeText(walletAddress)
    .then(() => {
      message.value = 'Alamat dompet berhasil disalin!'
      messageType.value = 'success'
      setTimeout(() => message.value = '', 3000)
    })
    .catch(() => {
      message.value = 'Gagal menyalin alamat dompet'
      messageType.value = 'error'
      setTimeout(() => message.value = '', 3000)
    })
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
