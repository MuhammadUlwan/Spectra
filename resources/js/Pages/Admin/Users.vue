<template>
  <AdminLayout
    :user="user"
    :profileUrl="profileUrl"
    :logoutUrl="logoutUrl"
    :sidebarMenu="sidebarMenu"
    pageTitle="$t('usersTitle')"
  >
    <!-- Filter dan Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
      <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
        Daftar Pengguna
      </h1>
      <select v-model="roleFilter" class="px-4 py-2 border rounded-lg w-full md:w-auto">
        <option value="">Semua Role</option>
        <option v-for="(label, key) in roles" :key="key" :value="key">{{ label }}</option>
      </select>
    </div>

    <!-- Tabel Users -->
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white shadow-md rounded-xl overflow-hidden table-fixed">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2 border">Nama</th>
            <th class="px-4 py-2 border">Email</th>
            <th class="px-4 py-2 border">Role</th>
            <th class="px-4 py-2 border">Status</th>
            <th class="px-4 py-2 border">Terakhir Login</th>
            <th class="px-4 py-2 border">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in filteredUsers" :key="u.id">
            <td class="px-4 py-2 border">{{ u.name }}</td>
            <td class="px-4 py-2 border">{{ u.email }}</td>
            <td class="px-4 py-2 border">{{ u.role_label }}</td>
            <td class="px-4 py-2 border">
              <span :class="u.is_active ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'">
                {{ u.status_label }}
              </span>
            </td>
            <td class="px-4 py-2 border">{{ u.last_login }}</td>
            <td class="px-4 py-2 border text-center">
              <button @click="openDetailModal(u)" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
                Lihat Detail
              </button>
            </td>
          </tr>
          <tr v-if="filteredUsers.length === 0">
            <td class="px-4 py-4 border text-center" colspan="6">Tidak ada pengguna</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Detail Pengguna -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/2 p-6 relative">
        <h2 class="text-xl font-bold mb-4">Detail Pengguna</h2>
        <p><strong>Nama:</strong> {{ selectedUser.name }}</p>
        <p><strong>Email:</strong> {{ selectedUser.email }}</p>
        <p><strong>Status:</strong> {{ selectedUser.status_label }}</p>
        <p><strong>Terakhir Login:</strong> {{ selectedUser.last_login }}</p>
        <p><strong>Telepon:</strong> {{ selectedUser.phone }}</p>

        <!-- Role -->
        <div class="mt-4">
          <label class="block font-semibold mb-1">Role</label>
          <select v-model="selectedUser.role" class="border px-3 py-2 rounded w-full">
            <option value="admin">Admin</option>
            <option value="investor">Investor</option>
            <option value="konsultan">Konsultan</option>
          </select>
        </div>

        <!-- Wallet -->
        <div class="mt-4">
          <label class="block font-semibold mb-1">Wallet</label>
          <div class="flex gap-2 items-center">
            <input type="text" class="border px-3 py-2 rounded flex-1" v-model="selectedUser.wallet_address" placeholder="Belum ada wallet" readonly />
            <button @click="generateWallet(selectedUser)" class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 transition">
              Generate
            </button>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-end items-center gap-2">
          <button v-if="!selectedUser.is_active" @click="selectedUser.is_active = true" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Aktifkan
          </button>
          <button v-if="selectedUser.is_active" @click="selectedUser.is_active = false" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
            Nonaktifkan
          </button>
          <button @click="saveUser(selectedUser)" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Simpan
          </button>
          <button @click="closeModal" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
            Tutup
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const { props } = usePage()
const user = props.auth?.user || {}
const profileUrl = props.profileUrl || '/profile'
const logoutUrl = props.logoutUrl || '/logout'

const users = ref(props.users ?? [])
const roles = { admin: 'Admin', investor: 'Investor', konsultan: 'Konsultan' }
const roleFilter = ref('')
const filteredUsers = computed(() => {
  return users.value.filter(u => {
    if (!roleFilter.value) return true
    if (roleFilter.value === 'konsultan') return u.is_consultant
    return u.role === roleFilter.value && !u.is_consultant
  })
})

// Modal
const showModal = ref(false)
const selectedUser = ref({})

function openDetailModal(u) {
  selectedUser.value = { ...u }
  showModal.value = true
}

function closeModal() {
  selectedUser.value = {}
  showModal.value = false
}

// Wallet generator
function generateWallet(u) {
  const random6 = Math.floor(100000 + Math.random() * 900000)
  u.wallet_address = `SPC-${u.id}-${random6}`
}

// Save user
function saveUser(u) {
  router.post(`/admin/users/${u.id}/update`, {
    role: u.role,
    wallet_address: u.wallet_address,
    is_active: u.is_active
  }, {
    onSuccess: (page) => {
      const idx = users.value.findIndex(user => user.id === u.id)
      if (idx !== -1) users.value[idx] = { ...page.props.user || u }
      closeModal()
    },
    onError: () => alert('Gagal menyimpan user')
  })
}

// Sidebar menu
const sidebarMenu = [
  { label: "Dashboard", url: "/admin/dashboard", icon: "fas fa-home" },
  { label: "Investasi", url: "/admin/investments", icon: "fas fa-chart-line" },
  { label: "Pengguna", url: "/admin/users", icon: "fas fa-users", active: true },
  { label: "Withdraw", url: "/admin/withdraws", icon: "fas fa-money-bill-wave" },
  { label: "Pengaturan", url: "/admin/settings", icon: "fas fa-cog" },
]
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
.table-fixed td, .table-fixed th { text-align: center; word-wrap: break-word; }
.table-fixed th:nth-child(1), .table-fixed td:nth-child(1) { width: 20%; }
.table-fixed th:nth-child(2), .table-fixed td:nth-child(2) { width: 25%; }
.table-fixed th:nth-child(3), .table-fixed td:nth-child(3) { width: 15%; }
.table-fixed th:nth-child(4), .table-fixed td:nth-child(4) { width: 10%; }
.table-fixed th:nth-child(5), .table-fixed td:nth-child(5) { width: 20%; }
.table-fixed th:nth-child(6), .table-fixed td:nth-child(6) { width: 10%; }
</style>
