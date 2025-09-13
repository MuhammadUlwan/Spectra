<template>
  <AppLayout>
    <div class="bg-gradient-to-b from-blue-50 to-gray-100 py-4 px-3 sm:py-6 sm:px-4 lg:py-8 lg:px-6">
      <!-- Header Section -->
      <div class="max-w-6xl mx-auto mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start gap-3 sm:gap-4">
          <div class="flex-1 min-w-0">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 truncate">Dashboard Konsultan</h1>
            <p class="text-gray-600 mt-1 sm:mt-2 text-sm sm:text-base">Kelola dan pantau performa afiliasi Anda</p>
          </div>
          <div class="flex-shrink-0 w-full sm:w-auto mt-3 sm:mt-0">
            <button class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg font-medium flex items-center justify-center transition-colors text-sm sm:text-base">
              <i class="fas fa-download mr-2 text-sm"></i> Material Promo
            </button>
          </div>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="max-w-6xl mx-auto grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <!-- Total Komisi -->
        <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-5 shadow-sm sm:shadow-md border border-gray-200">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-gray-500 font-medium text-sm sm:text-base">Total Komisi</h3>
            <div class="bg-blue-100 p-1.5 sm:p-2 rounded-md sm:rounded-lg">
              <i class="fas fa-coins text-blue-600 text-sm sm:text-base"></i>
            </div>
          </div>
          <p class="text-xl sm:text-2xl font-bold text-gray-800 truncate">USD {{ formatCurrency(careerData.stats.total_commission) }}</p>
        </div>

        <!-- Jumlah Referral -->
        <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-5 shadow-sm sm:shadow-md border border-gray-200">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-gray-500 font-medium text-sm sm:text-base">Jumlah Referral</h3>
            <div class="bg-green-100 p-1.5 sm:p-2 rounded-md sm:rounded-lg">
              <i class="fas fa-users text-green-600 text-sm sm:text-base"></i>
            </div>
          </div>
          <div class="flex justify-between space-x-2">
            <div class="text-center flex-1">
              <p class="text-xs sm:text-sm text-gray-600 mb-1">Level 1</p>
              <p class="text-lg sm:text-xl font-bold text-gray-800">{{ careerData.stats.referrals.level1 }}</p>
            </div>
            <div class="text-center flex-1">
              <p class="text-xs sm:text-sm text-gray-600 mb-1">Level 2</p>
              <p class="text-lg sm:text-xl font-bold text-gray-800">{{ careerData.stats.referrals.level2 }}</p>
            </div>
          </div>
        </div>

        <!-- Omset Direct -->
        <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-5 shadow-sm sm:shadow-md border border-gray-200">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-gray-500 font-medium text-sm sm:text-base">Omset Direct</h3>
            <div class="bg-purple-100 p-1.5 sm:p-2 rounded-md sm:rounded-lg">
              <i class="fas fa-chart-line text-purple-600 text-sm sm:text-base"></i>
            </div>
          </div>
          <p class="text-xl sm:text-2xl font-bold text-gray-800 truncate">USD {{ formatCurrency(careerData.stats.direct_volume) }}</p>
        </div>

        <!-- Status Bonus -->
        <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-5 shadow-sm sm:shadow-md border border-gray-200">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-gray-500 font-medium text-sm sm:text-base">Status Bonus</h3>
            <div class="bg-amber-100 p-1.5 sm:p-2 rounded-md sm:rounded-lg">
              <i class="fas fa-gift text-amber-600 text-sm sm:text-base"></i>
            </div>
          </div>
          <div :class="['inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium', 
            careerData.stats.bonus_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800']">
            <i :class="['fas mr-1 text-xs', careerData.stats.bonus_active ? 'fa-check-circle' : 'fa-clock']"></i>
            {{ careerData.stats.bonus_active ? 'Aktif' : 'Tidak Aktif' }}
          </div>
          <p class="text-xs text-gray-500 mt-1 sm:mt-2">Butuh USD {{ formatCurrency(careerData.commissionRates.bonus_threshold) }} omset direct</p>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
          <!-- Link Referral Section -->
          <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 shadow-sm sm:shadow-md border border-gray-200">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Link Referral Anda</h2>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
              <div class="flex-1 bg-gray-50 p-2 sm:p-3 rounded-md sm:rounded-lg border border-gray-200">
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Salin link ini untuk dibagikan</p>
                <p class="font-mono text-blue-600 break-all text-xs sm:text-sm">{{ careerData.referralLink }}</p>
              </div>
              <button @click="copyLink" class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 sm:py-3 rounded-md sm:rounded-lg font-medium flex items-center justify-center transition-colors text-xs sm:text-sm">
                <i class="far fa-copy mr-1 sm:mr-2 text-xs sm:text-sm"></i> Salin
              </button>
            </div>
            <div class="flex gap-2 sm:gap-3 mt-3 sm:mt-4">
              <button class="flex-1 bg-green-100 hover:bg-green-200 text-green-800 px-3 py-2 rounded-md sm:rounded-lg font-medium flex items-center justify-center transition-colors text-xs sm:text-sm">
                <i class="fab fa-whatsapp mr-1 sm:mr-2 text-xs sm:text-sm"></i> WhatsApp
              </button>
              <button class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-2 rounded-md sm:rounded-lg font-medium flex items-center justify-center transition-colors text-xs sm:text-sm">
                <i class="far fa-envelope mr-1 sm:mr-2 text-xs sm:text-sm"></i> Email
              </button>
            </div>
          </div>

          <!-- Riwayat Komisi Section -->
          <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 shadow-sm sm:shadow-md border border-gray-200">
            <div class="flex justify-between items-center mb-4 sm:mb-6">
              <h2 class="text-lg sm:text-xl font-bold text-gray-800">Riwayat Komisi</h2>
              <button class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">Lihat Semua</button>
            </div>
            
            <div v-if="careerData.commissionHistory.length > 0" class="overflow-x-auto">
              <table class="w-full min-w-[500px] sm:min-w-full">
                <thead>
                  <tr class="border-b border-gray-200">
                    <th class="pb-2 sm:pb-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="pb-2 sm:pb-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sumber</th>
                    <th class="pb-2 sm:pb-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="pb-2 sm:pb-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="(history, index) in careerData.commissionHistory" :key="index">
                    <td class="py-3 sm:py-4 whitespace-nowrap">
                      <div class="text-xs sm:text-sm text-gray-900">{{ history.date }}</div>
                    </td>
                    <td class="py-3 sm:py-4">
                      <div class="text-xs sm:text-sm text-gray-900">{{ history.source }}</div>
                    </td>
                    <td class="py-3 sm:py-4 whitespace-nowrap">
                      <div class="text-xs sm:text-sm font-medium text-gray-900">USD {{ formatCurrency(history.amount) }}</div>
                    </td>
                    <td class="py-3 sm:py-4 whitespace-nowrap">
                      <span :class="`inline-flex px-2 py-0.5 sm:px-2.5 sm:py-0.5 rounded-full text-xs font-medium ${getStatusClass(history.status)}`">
                        {{ history.status }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-else class="text-center py-6 sm:py-8">
              <i class="fas fa-history text-gray-300 text-2xl sm:text-3xl mb-2 sm:mb-3"></i>
              <p class="text-gray-500 text-sm sm:text-base">Belum ada riwayat komisi</p>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-4 sm:space-y-6">
          <!-- Ringkasan Komisi Section -->
          <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 shadow-sm sm:shadow-md border border-gray-200">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Ringkasan Komisi</h2>
            
            <div class="space-y-3 sm:space-y-4">
              <div>
                <h3 class="font-medium text-gray-700 mb-1.5 sm:mb-2 text-sm sm:text-base">Sponsor Fee</h3>
                <div class="bg-blue-50 p-2 sm:p-3 rounded-md sm:rounded-lg mb-1.5 sm:mb-2">
                  <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm text-gray-600">Level 1 (Direct)</span>
                    <span class="font-medium text-blue-700 text-xs sm:text-sm">{{ careerData.commissionRates.sponsor_fee.level1 }}%</span>
                  </div>
                </div>
                <div class="bg-blue-50 p-2 sm:p-3 rounded-md sm:rounded-lg">
                  <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm text-gray-600">Level 2 (Indirect)</span>
                    <span class="font-medium text-blue-700 text-xs sm:text-sm">{{ careerData.commissionRates.sponsor_fee.level2 }}%</span>
                  </div>
                </div>
              </div>

              <div>
                <h3 class="font-medium text-gray-700 mb-1.5 sm:mb-2 text-sm sm:text-base">Profit Sharing</h3>
                <div class="bg-green-50 p-2 sm:p-3 rounded-md sm:rounded-lg mb-1.5 sm:mb-2">
                  <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm text-gray-600">Level 1</span>
                    <span class="font-medium text-green-700 text-xs sm:text-sm">{{ careerData.commissionRates.profit_sharing.level1 }}%</span>
                  </div>
                </div>
                <div class="bg-green-50 p-2 sm:p-3 rounded-md sm:rounded-lg mb-1.5 sm:mb-2">
                  <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm text-gray-600">Level 2</span>
                    <span class="font-medium text-green-700 text-xs sm:text-sm">{{ careerData.commissionRates.profit_sharing.level2 }}%</span>
                  </div>
                </div>
                <div class="bg-green-50 p-2 sm:p-3 rounded-md sm:rounded-lg">
                  <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm text-gray-600">Level 3</span>
                    <span class="font-medium text-green-700 text-xs sm:text-sm">{{ careerData.commissionRates.profit_sharing.level3 }}%</span>
                  </div>
                </div>
              </div>

              <div>
                <h3 class="font-medium text-gray-700 mb-1.5 sm:mb-2 text-sm sm:text-base">Bonus Omset</h3>
                <div class="bg-amber-50 p-2 sm:p-3 rounded-md sm:rounded-lg">
                  <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm text-gray-600">Tambahan Profit Sharing</span>
                    <span class="font-medium text-amber-700 text-xs sm:text-sm">+{{ careerData.commissionRates.bonus_rate }}%</span>
                  </div>
                  <p class="text-xs text-gray-500 mt-1">Jika omset direct ≥ USD {{ formatCurrency(careerData.commissionRates.bonus_threshold) }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Progress Omset Section -->
          <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 shadow-sm sm:shadow-md border border-gray-200">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Progress Omset Direct</h2>
            
            <div class="mb-1.5 sm:mb-2 flex justify-between">
              <span class="text-xs sm:text-sm text-gray-600">Tercapai</span>
              <span class="text-xs sm:text-sm font-medium text-gray-700">USD {{ formatCurrency(careerData.stats.direct_volume) }} / USD {{ formatCurrency(careerData.commissionRates.bonus_threshold) }}</span>
            </div>
            
            <div class="w-full bg-gray-200 rounded-full h-2 sm:h-2.5 mb-3 sm:mb-4">
              <div 
                class="bg-blue-600 h-full rounded-full transition-all duration-300" 
                :style="`width: ${Math.min((careerData.stats.direct_volume / careerData.commissionRates.bonus_threshold) * 100, 100)}%`"
              ></div>
            </div>
            
            <p class="text-xs sm:text-sm text-gray-600">
              {{ careerData.stats.bonus_active ? '✅ Bonus omset aktif' : '⏳ Butuh ' + formatCurrency(careerData.commissionRates.bonus_threshold - careerData.stats.direct_volume) + ' lagi untuk bonus' }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const { props } = usePage()

// Data dari backend melalui props
const careerData = computed(() => props.careerData || {
  stats: {
    total_commission: 0,
    referrals: {
      level1: 0,
      level2: 0
    },
    direct_volume: 0,
    bonus_active: false
  },
  commissionHistory: [],
  referralLink: '',
  commissionRates: {
    sponsor_fee: {
      level1: 7.5,
      level2: 2.5
    },
    profit_sharing: {
      level1: 1.25,
      level2: 0.75,
      level3: 0.5
    },
    bonus_threshold: 10000,
    bonus_rate: 1.25
  }
})

// Methods
function formatCurrency(amount) {
  return new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount)
}

function getStatusClass(status) {
  const statusClasses = {
    'Completed': 'bg-green-100 text-green-800',
    'Pending': 'bg-yellow-100 text-yellow-800',
    'Processing': 'bg-blue-100 text-blue-800'
  }
  return statusClasses[status] || 'bg-gray-100 text-gray-800'
}

function copyLink() {
  navigator.clipboard.writeText(careerData.value.referralLink)
  alert('Link referral berhasil disalin!')
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

/* Responsive breakpoints */
@media (max-width: 475px) {
  .xs\:grid-cols-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
</style>