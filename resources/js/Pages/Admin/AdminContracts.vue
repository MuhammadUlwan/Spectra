<template>
  <AdminLayout title="Manajemen Kontrak" :activeLink="activeLink">
    <div class="space-y-6">
      <!-- Header -->
      <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Kontrak</h1>
            <p class="text-gray-600 mt-1">Kelola semua kontrak investasi sistem Spectra</p>
          </div>
        </div>

        <!-- Filter & Search -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
          <!-- Search -->
          <div class="lg:col-span-2">
            <div class="relative">
              <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
              <input
                v-model="search"
                type="text"
                placeholder="Cari investor atau paket..."
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
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
              <option value="active">Aktif</option>
              <option value="pending">Menunggu</option>
              <option value="completed">Selesai</option>
              <option value="terminated">Dihentikan</option>
            </select>
            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
          </div>

          <!-- Dropdown Paket -->
          <div class="relative">
            <label class="sr-only">Filter Paket</label>
            <i class="fas fa-cube absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 z-10 pointer-events-none"></i>
            <select 
              v-model="filterPackage" 
              class="w-full pl-10 pr-8 py-3 appearance-none border border-gray-300 rounded-x focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white cursor-pointer"
            >
              <option value="">Semua Paket</option>
              <option v-for="pkg in availablePackages" :key="pkg.id" :value="pkg.id">
                {{ pkg.name }}
              </option>
            </select>
            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
          <div v-for="stat in statCards" :key="stat.label" :class="stat.bgClass">
            <div class="flex items-center p-4">
              <div :class="['p-2 rounded-lg', stat.iconBg]">
                <i :class="[stat.icon, 'text-sm sm:text-base']"></i>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium" :class="stat.textColor">{{ stat.label }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ stat.value }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full min-w-[600px]">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontrak</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Investor</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="contract in filteredContracts" :key="contract.id" class="hover:bg-gray-50 transition">
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">#{{ contract.id }}</div>
                    <div class="text-xs text-gray-500">{{ contract.contract_code }}</div>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-xs sm:text-sm">
                        {{ getUserInitials(contract.investor_name) }}
                      </div>
                      <div class="ml-3 min-w-0">
                        <div class="text-sm font-medium text-gray-900 truncate">{{ contract.investor_name }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ contract.investor_email }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ contract.package_name }}</div>
                    <div class="text-xs text-gray-500">{{ formatCurrency(contract.investment_amount) }}</div>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ formatDate(contract.start_date) }}</div>
                    <div class="text-xs text-gray-500">s/d {{ formatDate(contract.end_date) }}</div>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <span class="px-2 py-1 rounded-full text-xs font-medium" :class="getStatusClass(contract.status)">
                      {{ getStatusLabel(contract.status) }}
                    </span>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                      <button @click="viewDetail(contract)" class="text-blue-600 hover:text-blue-900 p-1.5 rounded-lg hover:bg-blue-50 transition" title="Lihat Detail">
                        <i class="fas fa-eye text-sm"></i>
                      </button>
                      <button @click="monitorProfit(contract)" class="text-green-600 hover:text-green-900 p-1.5 rounded-lg hover:bg-green-50 transition" title="Monitor Profit">
                        <i class="fas fa-chart-line text-sm"></i>
                      </button>
                      <button @click="editContract(contract)" class="text-purple-600 hover:text-purple-900 p-1.5 rounded-lg hover:bg-purple-50 transition" title="Edit">
                        <i class="fas fa-edit text-sm"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Empty State -->
          <div v-if="filteredContracts.length === 0" class="text-center py-12">
            <i class="fas fa-file-contract text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Tidak ada kontrak ditemukan</p>
            <p class="text-gray-400 text-sm mt-1">Coba ubah filter pencarian</p>
          </div>

          <!-- Pagination -->
          <div v-if="pagination" class="px-4 py-3 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
              <div class="text-sm text-gray-700">
                Menampilkan {{ pagination.from }} - {{ pagination.to }} dari {{ pagination.total }} kontrak
              </div>
              <div class="flex space-x-2">
                <button 
                  @click="prevPage" 
                  :disabled="!pagination.prev_page_url" 
                  class="px-3 py-1.5 rounded-lg border border-gray-300 text-sm transition"
                  :class="!pagination.prev_page_url ? 'opacity-50 cursor-not-allowed text-gray-400' : 'hover:bg-gray-100 text-gray-700'">
                  <i class="fas fa-chevron-left mr-1 text-xs"></i> Sebelumnya
                </button>
                <button 
                  @click="nextPage" 
                  :disabled="!pagination.next_page_url" 
                  class="px-3 py-1.5 rounded-lg border border-gray-300 text-sm transition"
                  :class="!pagination.next_page_url ? 'opacity-50 cursor-not-allowed text-gray-400' : 'hover:bg-gray-100 text-gray-700'">
                  Selanjutnya <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </button>
              </div>
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
const activeLink = ref('/admin/contracts')

