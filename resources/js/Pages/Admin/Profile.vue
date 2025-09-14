<template>
  <AdminLayout
    :user="user"
    :profileUrl="profileUrl"
    :logoutUrl="logoutUrl"
    :sidebarMenu="sidebarMenu"
    pageTitle="Profil Admin"
  >
    <div class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-4 gap-6 w-full flex-1">
      <!-- Menu Profil -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm">
          <h2 class="text-md font-semibold text-gray-700 mb-3">Menu Profil</h2>
          <ul class="space-y-1">
            <li
              v-for="item in menuItems"
              :key="item.name"
              :class="[
                'px-3 py-2 rounded-md cursor-pointer text-sm transition-all flex items-center',
                activeTab === item.name
                  ? 'bg-blue-100 text-blue-700 font-medium'
                  : 'text-gray-600 hover:bg-gray-100'
              ]"
              @click="activeTab = item.name"
            >
              <i :class="[item.icon, 'mr-2 w-4 text-center']"></i>
              {{ item.label }}
            </li>
          </ul>
        </div>
      </div>

      <!-- Konten Utama -->
      <div class="lg:col-span-3">
        <!-- Profil -->
        <div
          v-if="activeTab === 'profile'"
          class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm"
        >
          <div class="flex flex-col md:flex-row items-center md:items-start mb-6 pb-4 border-b border-gray-100">
            <div
              class="w-20 h-20 rounded-full bg-gradient-to-r from-blue-500 to-green-600 flex items-center justify-center text-2xl font-bold text-white shadow-md"
            >
              {{ userInitials }}
            </div>
            <div class="md:ml-6 mt-4 md:mt-0 text-center md:text-left">
              <h2 class="text-xl font-semibold text-gray-800">{{ user.name }}</h2>
              <p class="text-gray-500 text-sm">{{ user.email }}</p>
            </div>
          </div>

          <div>
            <h3 class="text-md font-medium text-gray-700 mb-4">Informasi Admin</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Nama Lengkap</label>
                <div class="p-3 text-sm text-gray-800 bg-gray-50 rounded-md">{{ user.name }}</div>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                <div class="p-3 text-sm text-gray-800 bg-gray-50 rounded-md">{{ user.email }}</div>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">No. HP</label>
                <div class="p-3 text-sm text-gray-800 bg-gray-50 rounded-md">{{ user.phone || '-' }}</div>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status Akun</label>
                <div class="p-3 text-sm text-gray-800 bg-gray-50 rounded-md">
                  {{ user.is_active ? 'Aktif' : 'Nonaktif' }}
                </div>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Bergabung Sejak</label>
                <div class="p-3 text-sm text-gray-800 bg-gray-50 rounded-md">
                  {{ formatDate(user.created_at) }}
                </div>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Terakhir Login</label>
                <div class="p-3 text-sm text-gray-800 bg-gray-50 rounded-md">
                  {{ formatDate(user.last_login_at) }}
                </div>
              </div>
            </div>
          </div>

          <div class="mt-8 pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-4 justify-between">
            <div class="flex gap-4">
              <button
                @click="$inertia.visit('/admin/profile/edit')"
                class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors flex items-center justify-center shadow-sm"
              >
                <i class="fas fa-edit mr-2"></i> Edit Profil
              </button>
            </div>
            <div>
              <button
                @click="goBack"
                class="bg-gray-100 text-gray-800 px-4 py-2 rounded-md text-sm hover:bg-gray-200 transition-colors flex items-center justify-center shadow-sm"
              >
                <i class="fas fa-arrow-left mr-2"></i> Kembali
              </button>
            </div>
          </div>
        </div>

        <!-- Keamanan -->
        <div
          v-else-if="activeTab === 'security'"
          class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm"
        >
          <h2 class="text-md font-medium text-gray-700 mb-4">Keamanan Akun</h2>
          <p class="text-sm text-gray-500 mb-6">
            Jaga keamanan akun Anda dengan mengubah password secara berkala.
          </p>
          <button
            @click="$inertia.visit('/admin/profile/security')"
            class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors flex items-center shadow-sm"
          >
            <i class="fas fa-key mr-2"></i> Ubah Password
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const { props } = usePage()
const user = props.auth.user
const profileUrl = props.profileUrl
const logoutUrl = props.logoutUrl

const activeTab = ref('profile')
const menuItems = [
  { name: 'profile', label: 'Informasi Profil', icon: 'fas fa-user' },
  { name: 'security', label: 'Keamanan', icon: 'fas fa-shield-alt' }
]

// Sidebar menu (konsisten dengan halaman lain)
const sidebarMenu = [
  { label: "Dashboard", url: "/admin/dashboard", icon: "fas fa-home" },
  { label: "Investasi", url: "/admin/investments", icon: "fas fa-chart-line" },
  { label: "Pengguna", url: "/admin/users", icon: "fas fa-users" },
  { label: "Withdraw", url: "/admin/withdraws", icon: "fas fa-money-bill-wave" },
  { label: "Pengaturan", url: "/admin/settings", icon: "fas fa-cog" },
]

const userInitials = computed(() => {
  if (!user.name) return 'A'
  return user.name
    .split(' ')
    .map((w) => w[0])
    .join('')
    .toUpperCase()
    .substring(0, 2)
})

const formatDate = (dateString) => {
  if (!dateString) return '-'
  return new Intl.DateTimeFormat('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(new Date(dateString))
}

const goBack = () => {
  window.history.back()
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
</style>
