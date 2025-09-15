<template>
  <AdminLayout
    :user="user"
    :profileUrl="profileUrl"
    :logoutUrl="logoutUrl"
    :sidebarMenu="sidebarMenu"
  >
    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
      <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
        Daftar Pengguna
      </h1>
      <select v-model="roleFilter" class="px-4 py-2 border rounded-lg w-full md:w-64">
        <option value="">Semua Role</option>
        <option v-for="(label, key) in roles" :key="key" :value="key">{{ label }}</option>
      </select>
    </div>

    <!-- Table Users -->
    <div class="overflow-x-auto w-full">
      <table class="min-w-[800px] md:min-w-full bg-white shadow-md rounded-xl table-auto">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2 text-left">Nama</th>
            <th class="px-4 py-2 text-left">Email</th>
            <th class="px-4 py-2 text-left">Role</th>
            <th class="px-4 py-2 text-left">Status</th>
            <th class="px-4 py-2 text-left">Terakhir Login</th>
            <th class="px-4 py-2 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in filteredUsers" :key="u.id" class="hover:bg-gray-50 transition">
            <td class="px-4 py-2">{{ u.name }}</td>
            <td class="px-4 py-2">{{ u.email }}</td>
            <td class="px-4 py-2">{{ u.role_label }}</td>
            <td class="px-4 py-2">
              <span :class="u.is_active ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'">
                {{ u.status_label }}
              </span>
            </td>
            <td class="px-4 py-2">{{ u.last_login ?? '-' }}</td>
            <td class="px-4 py-2 text-center">
              <button @click="openDetailModal(u)" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
                Detail
              </button>
            </td>
          </tr>
          <tr v-if="filteredUsers.length === 0">
            <td colspan="6" class="px-4 py-4 text-center text-gray-500">Tidak ada pengguna</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Detail Pengguna -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl overflow-y-auto max-h-[90vh] p-6">
        <h2 class="text-xl font-bold mb-4">Detail Pengguna</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div><strong>Nama:</strong> {{ selectedUser.name }}</div>
          <div><strong>Email:</strong> {{ selectedUser.email }}</div>
          <div><strong>Status:</strong> {{ selectedUser.status_label }}</div>
          <div><strong>Terakhir Login:</strong> {{ selectedUser.last_login ?? '-' }}</div>
          <div><strong>Telepon:</strong> {{ selectedUser.phone ?? '-' }}</div>
        </div>

        <!-- Role -->
        <div class="mt-4">
          <label class="block font-semibold mb-1">Role</label>
          <select v-model="selectedUser.role" class="border px-3 py-2 rounded w-full">
            <option v-for="(label, key) in roles" :key="key" :value="key">{{ label }}</option>
          </select>
        </div>

        <!-- Wallet -->
        <div class="mt-4">
          <label class="block font-semibold mb-1">Wallet</label>
          <div class="flex flex-col sm:flex-row gap-2 items-stretch">
            <input type="text" v-model="selectedUser.wallet_address" placeholder="Belum ada wallet" readonly
                   class="border px-3 py-2 rounded flex-1"/>
            <button @click="generateWallet(selectedUser)" class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 transition">
              Generate
            </button>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row justify-end gap-2">
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

</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
.table-auto th, .table-auto td { text-align: center; word-wrap: break-word; }
div.overflow-x-auto::-webkit-scrollbar { height: 6px; }
div.overflow-x-auto::-webkit-scrollbar-thumb { background-color: rgba(100,100,100,0.4); border-radius: 3px; }
div.overflow-x-auto::-webkit-scrollbar-thumb:hover { background-color: rgba(100,100,100,0.6); }
</style>
