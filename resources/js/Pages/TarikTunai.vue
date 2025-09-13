<template>
  <AppLayout>
    <div class="bg-gray-50 p-4 md:p-6">
      <!-- Header -->
      <div class="w-full max-w-6xl mx-auto mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
          <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Riwayat Pencairan</h1>
          <p class="text-gray-500 text-sm md:text-base">Lihat riwayat pencairan profit dan komisi Anda</p>
        </div>
        <div class="flex items-center gap-2">
          <button
            @click="$inertia.visit('/dashboard')"
            class="bg-white hover:bg-gray-100 text-gray-800 px-4 py-2 rounded-lg text-sm flex items-center shadow-sm border border-gray-200 transition-all duration-200 hover:shadow-md"
          >
            <i class="fas fa-arrow-left mr-2"></i> Kembali
          </button>
        </div>
      </div>

      <!-- Statistik Ringkas -->
      <div class="w-full max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
          <div class="flex items-center gap-3 mb-3">
            <div class="bg-blue-100 p-3 rounded-lg">
              <i class="fas fa-coins text-blue-600"></i>
            </div>
            <span class="text-gray-600 font-medium">Total Profit Dicairkan</span>
          </div>
          <span class="text-xl md:text-2xl font-semibold text-gray-800">
            USD {{ formatCurrency(stats?.total_profit_withdrawn || 0) }}
          </span>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
          <div class="flex items-center gap-3 mb-3">
            <div class="bg-green-100 p-3 rounded-lg">
              <i class="fas fa-hand-holding-usd text-green-600"></i>
            </div>
            <span class="text-gray-600 font-medium">Total Komisi Dicairkan</span>
          </div>
          <span class="text-xl md:text-2xl font-semibold text-gray-800">
            USD {{ formatCurrency(stats?.total_commission_withdrawn || 0) }}
          </span>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
          <div class="flex items-center gap-3 mb-3">
            <div class="bg-purple-100 p-3 rounded-lg">
              <i class="fas fa-clock text-purple-600"></i>
            </div>
            <span class="text-gray-600 font-medium">Menunggu Pencairan</span>
          </div>
          <span class="text-xl md:text-2xl font-semibold text-gray-800">
            USD {{ formatCurrency(stats?.pending_withdrawal || 0) }}
          </span>
        </div>
      </div>

      <!-- Filter Section -->
      <div class="w-full max-w-6xl mx-auto bg-white rounded-xl p-4 mb-6 shadow-md border border-gray-200">
        <div class="flex flex-col md:flex-row gap-4 items-end">
          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Sumber Dana</label>
            <select
              v-model="filters.source"
              class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Semua Sumber</option>
              <option value="profit">Bagi Hasil</option>
              <option value="sponsor_level_1">Komisi Sponsor Level 1</option>
              <option value="sponsor_level_2">Komisi Sponsor Level 2</option>
              <option value="profit_sharing_level_1">Profit Sharing Level 1</option>
              <option value="profit_sharing_level_2">Profit Sharing Level 2</option>
              <option value="profit_sharing_level_3">Profit Sharing Level 3</option>
              <option value="bonus">Bonus Omset</option>
            </select>
          </div>

          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select
              v-model="filters.status"
              class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Semua Status</option>
              <option value="generated">Dijadwalkan</option>
              <option value="processed">Diproses</option>
              <option value="completed">Selesai</option>
            </select>
          </div>

          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
            <input
              type="month"
              v-model="filters.month"
              class="w-full p-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
          </div>

          <button
            @click="applyFilters"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm transition-colors"
          >
            Terapkan Filter
          </button>
        </div>
      </div>

      <!-- Tabel Riwayat Pencairan -->
      <div class="w-full max-w-6xl mx-auto bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sumber Dana</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(withdrawal, index) in withdrawals" :key="index">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ formatDate(withdrawal.date) }}</div>
                  <div class="text-sm text-gray-500">{{ withdrawal.period }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ getSourceName(withdrawal.source) }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900">{{ withdrawal.description }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">USD {{ formatCurrency(withdrawal.amount) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusClass(withdrawal.status)}`">
                    {{ getStatusText(withdrawal.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button 
                    v-if="withdrawal.status === 'completed' && withdrawal.proof_url"
                    @click="viewProof(withdrawal.proof_url)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    Lihat Bukti
                  </button>
                  <span v-else class="text-gray-400">-</span>
                </td>
              </tr>
              
              <tr v-if="withdrawals.length === 0">
                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                  Tidak ada riwayat pencairan
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
          <div class="text-sm text-gray-700">
            Menampilkan {{ pagination.from }} hingga {{ pagination.to }} dari {{ pagination.total }} hasil
          </div>
          <div class="flex space-x-2">
            <button
              @click="prevPage"
              :disabled="pagination.current_page === 1"
              :class="['px-3 py-1 rounded border', pagination.current_page === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50']"
            >
              Sebelumnya
            </button>
            <button
              @click="nextPage"
              :disabled="pagination.current_page === pagination.last_page"
              :class="['px-3 py-1 rounded border', pagination.current_page === pagination.last_page ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50']"
            >
              Selanjutnya
            </button>
          </div>
        </div>
      </div>

      <!-- Modal Bukti Transfer -->
      <div v-if="showProofModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50">
        <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
          <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-xl font-semibold text-gray-900">Bukti Transfer</h3>
            <button @click="showProofModal = false" class="text-gray-400 hover:text-gray-600">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <div class="p-6">
            <img :src="currentProofUrl" alt="Bukti Transfer" class="w-full h-auto rounded-lg">
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AppLayout from "@/Layouts/AppLayout.vue"

const { props } = usePage()

// Data dari backend
const stats = ref(props.stats || {
  total_profit_withdrawn: 0,
  total_commission_withdrawn: 0,
  pending_withdrawal: 0
})

const withdrawalsData = ref(props.withdrawals || [])
const paginationData = ref(props.pagination || {
  current_page: 1,
  last_page: 1,
  from: 0,
  to: 0,
  total: 0
})

// State lokal
const filters = ref({
  source: '',
  status: '',
  month: ''
})

const showProofModal = ref(false)
const currentProofUrl = ref('')

// Computed
const withdrawals = computed(() => {
  return withdrawalsData.value
})

const pagination = computed(() => {
  return paginationData.value
})

// Methods
function formatCurrency(amount) {
  return new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount || 0)
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  })
}

function getSourceName(source) {
  const sources = {
    'profit': 'Bagi Hasil',
    'sponsor_level_1': 'Komisi Sponsor L1',
    'sponsor_level_2': 'Komisi Sponsor L2',
    'profit_sharing_level_1': 'Profit Sharing L1',
    'profit_sharing_level_2': 'Profit Sharing L2',
    'profit_sharing_level_3': 'Profit Sharing L3',
    'bonus': 'Bonus Omset'
  }
  return sources[source] || source
}

function getStatusText(status) {
  const statuses = {
    'generated': 'Dijadwalkan',
    'processed': 'Diproses',
    'completed': 'Selesai'
  }
  return statuses[status] || status
}

function getStatusClass(status) {
  const classes = {
    'generated': 'bg-yellow-100 text-yellow-800',
    'processed': 'bg-blue-100 text-blue-800',
    'completed': 'bg-green-100 text-green-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

function viewProof(url) {
  currentProofUrl.value = url
  showProofModal.value = true
}

function applyFilters() {
  // Implementasi filter - akan memanggil API dengan filter yang dipilih
  console.log('Menerapkan filter:', filters.value)
}

function nextPage() {
  if (pagination.value.current_page < pagination.value.last_page) {
    loadPage(pagination.value.current_page + 1)
  }
}

function prevPage() {
  if (pagination.value.current_page > 1) {
    loadPage(pagination.value.current_page - 1)
  }
}

function loadPage(page) {
  // Implementasi pagination - akan memanggil API dengan halaman yang diminta
  console.log('Memuat halaman:', page)
}

onMounted(() => {
  // Set filter bulan ke bulan saat ini
  const now = new Date()
  filters.value.month = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`
})
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
</style>