// State
const contracts = ref([])
const stats = ref({})
const search = ref('')
const filterStatus = ref('')
const filterPackage = ref('')
const pagination = ref(null)
const currentPage = ref(1)
const availablePackages = ref([])

// Cards stat auto build
const statCards = computed(() => [
  {
    label: 'Total Kontrak',
    value: formatNumber(stats.value.totalContracts || 0),
    icon: 'fas fa-file-contract text-blue-600',
    bgClass: 'bg-blue-50 rounded-xl p-4 border border-blue-100',
    iconBg: 'bg-blue-100',
    textColor: 'text-blue-600'
  },
  {
    label: 'Kontrak Aktif',
    value: formatNumber(stats.value.activeContracts || 0),
    icon: 'fas fa-check-circle text-green-600',
    bgClass: 'bg-green-50 rounded-xl p-4 border border-green-100',
    iconBg: 'bg-green-100',
    textColor: 'text-green-600'
  },
  {
    label: 'Total Investasi',
    value: formatCurrency(stats.value.totalInvestment || 0),
    icon: 'fas fa-coins text-yellow-600',
    bgClass: 'bg-yellow-50 rounded-xl p-4 border border-yellow-100',
    iconBg: 'bg-yellow-100',
    textColor: 'text-yellow-600'
  },
  {
    label: 'Profit Dibagikan',
    value: formatCurrency(stats.value.totalProfit || 0),
    icon: 'fas fa-chart-line text-purple-600',
    bgClass: 'bg-purple-50 rounded-xl p-4 border border-purple-100',
    iconBg: 'bg-purple-100',
    textColor: 'text-purple-600'
  }
])

// Computed
const filteredContracts = computed(() => {
  return contracts.value.filter(c => {
    const matchSearch = !search.value || 
      c.investor_name.toLowerCase().includes(search.value.toLowerCase()) ||
      c.package_name.toLowerCase().includes(search.value.toLowerCase()) ||
      c.contract_code.toLowerCase().includes(search.value.toLowerCase())
    
    const matchStatus = !filterStatus.value || c.status === filterStatus.value
    const matchPackage = !filterPackage.value || c.package_id == filterPackage.value
    
    return matchSearch && matchStatus && matchPackage
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

const getUserInitials = name => {
  if (!name) return 'NN'
  return name.split(' ').map(word => word[0]).join('').toUpperCase().substring(0, 2)
}

const getStatusClass = status => {
  const classes = {
    active: 'bg-green-100 text-green-700',
    pending: 'bg-yellow-100 text-yellow-700',
    completed: 'bg-blue-100 text-blue-700',
    terminated: 'bg-red-100 text-red-700'
  }
  return classes[status] || 'bg-gray-100 text-gray-700'
}

const getStatusLabel = status => {
  const labels = {
    active: 'Aktif',
    pending: 'Menunggu',
    completed: 'Selesai',
    terminated: 'Dihentikan'
  }
  return labels[status] || status
}

// Actions
const fetchContracts = async (page = 1) => {
  try {
    const res = await fetch(`/api/admin/contracts?page=${page}`)
    if (res.ok) {
      const data = await res.json()
      contracts.value = data.data
      pagination.value = data.meta
      stats.value = data.stats
      availablePackages.value = data.availablePackages || []
    }
  } catch (e) {
    console.error('Gagal mengambil data kontrak:', e)
  }
}

const viewDetail = contract => {
  window.location.href = `/admin/contracts/${contract.id}`
}

const editContract = contract => {
  window.location.href = `/admin/contracts/${contract.id}/edit`
}

const monitorProfit = contract => {
  window.location.href = `/admin/contracts/${contract.id}/profit`
}

const nextPage = () => {
  if (pagination.value?.next_page_url) {
    currentPage.value++
    fetchContracts(currentPage.value)
  }
}

const prevPage = () => {
  if (pagination.value?.prev_page_url) {
    currentPage.value--
    fetchContracts(currentPage.value)
  }
}

// Init
onMounted(() => fetchContracts())
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

/* Responsive improvements */
@media (max-width: 640px) {
  .grid-cols-1 > div {
    margin-bottom: 0.5rem;
  }
  
  .px-4 { padding-left: 1rem; padding-right: 1rem; }
  .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
  .text-sm { font-size: 0.875rem; }
  .text-2xl { font-size: 1.5rem; }
  
  .relative { position: relative; z-index: 1; }
  select { font-size: 16px; -webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none; }
}

@media (max-width: 768px) {
  .min-w-\[600px\] { min-width: 100%; }
  .overflow-x-auto { overflow-x: auto; -webkit-overflow-scrolling: touch; }
}

/* Hover effects */
button:hover { transform: translateY(-1px); }

/* Smooth transition */
.transition { transition: all 0.2s ease-in-out; }
</style>
