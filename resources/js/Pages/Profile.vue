<template>
  <AppLayout>
    <div class=" bg-gray-50 p-3 sm:p-4 md:p-6">
      <!-- Header -->
      <div class="max-w-4xl mx-auto mb-3 sm:mb-4 md:mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div>
          <h1 class="text-base sm:text-lg md:text-xl font-bold text-gray-800 mb-1">Profil Pengguna</h1>
          <p class="text-gray-500 text-xs sm:text-sm">Kelola informasi profil Anda</p>
        </div>
        <div class="w-full sm:w-auto">
          <button
            @click="$inertia.visit('/dashboard')"
            class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded-md text-xs sm:text-sm flex items-center justify-center"
          >
            <i class="fas fa-arrow-left mr-2 text-xs"></i> Kembali ke Dashboard
          </button>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 shadow-sm">
            <ul class="space-y-1">
              <li
                v-for="item in menuItems"
                :key="item.name"
                :class="[ 
                  'px-2 py-2 rounded-md cursor-pointer text-xs sm:text-sm transition-all flex items-center',
                  activeTab === item.name
                    ? 'bg-blue-100 text-blue-700 font-medium'
                    : 'text-gray-600 hover:bg-gray-100'
                ]"
                @click="activeTab = item.name"
              >
                <i :class="[item.icon, 'mr-2 w-3 sm:w-4 text-center text-xs sm:text-sm']"></i>
                <span>{{ item.label }}</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Content -->
        <div class="lg:col-span-3">
          <!-- Profil -->
          <div v-if="activeTab === 'profile'" class="bg-white rounded-lg border border-gray-200 p-4 sm:p-5 shadow-sm">
            <div class="flex flex-col items-center text-center mb-4 sm:mb-5 pb-3 border-b border-gray-100">
              <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-full bg-gradient-to-r from-blue-500 to-green-600 flex items-center justify-center text-lg sm:text-xl md:text-2xl font-bold text-white">
                {{ userInitial }}
              </div>
              <div class="mt-2 sm:mt-3">
                <h2 class="text-sm sm:text-base md:text-lg font-semibold text-gray-800">{{ user.name }}</h2>
                <p class="text-gray-500 text-xs sm:text-sm mt-1">{{ user.email }}</p>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
              <div>
                <label class="block text-xs text-gray-500 mb-1">Nama Lengkap</label>
                <div class="p-2 text-xs sm:text-sm text-gray-800 bg-gray-50 rounded-md border border-gray-200">{{ user.name }}</div>
              </div>
              <div>
                <label class="block text-xs text-gray-500 mb-1">Email</label>
                <div class="p-2 text-xs sm:text-sm text-gray-800 bg-gray-50 rounded-md border border-gray-200">{{ user.email }}</div>
              </div>
              <div>
                <label class="block text-xs text-gray-500 mb-1">Nomor Handphone</label>
                <div class="p-2 text-xs sm:text-sm text-gray-800 bg-gray-50 rounded-md border border-gray-200">{{ user.phone || '-' }}</div>
              </div>
              <div>
                <label class="block text-xs text-gray-500 mb-1">Bergabung Sejak</label>
                <div class="p-2 text-xs sm:text-sm text-gray-800 bg-gray-50 rounded-md border border-gray-200">{{ formatDate(user.created_at) }}</div>
              </div>
              <div class="sm:col-span-2">
                <label class="block text-xs text-gray-500 mb-1">Status Akun</label>
                <div class="p-2 text-xs sm:text-sm text-gray-800 bg-gray-50 rounded-md border border-gray-200">{{ user.status || '-' }}</div>
              </div>
            </div>

            <div class="mt-4 sm:mt-5 flex justify-end">
              <button
                @click="$inertia.visit('/profile/edit')"
                class="bg-blue-600 text-white px-3 py-2 rounded-md text-xs sm:text-sm hover:bg-blue-700 transition-colors flex items-center justify-center"
              >
                <i class="fas fa-edit mr-1 text-xs"></i> Edit Profil
              </button>
            </div>
          </div>

          <!-- Keamanan -->
          <div v-else-if="activeTab === 'security'" class="bg-white rounded-lg border border-gray-200 p-4 sm:p-5 shadow-sm">
            <h2 class="text-sm sm:text-base font-medium text-gray-700 mb-2 sm:mb-3">Keamanan Akun</h2>
            <p class="text-xs sm:text-sm text-gray-500 mb-3 sm:mb-4">
              Jaga keamanan akun Anda dengan mengubah password secara berkala.
            </p>
            <button
              @click="$inertia.visit('/profile/security')"
              class="bg-blue-600 text-white px-3 py-2 rounded-md text-xs sm:text-sm hover:bg-blue-700 transition-colors flex items-center justify-center"
            >
              <i class="fas fa-key mr-1 text-xs"></i> Ubah Password
            </button>
          </div>

          <!-- Notifikasi -->
          <div v-else-if="activeTab === 'notifications'" class="bg-white rounded-lg border border-gray-200 p-4 sm:p-5 shadow-sm">
            <h2 class="text-sm sm:text-base font-medium text-gray-700 mb-2 sm:mb-3">Pengaturan Notifikasi</h2>
            <div class="space-y-2 sm:space-y-3">
              <div v-for="option in notificationOptions" :key="option.name" class="flex items-center justify-between p-2 sm:p-3 bg-gray-50 rounded-md border border-gray-200">
                <div class="flex items-center space-x-2">
                  <i :class="[option.icon, 'text-blue-500 text-sm']"></i>
                  <span class="text-gray-800 text-xs sm:text-sm">{{ option.label }}</span>
                </div>
                <input type="checkbox" v-model="option.enabled" class="w-4 h-4 sm:w-5 sm:h-5 accent-blue-600">
              </div>
            </div>
            <div class="mt-3 sm:mt-4 flex justify-end">
              <button 
                class="bg-blue-600 text-white px-3 py-2 rounded-md text-xs sm:text-sm hover:bg-blue-700 transition-colors flex items-center"
                @click="saveNotifications"
              >
                <i class="fas fa-save mr-1 text-xs"></i> Simpan
              </button>
            </div>
          </div>

          <!-- Tagihan -->
          <div v-else-if="activeTab === 'billing'" class="bg-white rounded-lg border border-gray-200 p-4 sm:p-5 shadow-sm">
            <h2 class="text-sm sm:text-base font-medium text-gray-700 mb-2 sm:mb-3">Riwayat Tagihan</h2>
            <p class="text-xs sm:text-sm text-gray-500 mb-3 sm:mb-4">Riwayat transaksi deposit/pembelian paket Anda.</p>

            <div v-if="billing.length" class="space-y-3">
              <div v-for="item in billing" :key="item.id" class="p-3 bg-gray-50 border border-gray-200 rounded-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex-1">
                  <p class="text-xs sm:text-sm text-gray-800 font-medium">{{ item.description }}</p>
                  <p class="text-xs text-gray-500 mt-1">{{ formatDate(item.created_at) }}</p>
                  <p class="text-xs sm:text-sm text-gray-800 mt-1">Jumlah: {{ item.amount }}</p>
                  <p class="text-xs sm:text-sm text-gray-800 mt-1">Status: {{ item.status }}</p>
                  <div class="flex items-center gap-2 mt-2" v-if="item.wallet_address">
                    <span class="text-xs sm:text-sm text-gray-800">Wallet: {{ item.wallet_address }}</span>
                    <button @click="copyToClipboard(item.wallet_address)" class="text-blue-600 hover:text-blue-800 text-xs flex items-center">
                      <i class="fas fa-copy mr-1 text-xs"></i> Salin
                    </button>
                  </div>
                </div>
                <div v-if="item.qr_url" class="self-center mt-2 sm:mt-0">
                  <img :src="item.qr_url" alt="QR Code Tagihan" class="w-16 h-16 sm:w-20 sm:h-20"/>
                </div>
              </div>
            </div>

            <div v-else class="text-center py-6 text-gray-400">
              <i class="fas fa-receipt text-xl mb-2 text-blue-400"></i>
              <p class="text-xs sm:text-sm">Belum ada riwayat tagihan</p>
            </div>
          </div>

          <!-- Referral -->
          <div v-else-if="activeTab === 'referral'" class="bg-white rounded-lg border border-gray-200 p-4 sm:p-5 shadow-sm">
            <h2 class="text-sm sm:text-base font-medium text-gray-700 mb-2 sm:mb-3">Program Referral</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3 sm:mb-4">
              <!-- Kode & QR Referral -->
              <div>
                <label class="block text-xs text-gray-500 mb-1">Kode & QR Referral</label>
                <div class="p-3 bg-gray-50 rounded-md border border-gray-200 flex flex-col items-center">
                  <img v-if="referral.qr" :src="referral.qr" alt="QR Code Referral" class="w-16 h-16 sm:w-20 sm:h-20 mb-2"/>
                  <span class="text-xs sm:text-sm text-gray-800 font-medium">{{ user.referral_code }}</span>
                  <button @click="copyReferralCode" class="text-blue-600 hover:text-blue-800 text-xs mt-1">
                    <i class="fas fa-copy mr-1 text-xs"></i> Salin
                  </button>
                </div>
              </div>

              <!-- Link Referral -->
              <div>
                <label class="block text-xs text-gray-500 mb-1">Link Referral</label>
                <div class="p-3 bg-gray-50 rounded-md border border-gray-200 flex flex-col items-center">
                  <span class="text-xs sm:text-sm text-gray-800 break-all text-center">{{ referralLink }}</span>
                  <button @click="copyReferralLink" class="text-blue-600 hover:text-blue-800 text-xs mt-1">
                    <i class="fas fa-copy mr-1 text-xs"></i> Salin
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AppLayout from "@/Layouts/AppLayout.vue"

