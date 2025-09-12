<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 mb-1">Profil Pengguna</h1>
        <p class="text-gray-500 text-sm">Kelola informasi profil Anda</p>
      </div>
      <div>
        <button
          @click="$inertia.visit('/dashboard')"
          class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md text-sm flex items-center"
        >
          <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </button>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-6">
      <!-- Sidebar -->
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

      <!-- Content -->
      <div class="lg:col-span-3">
        <!-- Profil -->
        <div v-if="activeTab === 'profile'" class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
          <div class="flex flex-col md:flex-row items-center md:items-start mb-6 pb-4 border-b border-gray-100">
            <div class="w-20 h-20 rounded-full bg-gradient-to-r from-blue-500 to-green-600 flex items-center justify-center text-2xl font-bold text-white">
              {{ userInitial }}
            </div>
            <div class="md:ml-6 mt-4 md:mt-0 text-center md:text-left">
              <h2 class="text-xl font-semibold text-gray-800">{{ user.name }}</h2>
              <p class="text-gray-500 text-sm">{{ user.email }}</p>
            </div>
          </div>

          <div>
            <h3 class="text-md font-medium text-gray-700 mb-4">Informasi Akun</h3>
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
                <label class="block text-xs font-medium text-gray-500 mb-1">Nomor Handphone</label>
                <div class="p-3 text-sm text-gray-800 bg-gray-50 rounded-md">{{ user.phone || '-' }}</div>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Bergabung Sejak</label>
                <div class="p-3 text-sm text-gray-800 bg-gray-50 rounded-md">{{ formatDate(user.created_at) }}</div>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status Akun</label>
                <div class="p-3 text-sm bg-gray-50 rounded-md inline-flex items-center">
                  <span class="text-gray-800">{{ user.status || '-' }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-8 pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-4">
            <button
              @click="$inertia.visit('/profile/edit')"
              class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors flex items-center justify-center"
            >
              <i class="fas fa-edit mr-2"></i> Edit Profil
            </button>
          </div>
        </div>

        <!-- Keamanan -->
        <div v-else-if="activeTab === 'security'" class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
          <h2 class="text-md font-medium text-gray-700 mb-4">Keamanan Akun</h2>
          <p class="text-sm text-gray-500 mb-6">
            Jaga keamanan akun Anda dengan mengubah password secara berkala dan aktifkan autentikasi 2-faktor jika tersedia.
          </p>
          <button
            @click="$inertia.visit('/profile/security')"
            class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors flex items-center"
          >
            <i class="fas fa-key mr-2"></i> Ubah Password
          </button>
        </div>

        <!-- Notifikasi -->
        <div v-else-if="activeTab === 'notifications'" class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
          <h2 class="text-md font-medium text-gray-700 mb-4">Pengaturan Notifikasi</h2>
          <div class="space-y-4">
            <div v-for="option in notificationOptions" :key="option.name" class="flex items-center justify-between p-3 bg-gray-50 rounded-md border border-gray-200">
              <div class="flex items-center space-x-2">
                <i :class="[option.icon, 'text-blue-500']"></i>
                <span class="text-gray-800 text-sm">{{ option.label }}</span>
              </div>
              <input type="checkbox" v-model="option.enabled" class="w-5 h-5 accent-blue-600">
            </div>
          </div>
          <div class="mt-6 flex justify-end">
            <button 
              class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors flex items-center"
              @click="saveNotifications"
            >
              <i class="fas fa-save mr-2"></i> Simpan
            </button>
          </div>
        </div>

        <!-- Preferensi -->
        <div v-else-if="activeTab === 'preferences'" class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
          <h2 class="text-md font-medium text-gray-700 mb-4">Preferensi</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-medium text-gray-500 mb-1">Tema</label>
              <select v-model="preferences.theme" class="p-2 border rounded-md w-full">
                <option value="light">Light</option>
                <option value="dark">Dark</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-500 mb-1">Bahasa</label>
              <select v-model="preferences.language" class="p-2 border rounded-md w-full">
                <option value="id">Bahasa Indonesia</option>
                <option value="en">English</option>
              </select>
            </div>
          </div>
          <div class="mt-6 flex justify-end">
            <button 
              class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors flex items-center"
              @click="savePreferences"
            >
              <i class="fas fa-save mr-2"></i> Simpan Preferensi
            </button>
          </div>
        </div>

        <!-- Tagihan -->
        <div v-else-if="activeTab === 'billing'" class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
          <h2 class="text-md font-medium text-gray-700 mb-4">Riwayat Tagihan</h2>
          <p class="text-sm text-gray-500 mb-4">Riwayat transaksi deposit/pembelian paket Anda.</p>

          <div v-if="billing.length" class="space-y-4">
            <div v-for="item in billing" :key="item.id" class="p-4 bg-gray-50 border rounded-md flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div class="flex-1">
                <p class="text-sm text-gray-800"><strong>{{ item.description }}</strong></p>
                <p class="text-xs text-gray-500">{{ formatDate(item.created_at) }}</p>
                <p class="text-sm text-gray-800">Jumlah: {{ item.amount }}</p>
                <p class="text-sm text-gray-800">Status: {{ item.status }}</p>
                <div class="flex items-center gap-2 mt-1" v-if="item.wallet_address">
                  <span class="text-sm text-gray-800">Wallet: {{ item.wallet_address }}</span>
                  <button @click="copyToClipboard(item.wallet_address)" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                    <i class="fas fa-copy mr-1"></i> Salin
                  </button>
                </div>
              </div>
              <div v-if="item.qr_url">
                <img :src="item.qr_url" alt="QR Code Tagihan" class="w-32 h-32 mx-auto md:mx-0"/>
              </div>
            </div>
          </div>

          <div v-else class="text-center py-8 text-gray-400">
            <i class="fas fa-receipt text-2xl mb-2 text-blue-400"></i>
            <p class="text-sm">Belum ada riwayat tagihan</p>
          </div>
        </div>

        <!-- Referral -->
        <div v-else-if="activeTab === 'referral'" class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
          <h2 class="text-md font-medium text-gray-700 mb-4">Program Referral</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Kode & QR Referral -->
            <div>
              <label class="block text-xs font-medium text-gray-500 mb-1">Kode & QR Referral</label>
              <div class="p-3 bg-gray-50 rounded-md border border-gray-200 flex flex-col items-center">
                <img v-if="referral.qr" :src="referral.qr" alt="QR Code Referral" class="w-32 h-32 mb-2"/>
                <span class="text-gray-800 font-medium">{{ user.referral_code }}</span>
                <button @click="copyReferralCode" class="text-blue-600 hover:text-blue-800 text-sm mt-1">
                  <i class="fas fa-copy mr-1"></i> Salin
                </button>
              </div>
            </div>

            <!-- Link Referral -->
            <div>
              <label class="block text-xs font-medium text-gray-500 mb-1">Link Referral</label>
              <div class="p-3 bg-gray-50 rounded-md border border-gray-200 flex flex-col items-center">
                <span class="text-gray-800 text-sm break-all">{{ referralLink }}</span>
                <button @click="copyReferralLink" class="text-blue-600 hover:text-blue-800 text-sm mt-1">
                  <i class="fas fa-copy mr-1"></i> Salin
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const { props } = usePage()

// User & Data
const user = props.auth.user
const billing = props.billing || []
const referral = props.referral || { qr: '', code: user.referral_code }

// Active tab sidebar
const activeTab = ref('profile')

// Sidebar menu
const menuItems = [
  { name: 'profile', label: 'Informasi Profil', icon: 'fas fa-user' },
  { name: 'security', label: 'Keamanan', icon: 'fas fa-shield-alt' },
  { name: 'notifications', label: 'Notifikasi', icon: 'fas fa-bell' },
  { name: 'preferences', label: 'Preferensi', icon: 'fas fa-cog' },
  { name: 'billing', label: 'Tagihan', icon: 'fas fa-credit-card' },
  { name: 'referral', label: 'Referral', icon: 'fas fa-users' }
]

// User initial
const userInitial = computed(() => user?.name?.charAt(0).toUpperCase() || '')

// Format date
const formatDate = (dateString) => {
  if (!dateString) return '-'
  return new Intl.DateTimeFormat('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }).format(new Date(dateString))
}

// Notifikasi
const notificationOptions = ref(props.notifications || [])
const saveNotifications = () => $inertia.post('/profile/notifications', { options: notificationOptions.value })

// Preferensi
const preferences = ref(props.preferences || { theme: 'light', language: 'id' })
const savePreferences = () => $inertia.post('/profile/preferences', preferences.value)

// Referral
const referralLink = computed(() => user.referral_code ? `${window.location.origin}/register?ref=${user.referral_code}` : '')

// Fungsi copy
const copyToClipboard = (text) => {
  if (!text) return
  navigator.clipboard.writeText(text)
}

// Tombol salin spesifik
const copyReferralCode = () => copyToClipboard(user.referral_code)
const copyReferralLink = () => copyToClipboard(referralLink.value)
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
</style>
