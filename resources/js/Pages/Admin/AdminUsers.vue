<template>
  <AdminLayout title="Manajemen Pengguna" :activeLink="activeLink">
    <div class="space-y-6">
      <!-- Header -->
      <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Pengguna</h1>
            <p class="text-gray-600 mt-1">Kelola data pengguna sistem Spectra</p>
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
                placeholder="Cari nama atau email..."
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
              />
            </div>
          </div>

          <!-- Dropdown Role -->
          <div class="relative">
            <label class="sr-only">Filter Tipe</label>
            <i class="fas fa-user-tag absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10"></i>
            <select 
              v-model="filterRole" 
              class="w-full pl-10 pr-8 py-3 appearance-none border border-gray-300 rounded-x0 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white cursor-pointer"
            >
              <option value="">Semua Tipe</option>
              <option 
                v-for="role in availableRoles" 
                :key="role.value" 
                :value="role.value"
              >
                {{ role.label }}
              </option>
            </select>
            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
          </div>

          <!-- Dropdown Status -->
          <div class="relative">
            <label class="sr-only">Filter Status</label>
            <i class="fas fa-toggle-on absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10"></i>
            <select 
              v-model="filterStatus" 
              class="w-full pl-10 pr-8 py-3 appearance-none border border-gray-300 rounded-x focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white cursor-pointer"
            >
              <option value="">Semua Status</option>
              <option 
                v-for="status in availableStatuses" 
                :key="status.value" 
                :value="status.value"
              >
                {{ status.label }}
              </option>
            </select>
            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
          </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div v-for="stat in statCards" :key="stat.label" :class="stat.bgClass">
            <div class="flex items-center">
              <div :class="['p-2 rounded-lg', stat.iconBg]">
                <i :class="stat.icon"></i>
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
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50 transition">
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold flex-shrink-0">
                        {{ getUserInitials(user.name) }}
                      </div>
                      <div class="ml-3 min-w-0">
                        <div class="text-sm font-medium text-gray-900 truncate">{{ user.name }}</div>
                        <div class="text-sm text-gray-500 truncate">{{ user.email }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <span class="px-2 py-1 rounded-full text-xs font-medium" :class="user.role_color">
                      {{ user.role_label }}
                    </span>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <span class="px-2 py-1 rounded-full text-xs font-medium" :class="user.status_color">
                      {{ user.status_label }}
                    </span>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(user.created_at) }}
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center space-x-1">
                      <button @click="viewDetail(user)" class="text-blue-600 hover:text-blue-900 p-1.5 rounded-lg hover:bg-blue-50 transition" title="Detail">
                        <i class="fas fa-eye text-sm"></i>
                      </button>
                      <button @click="editUser(user)" class="text-green-600 hover:text-green-900 p-1.5 rounded-lg hover:bg-green-50 transition" title="Edit">
                        <i class="fas fa-edit text-sm"></i>
                      </button>
                      <button @click="toggleStatus(user)" class="p-1.5 rounded-lg transition" :class="user.status === 'active' ? 'text-red-600 hover:text-red-900 hover:bg-red-50' : 'text-green-600 hover:text-green-900 hover:bg-green-50'" :title="user.status === 'active' ? 'Nonaktifkan' : 'Aktifkan'">
                        <i :class="user.status === 'active' ? 'fas fa-user-slash text-sm' : 'fas fa-user-check text-sm'"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Empty State -->
          <div v-if="filteredUsers.length === 0" class="text-center py-12">
            <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Tidak ada pengguna ditemukan</p>
            <p class="text-gray-400 text-sm mt-1">Coba ubah filter pencarian</p>
          </div>

          <!-- Pagination -->
          <div v-if="pagination" class="px-4 py-3 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
              <div class="text-sm text-gray-700">
                Menampilkan {{ pagination.from }} - {{ pagination.to }} dari {{ pagination.total }}
              </div>
              <div class="flex space-x-2">
                <button 
                  @click="prevPage" 
                  :disabled="!pagination.prev_page_url" 
                  class="px-3 py-1.5 rounded-lg border border-gray-300 text-sm transition"
                  :class="!pagination.prev_page_url ? 'opacity-50 cursor-not-allowed text-gray-400' : 'hover:bg-gray-100 text-gray-700'"
                >
                  <i class="fas fa-chevron-left mr-1 text-xs"></i> Sebelumnya
                </button>
                <button 
                  @click="nextPage" 
                  :disabled="!pagination.next_page_url" 
                  class="px-3 py-1.5 rounded-lg border border-gray-300 text-sm transition"
                  :class="!pagination.next_page_url ? 'opacity-50 cursor-not-allowed text-gray-400' : 'hover:bg-gray-100 text-gray-700'"
                >
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
const activeLink = ref('/admin/manajemen_users')