const { props } = usePage()
const user = props.auth.user
const billing = props.billing || []
const referral = props.referral || { qr: '', code: user.referral_code }

const activeTab = ref('profile')
const menuItems = [
  { name: 'profile', label: 'Informasi Profil', icon: 'fas fa-user' },
  { name: 'billing', label: 'Tagihan', icon: 'fas fa-credit-card' },
  { name: 'referral', label: 'Referral', icon: 'fas fa-users' }
]

const userInitial = computed(() => user?.name?.charAt(0).toUpperCase() || '')
const formatDate = (dateString) => dateString ? new Intl.DateTimeFormat('id-ID', { year:'numeric', month:'long', day:'numeric' }).format(new Date(dateString)) : '-'

const notificationOptions = ref(props.notifications || [])
const saveNotifications = () => $inertia.post('/profile/notifications', { options: notificationOptions.value })

const preferences = ref(props.preferences || { theme: 'light', language: 'id' })
const savePreferences = () => $inertia.post('/profile/preferences', preferences.value)

const referralLink = computed(() => user.referral_code ? `${window.location.origin}/register?ref=${user.referral_code}` : '')

const copyToClipboard = (text) => { if (!text) return; navigator.clipboard.writeText(text) }
const copyReferralCode = () => copyToClipboard(user.referral_code)
const copyReferralLink = () => copyToClipboard(referralLink.value)
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
</style>