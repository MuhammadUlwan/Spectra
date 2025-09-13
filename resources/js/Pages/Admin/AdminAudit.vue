<template>
  <AdminLayout title="Audit Log" :activeLink="activeLink">
    <div class="min-h-screen bg-gray-50 py-4 px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Audit Log</h1>
            <p class="text-gray-600 mt-1">Catatan aktivitas admin sistem Spectra</p>
          </div>
        </div>

        <!-- Filter & Search -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
          <!-- Search -->
          <div class="md:col-span-2">
            <div class="relative">
              <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
              <input
                v-model="search"
                type="text"
                placeholder="Cari admin atau aktivitas..."
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
              />
            </div>
          </div>

          <!-- Dropdown Type -->
          <div class="relative">
            <label class="sr-only">Filter Jenis</label>
            <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
            <select 
              v-model="filterType" 
              class="w-full pl-10 pr-8 py-3 appearance-none border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white cursor-pointer"
            >
              <option value="">Semua Jenis</option>
              <option value="login">Login</option>
              <option value="user_management">Manajemen User</option>
              <option value="deposit_management">Manajemen Deposit</option>
              <option value="withdrawal_management">Manajemen Withdrawal</option>
              <option value="profit_management">Manajemen Profit</option>
              <option value="system_settings">Pengaturan Sistem</option>
              <option value="other">Lainnya</option>
            </select>
            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
          </div>

          <!-- Dropdown Status -->
          <div class="relative">
            <label class="sr-only">Filter Status</label>
            <i class="fas fa-bolt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
            <select 
              v-model="filterStatus" 
              class="w-full pl-10 pr-8 py-3 appearance-none border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white cursor-pointer"
            >
              <option value="">Semua Status</option>
              <option value="success">Sukses</option>
              <option value="failed">Gagal</option>
              <option value="warning">Peringatan</option>
            </select>
            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
          <div v-for="stat in statCards" :key="stat.label" :class="stat.bgClass">
            <div class="flex items-center p-4">
              <div :class="['p-3 rounded-lg', stat.iconBg]">
                <i :class="[stat.icon, 'text-white text-lg']"></i>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium" :class="stat.textColor">{{ stat.label }}</p>
                <p class="text-xl font-bold text-gray-900">{{ stat.value }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabel Audit Log -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full min-w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="log in filteredLogs" :key="log.id" class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-sm">
                      {{ getInitials(log.admin_name) }}
                    </div>
                    <div class="ml-3 min-w-0">
                      <div class="text-sm font-medium text-gray-900 truncate">{{ log.admin_name }}</div>
                      <div class="text-xs text-gray-500 truncate">{{ log.admin_role }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 rounded-full text-xs font-medium" :class="getTypeClass(log.type)">
                    {{ getTypeLabel(log.type) }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900">{{ log.description }}</div>
                  <div v-if="log.details" class="text-xs text-gray-500 mt-1">{{ log.details }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ log.ip_address || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ formatDate(log.created_at) }}</div>
                  <div class="text-xs text-gray-500">{{ formatTime(log.created_at) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 rounded-full text-xs font-medium" :class="getStatusClass(log.status)">
                    {{ getStatusLabel(log.status) }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="filteredLogs.length === 0" class="text-center py-12">
          <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
          <p class="text-gray-500 text-lg">Tidak ada catatan audit ditemukan</p>
          <p class="text-gray-400 text-sm mt-1">Coba ubah filter pencarian</p>
        </div>

        <!-- Pagination -->
        <div v-if="pagination" class="px-6 py-4 bg-gray-50 border-t border-gray-200">
          <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
            <div class="text-sm text-gray-700">
              Menampilkan {{ pagination.from }} - {{ pagination.to }} dari {{ pagination.total }} log
            </div>
            <div class="flex space-x-2">
              <button 
                @click="prevPage" 
                :disabled="!pagination.prev_page_url" 
                class="px-3 py-1.5 rounded-lg border text-sm transition-colors" 
                :class="!pagination.prev_page_url ? 'opacity-50 cursor-not-allowed text-gray-400' : 'hover:bg-gray-100 text-gray-700'"
              >
                <i class="fas fa-chevron-left mr-1 text-xs"></i> Sebelumnya
              </button>
              <button 
                @click="nextPage" 
                :disabled="!pagination.next_page_url" 
                class="px-3 py-1.5 rounded-lg border text-sm transition-colors" 
                :class="!pagination.next_page_url ? 'opacity-50 cursor-not-allowed text-gray-400' : 'hover:bg-gray-100 text-gray-700'"
              >
                Selanjutnya <i class="fas fa-chevron-right ml-1 text-xs"></i>
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
const activeLink = ref('/admin/audit')

// State
const logs = ref([])
const stats = ref({})
const search = ref('')
const filterType = ref('')
const filterStatus = ref('')
const pagination = ref(null)
const currentPage = ref(1)

// Stat Cards
const statCards = computed(() => [
  {
    label: 'Total Aktivitas',
    value: formatNumber(stats.value.totalActivities || 0),
    icon: 'fas fa-clipboard-list text-white',
    bgClass: 'bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl',
    iconBg: 'bg-white/20',
    textColor: 'text-white/90'
  },
  {
    label: 'Aktivitas Hari Ini',
    value: formatNumber(stats.value.todayActivities || 0),
    icon: 'fas fa-calendar-day text-white',
    bgClass: 'bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl',
    iconBg: 'bg-white/20',
    textColor: 'text-white/90'
  },
  {
    label: 'Login Admin',
    value: formatNumber(stats.value.adminLogins || 0),
    icon: 'fas fa-sign-in-alt text-white',
    bgClass: 'bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl',
    iconBg: 'bg-white/20',
    textColor: 'text-white/90'
  },
  {
    label: 'Aktivitas Gagal',
    value: formatNumber(stats.value.failedActivities || 0),
    icon: 'fas fa-exclamation-triangle text-white',
    bgClass: 'bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl',
    iconBg: 'bg-white/20',
    textColor: 'text-white/90'
  }
])

// Computed
const filteredLogs = computed(() => {
  return logs.value.filter(log => {
    const matchSearch = !search.value || 
      log.admin_name.toLowerCase().includes(search.value.toLowerCase()) ||
      log.description.toLowerCase().includes(search.value.toLowerCase()) ||
      (log.details && log.details.toLowerCase().includes(search.value.toLowerCase()))
    
    const matchType = !filterType.value || log.type === filterType.value
    const matchStatus = !filterStatus.value || log.status === filterStatus.value
    
    return matchSearch && matchType && matchStatus
  })
})

// Helper Functions
const formatNumber = (num) => {
  return new Intl.NumberFormat('id-ID').format(num)
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  }).format(date)
}

const formatTime = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('id-ID', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  }).format(date)
}

const getInitials = (name) => {
  if (!name) return 'AD'
  return name.split(' ').map(n => n[0].toUpperCase()).join('').slice(0, 2)
}

const getTypeClass = (type) => {
  const classes = {
    login: 'bg-blue-100 text-blue-800',
    user_management: 'bg-green-100 text-green-800',
    deposit_management: 'bg-yellow-100 text-yellow-800',
    withdrawal_management: 'bg-orange-100 text-orange-800',
    profit_management: 'bg-purple-100 text-purple-800',
    system_settings: 'bg-gray-100 text-gray-800',
    other: 'bg-gray-100 text-gray-800'
  }
  return classes[type] || 'bg-gray-100 text-gray-800'
}

const getTypeLabel = (type) => {
  const labels = {
    login: 'Login',
    user_management: 'Manajemen User',
    deposit_management: 'Manajemen Deposit',
    withdrawal_management: 'Manajemen Withdrawal',
    profit_management: 'Manajemen Profit',
    system_settings: 'Pengaturan Sistem',
    other: 'Lainnya'
  }
  return labels[type] || type
}

const getStatusClass = (status) => {
  const classes = {
    success: 'bg-green-100 text-green-800',
    failed: 'bg-red-100 text-red-800',
    warning: 'bg-yellow-100 text-yellow-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusLabel = (status) => {
  const labels = {
    success: 'Sukses',
    failed: 'Gagal',
    warning: 'Peringatan'
  }
  return labels[status] || status
}

// Actions
const fetchAuditLogs = async (page = 1) => {
  try {
    const res = await fetch(`/api/admin/audit-logs?page=${page}`)
    if (res.ok) {
      const data = await res.json()
      logs.value = data.data
      stats.value = data.stats || {}
      pagination.value = data.meta || null
      currentPage.value = page
    }
  } catch (error) {
    console.error('Gagal mengambil data audit log:', error)
  }
}

const nextPage = () => {
  if (pagination.value?.next_page_url) {
    currentPage.value++
    fetchAuditLogs(currentPage.value)
  }
}

const prevPage = () => {
  if (pagination.value?.prev_page_url) {
    currentPage.value--
    fetchAuditLogs(currentPage.value)
  }
}

// Lifecycle
onMounted(() => {
  fetchAuditLogs()
})
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>