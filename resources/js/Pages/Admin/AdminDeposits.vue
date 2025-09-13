<template>
  <AdminLayout title="Manajemen Deposit" :activeLink="activeLink">
    <div class="space-y-6">
      <!-- Header -->
      <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
          <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Manajemen Deposit</h1>
            <p class="text-gray-600 mt-1 text-sm sm:text-base">Kelola semua transaksi deposit sistem Spectra</p>
          </div>
        </div>

        <!-- Filter & Search -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
          <!-- Search -->
          <div class="md:col-span-2">
            <div class="relative">
              <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
              <input
                v-model="search"
                type="text"
                placeholder="Cari nama atau email investor..."
                class="w-full pl-10 pr-4 py-2 sm:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm sm:text-base"
              />
            </div>
          </div>

          <!-- Dropdown Status -->
          <div class="relative">
            <label class="sr-only">Filter Status</label>
            <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 z-10 pointer-events-none"></i>
            <select 
              v-model="filterStatus" 
              class="w-full pl-10 pr-8 py-3 appearance-none border border-gray-300 rounded-x focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white cursor-pointer"
            >
              <option value="">Semua Status</option>
              <option value="pending">Pending</option>
              <option value="processing">Diproses</option>
              <option value="completed">Selesai</option>
              <option value="rejected">Ditolak</option>
            </select>
            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
          <div v-for="stat in statCards" :key="stat.label" :class="stat.bgClass">
            <div class="flex items-center p-3 sm:p-4">
              <div :class="['p-2 rounded-lg', stat.iconBg]">
                <i :class="[stat.icon, 'text-sm sm:text-base']"></i>
              </div>
              <div class="ml-3 sm:ml-4">
                <p class="text-xs sm:text-sm font-medium" :class="stat.textColor">{{ stat.label }}</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ stat.value }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabel Deposit Container -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <!-- Tabel Deposit -->
        <div class="overflow-hidden">
          <div class="overflow-x-auto max-h-[calc(100vh-300px)]">
            <table class="w-full min-w-full">
              <thead class="bg-gray-50 sticky top-0 z-10">
                <tr>
                  <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                  <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Investor</th>
                  <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                  <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                  <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                  <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="deposit in filteredDeposits" :key="deposit.id" class="hover:bg-gray-50 transition">
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">#{{ deposit.id }}</div>
                    <div class="text-xs text-gray-500">{{ deposit.transaction_code }}</div>
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-xs sm:text-sm">
                        {{ getUserInitials(deposit.user.name) }}
                      </div>
                      <div class="ml-3 sm:ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ deposit.user.name }}</div>
                        <div class="text-xs sm:text-sm text-gray-500">{{ deposit.user.email }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ formatCurrency(deposit.amount) }}</div>
                    <div class="text-xs text-gray-500">{{ getMethodLabel(deposit.method) }}</div>
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                      {{ getMethodLabel(deposit.method) }}
                    </span>
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 rounded-full text-xs font-medium" :class="getStatusClass(deposit.status)">
                      {{ getStatusLabel(deposit.status) }}
                    </span>
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ formatDate(deposit.created_at) }}</div>
                    <div class="text-xs text-gray-500">{{ formatTime(deposit.created_at) }}</div>
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-1 sm:space-x-2">
                      <button @click="viewDetail(deposit)" class="text-blue-600 hover:text-blue-900 p-1 sm:p-2 rounded-lg hover:bg-blue-50" title="Lihat Detail">
                        <i class="fas fa-eye text-xs sm:text-sm"></i>
                      </button>
                      <button 
                        v-if="deposit.status === 'pending'" 
                        @click="validateDeposit(deposit)" 
                        class="text-green-600 hover:text-green-900 p-1 sm:p-2 rounded-lg hover:bg-green-50" 
                        title="Validasi"
                      >
                        <i class="fas fa-check-circle text-xs sm:text-sm"></i>
                      </button>
                      <button 
                        v-if="deposit.status === 'pending'" 
                        @click="confirmManual(deposit)" 
                        class="text-yellow-600 hover:text-yellow-900 p-1 sm:p-2 rounded-lg hover:bg-yellow-50" 
                        title="Konfirmasi Manual"
                      >
                        <i class="fas fa-hand-holding-usd text-xs sm:text-sm"></i>
                      </button>
                      <button 
                        v-if="deposit.status === 'pending'" 
                        @click="rejectDeposit(deposit)" 
                        class="text-red-600 hover:text-red-900 p-1 sm:p-2 rounded-lg hover:bg-red-50" 
                        title="Tolak"
                      >
                        <i class="fas fa-times-circle text-xs sm:text-sm"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Empty State -->
          <div v-if="filteredDeposits.length === 0" class="text-center py-8 sm:py-12">
            <i class="fas fa-money-bill-wave text-3xl sm:text-4xl text-gray-300 mb-3 sm:mb-4"></i>
            <p class="text-gray-500 text-base sm:text-lg">Tidak ada deposit ditemukan</p>
            <p class="text-gray-400 text-xs sm:text-sm mt-1">Coba ubah filter pencarian</p>
          </div>

          <!-- Pagination -->
          <div v-if="pagination" class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
            <div class="text-xs sm:text-sm text-gray-700">
              Menampilkan {{ pagination.from }} - {{ pagination.to }} dari {{ pagination.total }} deposit
            </div>
            <div class="flex space-x-2">
              <button @click="prevPage" :disabled="!pagination.prev_page_url" class="px-2 sm:px-3 py-1 rounded-lg border text-xs sm:text-sm transition-colors" :class="!pagination.prev_page_url ? 'opacity-50 cursor-not-allowed text-gray-400' : 'text-gray-700 hover:bg-gray-200'">
                Sebelumnya
              </button>
              <button @click="nextPage" :disabled="!pagination.next_page_url" class="px-2 sm:px-3 py-1 rounded-lg border text-xs sm:text-sm transition-colors" :class="!pagination.next_page_url ? 'opacity-50 cursor-not-allowed text-gray-400' : 'text-gray-700 hover:bg-gray-200'">
                Selanjutnya
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const { props } = usePage()
const activeLink = ref('/admin/deposits')