// State
const users = ref([])
const stats = ref({})
const search = ref('')
const filterRole = ref('')
const filterStatus = ref('')
const pagination = ref(null)
const currentPage = ref(1)

// Dropdown options → backend driven
const availableRoles = ref([])
const availableStatuses = ref([])

// Cards stat auto build
const statCards = computed(() => [
  {
    label: 'Total Pengguna',
    value: formatNumber(stats.value.totalUsers || 0),
    icon: 'fas fa-users text-blue-600',
    bgClass: 'bg-blue-50 rounded-xl p-4 border border-blue-100',
    iconBg: 'bg-blue-100',
    textColor: 'text-blue-600'
  },
  {
    label: 'Pengguna Aktif',
    value: formatNumber(stats.value.activeUsers || 0),
    icon: 'fas fa-user-check text-green-600',
    bgClass: 'bg-green-50 rounded-xl p-4 border border-green-100',
    iconBg: 'bg-green-100',
    textColor: 'text-green-600'
  },
  {
    label: 'Pertumbuhan',
    value: `${stats.value.growthRate || 0}%`,
    icon: 'fas fa-chart-line text-purple-600',
    bgClass: 'bg-purple-50 rounded-xl p-4 border border-purple-100',
    iconBg: 'bg-purple-100',
    textColor: 'text-purple-600'
  }
])

// Computed
const filteredUsers = computed(() => {
  return users.value.filter(u => {
    const matchSearch = !search.value || 
      u.name.toLowerCase().includes(search.value.toLowerCase()) || 
      u.email.toLowerCase().includes(search.value.toLowerCase())
    const matchRole = !filterRole.value || u.role === filterRole.value
    const matchStatus = !filterStatus.value || u.status === filterStatus.value
    return matchSearch && matchRole && matchStatus
  })
})

// Helpers
const formatNumber = num => new Intl.NumberFormat('id-ID').format(num)
const formatDate = d => new Date(d).toLocaleDateString('id-ID', { 
  year: 'numeric', 
  month: 'short', 
  day: 'numeric' 
})
const getUserInitials = n => n ? n.split(' ').map(w => w[0]).join('').toUpperCase().substring(0,2) : 'US'

// Actions
const fetchUsers = async (page = 1) => {
  try {
    const res = await fetch(`/api/admin/users?page=${page}`)
    if (res.ok) {
      const data = await res.json()
      users.value = data.data
      pagination.value = data.meta
      stats.value = data.stats
      availableRoles.value = data.availableRoles || []
      availableStatuses.value = data.availableStatuses || []
    }
  } catch (e) {
    console.error('Gagal mengambil data:', e)
  }
}

const viewDetail = u => {
  window.location.href = `/admin/users/${u.id}`
}

const editUser = u => {
  window.location.href = `/admin/users/${u.id}/edit`
}

const toggleStatus = async (u) => {
  try {
    const res = await fetch(`/api/admin/users/${u.id}/toggle-status`, {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
      },
      body: JSON.stringify({ 
        status: u.status === 'active' ? 'inactive' : 'active' 
      })
    })
    
    if (res.ok) {
      u.status = u.status === 'active' ? 'inactive' : 'active'
    }
  } catch (e) {
    console.error('Gagal mengubah status:', e)
  }
}

const nextPage = () => {
  if (pagination.value?.next_page_url) {
    currentPage.value++
    fetchUsers(currentPage.value)
  }
}

const prevPage = () => {
  if (pagination.value?.prev_page_url) {
    currentPage.value--
    fetchUsers(currentPage.value)
  }
}

// Init
onMounted(() => fetchUsers())
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

/* Improved dropdown styling for mobile */
select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-image: none; /* Remove default arrow on some browsers */
}

/* Responsive improvements */
@media (max-width: 640px) {
  .grid-cols-1 > div {
    margin-bottom: 0.5rem;
  }
  
  .px-4 {
    padding-left: 1rem;
    padding-right: 1rem;
  }
  
  .py-3 {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
  }
  
  .text-sm {
    font-size: 0.875rem;
  }
  
  .text-2xl {
    font-size: 1.5rem;
  }
  
  /* Ensure dropdowns don't overflow on mobile */
  .relative {
    position: relative;
    z-index: 1;
  }
  
  select {
    font-size: 16px; /* Prevent zoom on iOS */
  }
}

/* Fix for mobile viewport */
@media (max-width: 768px) {
  .min-w-\[600px\] {
    min-width: 100%;
  }
  
  .overflow-x-auto {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
  }
}

/* Hover effects */
button:hover {
  transform: translateY(-1px);
}

/* Transition smoothness */
.transition {
  transition: all 0.2s ease-in-out;
}
</style>