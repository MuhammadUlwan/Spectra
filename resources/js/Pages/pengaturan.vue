<template>
  <AppLayout>
    <div class="bg-gray-50 py-4 px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="max-w-4xl mx-auto mb-6 sm:mb-8">
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
          <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Pengaturan</h1>
          <p class="text-gray-600 mt-1 text-sm sm:text-base">Kelola pengaturan akun dan preferensi Anda</p>
        </div>
      </div>

      <div class="max-w-4xl mx-auto flex flex-col lg:flex-row gap-4 sm:gap-6">
        <!-- Mobile Menu Toggle -->
        <div class="lg:hidden">
          <button 
            @click="showMobileMenu = !showMobileMenu"
            class="w-full bg-white rounded-xl shadow-sm p-4 flex items-center justify-between"
          >
            <span class="font-medium text-gray-800">
              {{ activeSectionLabel }}
            </span>
            <i :class="['fas', showMobileMenu ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
          </button>
        </div>

        <!-- Sidebar Menu -->
        <div 
          class="lg:w-1/3"
          :class="{'hidden lg:block': !showMobileMenu, 'block': showMobileMenu}"
        >
          <div class="bg-white rounded-xl shadow-sm p-4 sm:p-5 lg:sticky lg:top-6">
            <!-- User Info -->
            <div class="text-center mb-4 sm:mb-6 pb-4 border-b border-gray-200">
              <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-r from-blue-500 to-green-500 rounded-full flex items-center justify-center mx-auto mb-3 text-white text-lg sm:text-xl font-bold">
                {{ userInitials }}
              </div>
              <h3 class="font-semibold text-gray-800 text-sm sm:text-base">{{ userName }}</h3>
              <p class="text-xs sm:text-sm text-gray-500">{{ userCode }}</p>
            </div>

            <!-- Navigation Menu -->
            <nav class="space-y-1">
              <button 
                v-for="item in menuItems" 
                :key="item.id"
                @click="setActiveSection(item.id)"
                :class="[
                  'w-full text-left px-3 py-2 sm:px-4 sm:py-3 rounded-lg flex items-center transition-colors text-sm sm:text-base',
                  activeSection === item.id 
                    ? 'bg-blue-50 text-blue-600 font-medium' 
                    : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50'
                ]"
              >
                <i :class="[item.icon, 'mr-2 sm:mr-3 text-xs sm:text-sm']"></i>
                {{ item.label }}
              </button>
            </nav>
          </div>
        </div>

        <!-- Content Area -->
        <div class="lg:w-2/3">
          <!-- Security Section -->
          <div v-if="activeSection === 'security'" class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
            <h2 class="text-lg font-medium text-gray-800 mb-4 sm:mb-6">Keamanan</h2>
            
            <div class="space-y-4 sm:space-y-6">
              <div>
                <h3 class="font-medium text-gray-700 mb-3 sm:mb-4">Ubah Password</h3>
                <div class="space-y-3 sm:space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Password Saat Ini</label>
                    <input 
                      type="password" 
                      v-model="securityForm.current_password" 
                      class="w-full px-3 py-2 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                    >
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Password Baru</label>
                    <input 
                      type="password" 
                      v-model="securityForm.new_password" 
                      class="w-full px-3 py-2 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                    >
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Konfirmasi Password Baru</label>
                    <input 
                      type="password" 
                      v-model="securityForm.new_password_confirmation" 
                      class="w-full px-3 py-2 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                    >
                  </div>
                </div>
              </div>
            </div>
            
            <div class="mt-4 sm:mt-6 flex justify-end">
              <button 
                @click="updateSecurity" 
                :disabled="securityForm.processing" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 sm:px-6 sm:py-2 rounded-lg font-medium disabled:opacity-50 text-sm sm:text-base"
              >
                <span v-if="securityForm.processing">Menyimpan...</span>
                <span v-else>Simpan Perubahan</span>
              </button>
            </div>
          </div>

          <!-- Preferences Section -->
          <div v-if="activeSection === 'preferences'" class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
            <h2 class="text-lg font-medium text-gray-800 mb-4 sm:mb-6">Preferensi</h2>
            
            <div class="space-y-4 sm:space-y-6">
              <div class="flex items-center justify-between">
                <div class="flex-1 mr-4">
                  <h3 class="font-medium text-gray-700 text-sm sm:text-base">Mode Gelap</h3>
                  <p class="text-gray-600 text-xs sm:text-sm">Ubah tampilan menjadi mode gelap</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" v-model="preferencesForm.dark_mode">
                  <div class="w-10 h-5 sm:w-11 sm:h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[1px] after:left-[1px] sm:after:top-[2px] sm:after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 sm:after:h-5 sm:after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
              </div>
              
              <div class="pt-3 sm:pt-4 border-t border-gray-200">
                <h3 class="font-medium text-gray-700 mb-3 sm:mb-4 text-sm sm:text-base">Notifikasi</h3>
                <div class="space-y-2 sm:space-y-3">
                  <div class="flex items-center justify-between">
                    <span class="text-gray-700 text-sm sm:text-base">Notifikasi Email</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input type="checkbox" class="sr-only peer" v-model="preferencesForm.email_notifications">
                      <div class="w-10 h-5 sm:w-11 sm:h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[1px] after:left-[1px] sm:after:top-[2px] sm:after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 sm:after:h-5 sm:after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-gray-700 text-sm sm:text-base">Notifikasi SMS</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input type="checkbox" class="sr-only peer" v-model="preferencesForm.sms_notifications">
                      <div class="w-10 h-5 sm:w-11 sm:h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[1px] after:left-[1px] sm:after:top-[2px] sm:after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 sm:after:h-5 sm:after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="mt-4 sm:mt-6 flex justify-end">
              <button 
                @click="updatePreferences" 
                :disabled="preferencesForm.processing" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 sm:px-6 sm:py-2 rounded-lg font-medium disabled:opacity-50 text-sm sm:text-base"
              >
                <span v-if="preferencesForm.processing">Menyimpan...</span>
                <span v-else>Simpan Preferensi</span>
              </button>
            </div>
          </div>

          <!-- Bank Account Section -->
          <div v-if="activeSection === 'bank'" class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
            <h2 class="text-lg font-medium text-gray-800 mb-4 sm:mb-6">Rekening Bank</h2>
            
            <div class="bg-blue-50 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6">
              <p class="text-blue-700 text-xs sm:text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                Informasi rekening bank digunakan untuk proses penarikan dana. Pastikan data yang dimasukkan benar.
              </p>
            </div>
            
            <div class="space-y-3 sm:space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Nama Bank</label>
                <select 
                  v-model="bankForm.bank_name" 
                  class="w-full px-3 py-2 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                >
                  <option value="">Pilih Bank</option>
                  <option value="bca">BCA</option>
                  <option value="bni">BNI</option>
                  <option value="bri">BRI</option>
                  <option value="mandiri">Mandiri</option>
                  <option value="cimb">CIMB Niaga</option>
                  <option value="other">Lainnya</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Nomor Rekening</label>
                <input 
                  type="text" 
                  v-model="bankForm.account_number" 
                  class="w-full px-3 py-2 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base" 
                  placeholder="Contoh: 1234567890"
                >
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Nama Pemilik Rekening</label>
                <input 
                  type="text" 
                  v-model="bankForm.account_holder_name" 
                  class="w-full px-3 py-2 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base" 
                  placeholder="Nama sesuai rekening bank"
                >
              </div>
            </div>
            
            <div class="mt-4 sm:mt-6 flex justify-end">
              <button 
                @click="updateBankInfo" 
                :disabled="bankForm.processing" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 sm:px-6 sm:py-2 rounded-lg font-medium disabled:opacity-50 text-sm sm:text-base"
              >
                <span v-if="bankForm.processing">Menyimpan...</span>
                <span v-else>Simpan Informasi Bank</span>
              </button>
            </div>
          </div>

          <!-- Logout Section -->
          <div v-if="activeSection === 'logout'" class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
            <h2 class="text-lg font-medium text-gray-800 mb-4 sm:mb-6">Keluar</h2>
            
            <div class="text-center py-6 sm:py-8">
              <div class="w-14 h-14 sm:w-16 sm:h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-sign-out-alt text-red-600 text-lg sm:text-xl"></i>
              </div>
              <h3 class="font-medium text-gray-800 mb-2 text-sm sm:text-base">Keluar dari Akun</h3>
              <p class="text-gray-600 mb-4 sm:mb-6 text-xs sm:text-sm">Anda akan keluar dari akun Spectra Anda</p>
              <button 
                @click="logout" 
                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 sm:px-8 sm:py-3 rounded-lg font-medium text-sm sm:text-base"
              >
                Keluar Sekarang
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { usePage, useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue' 

const { props } = usePage()
const user = props.auth?.user || {}

// State untuk mobile menu
const showMobileMenu = ref(false)

// User info - tanpa data dummy
const userName = computed(() => user.name || '')
const userCode = computed(() => user.referral_code || '')
const userInitials = computed(() => {
  if (!userName.value) return ''
  return userName.value.split(' ').map(n => n[0]).join('').toUpperCase()
})

// Active section
const activeSection = ref('security')

// Dapatkan label section aktif untuk mobile
const activeSectionLabel = computed(() => {
  const item = menuItems.find(item => item.id === activeSection.value)
  return item ? item.label : 'Menu'
})

// Menu items
const menuItems = [
  { id: 'security', label: 'Keamanan', icon: 'fas fa-shield-alt' },
  { id: 'preferences', label: 'Preferensi', icon: 'fas fa-cog' },
  { id: 'bank', label: 'Rekening Bank', icon: 'fas fa-university' },
  { id: 'logout', label: 'Keluar', icon: 'fas fa-sign-out-alt' }
]

// Form data menggunakan useForm
const securityForm = useForm({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
})

const preferencesForm = useForm({
  dark_mode: user.dark_mode || false,
  email_notifications: user.email_notifications || true,
  sms_notifications: user.sms_notifications || false
})

const bankForm = useForm({
  bank_name: user.bank_name || '',
  account_number: user.account_number || '',
  account_holder_name: user.account_holder_name || ''
})

// Methods
function setActiveSection(section) {
  activeSection.value = section
  // Pada mobile, tutup menu setelah memilih
  if (window.innerWidth < 1024) {
    showMobileMenu.value = false
  }
}

function updateSecurity() {
  securityForm.post(route('pengaturan.security'), {
    preserveScroll: true,
    onSuccess: () => {
      securityForm.reset()
    },
    onError: (errors) => {
      console.log('Error updating security:', errors)
    }
  })
}

function updatePreferences() {
  preferencesForm.post(route('pengaturan.preferences'), {
    preserveScroll: true,
    onSuccess: () => {
      // Preferensi berhasil disimpan
    },
    onError: (errors) => {
      console.log('Error updating preferences:', errors)
    }
  })
}

function updateBankInfo() {
  bankForm.post(route('pengaturan.bank'), {
    preserveScroll: true,
    onSuccess: () => {
      // Info bank berhasil disimpan
    },
    onError: (errors) => {
      console.log('Error updating bank info:', errors)
    }
  })
}

function logout() {
  if (confirm('Apakah Anda yakin ingin keluar?')) {
    router.post(route('logout'))
  }
}

// Tutup menu mobile saat resize ke desktop
onMounted(() => {
  const handleResize = () => {
    if (window.innerWidth >= 1024) {
      showMobileMenu.value = false
    }
  }

  window.addEventListener('resize', handleResize)
})
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
</style>