// State
const deposits = ref([])
const stats = ref({})
const search = ref('')
const filterStatus = ref('')
const filterMethod = ref('')
const pagination = ref(null)
const currentPage = ref(1)
const availableMethods = ref([])

// Cards stat auto build
const statCards = computed(() => [
  {
    label: 'Total Deposit',
    value: formatNumber(stats.value.totalDeposits || 0),
    icon: 'fas fa-money-bill-wave text-blue-600',
    bgClass: 'bg-blue-50 rounded-xl border border-blue-100',
    iconBg: 'bg-blue-100',
    textColor: 'text-blue-600'
  },
  {
    label: 'Pending',
    value: formatNumber(stats.value.pendingDeposits || 0),
    icon: 'fas fa-clock text-yellow-600',
    bgClass: 'bg-yellow-50 rounded-xl border border-yellow-100',
    iconBg: 'bg-yellow-100',
    textColor: 'text-yellow-600'
  },
  {
    label: 'Diproses',
    value: formatNumber(stats.value.processingDeposits || 0),
    icon: 'fas fa-cog text-purple-600',
    bgClass: 'bg-purple-50 rounded-xl border border-purple-100',
    iconBg: 'bg-purple-100',
    textColor: 'text-purple-600'
  },
  {
    label: 'Selesai',
    value: formatNumber(stats.value.completedDeposits || 0),
    icon: 'fas fa-check-circle text-green-600',
    bgClass: 'bg-green-50 rounded-xl border border-green-100',
    iconBg: 'bg-green-100',
    textColor: 'text-green-600'
  }
])

// Computed
const filteredDeposits = computed(() => {
  return deposits.value.filter(d => {
    const matchSearch = !search.value || 
      d.user.name.toLowerCase().includes(search.value.toLowerCase()) ||
      d.user.email.toLowerCase().includes(search.value.toLowerCase()) ||
      d.transaction_code.toLowerCase().includes(search.value.toLowerCase())
    
    const matchStatus = !filterStatus.value || d.status === filterStatus.value
    const matchMethod = !filterMethod.value || d.method === filterMethod.value
    
    return matchSearch && matchStatus && matchMethod
  })
})

// Helpers
const formatNumber = num => new Intl.NumberFormat('id-ID').format(num)
const formatCurrency = amount => new Intl.NumberFormat('id-ID', {
  style: 'currency',
  currency: 'IDR',
  minimumFractionDigits: 0
}).format(amount)

const formatDate = dateString => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  }).format(date)
}

const formatTime = dateString => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('id-ID', {
    hour: '2-digit',
    minute: '2-digit'
  }).format(date)
}

const getUserInitials = name => {
  if (!name) return 'NN'
  return name.split(' ').map(word => word[0]).join('').toUpperCase().substring(0, 2)
}

const getStatusClass = status => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    processing: 'bg-purple-100 text-purple-800',
    completed: 'bg-green-100 text-green-800',
    rejected: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusLabel = status => {
  const labels = {
    pending: 'Pending',
    processing: 'Diproses',
    completed: 'Selesai',
    rejected: 'Ditolak'
  }
  return labels[status] || status
}

const getMethodLabel = method => {
  const labels = {
    bank_transfer: 'Transfer Bank',
    e_wallet: 'E-Wallet',
    credit_card: 'Kartu Kredit',
    manual: 'Manual'
  }
  return labels[method] || method
}

// Actions
const fetchDeposits = async (page = 1) => {
  try {
    const res = await fetch(`/api/admin/deposits?page=${page}`)
    if (res.ok) {
      const data = await res.json()
      deposits.value = data.data
      pagination.value = data.meta
      stats.value = data.stats
      availableMethods.value = data.availableMethods || []
    }
  } catch (error) {
    console.error('Gagal mengambil data deposit:', error)
  }
}

const viewDetail = deposit => {
  window.location.href = `/admin/deposits/${deposit.id}`
}

const validateDeposit = async deposit => {
  try {
    const res = await fetch(`/api/admin/deposits/${deposit.id}/validate`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      }
    })
    
    if (res.ok) {
      deposit.status = 'processing'
    }
  } catch (error) {
    console.error('Gagal memvalidasi deposit:', error)
  }
}

const confirmManual = async deposit => {
  try {
    const res = await fetch(`/api/admin/deposits/${deposit.id}/manual-confirm`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      }
    })
    
    if (res.ok) {
      deposit.status = 'completed'
    }
  } catch (error) {
    console.error('Gagal mengonfirmasi manual:', error)
  }
}

const rejectDeposit = async deposit => {
  if (confirm('Apakah Anda yakin ingin menolak deposit ini?')) {
    try {
      const res = await fetch(`/api/admin/deposits/${deposit.id}/reject`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        }
      })
      
      if (res.ok) {
        deposit.status = 'rejected'
      }
    } catch (error) {
      console.error('Gagal menolak deposit:', error)
    }
  }
}

const nextPage = () => {
  if (pagination.value?.next_page_url) {
    currentPage.value++
    fetchDeposits(currentPage.value)
  }
}

const prevPage = () => {
  if (pagination.value?.prev_page_url) {
    currentPage.value--
    fetchDeposits(currentPage.value)
  }
}

// Init
onMounted(() => fetchDeposits())
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

/* Custom styling for better mobile experience */
@media (max-width: 640px) {
  .overflow-x-auto {
    -webkit-overflow-scrolling: touch;
    overflow-x: auto;
  }
  
  table {
    min-width: 800px; /* Force horizontal scroll on small screens */
  }
  
  /* Fix for mobile scrolling */
  .max-h-\[calc\(100vh-300px\)\] {
    max-height: calc(100vh - 300px) !important;
  }
}

/* Ensure proper scrolling behavior */
.overflow-x-auto {
  overflow-x: auto;
  overflow-y: auto;
}

/* Sticky header for better UX */
.sticky {
  position: sticky;
  z-index: 10;
}
</style>