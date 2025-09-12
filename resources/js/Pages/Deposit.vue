<template>
  <AppLayout>
  <div class="min-h-screen bg-gray-50 p-4 md:p-6 flex flex-col items-center">
    <!-- Header -->
    <div class="w-full max-w-4xl mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Deposit</h1>
        <p class="text-gray-500 text-sm md:text-base">Pilih paket deposit untuk memulai investasi</p>
      </div>
      <button
        @click="$inertia.visit('/dashboard')"
        class="bg-white hover:bg-gray-100 text-gray-800 px-4 py-2 rounded-lg text-sm flex items-center shadow-sm border border-gray-200 transition-all duration-200 hover:shadow-md"
      >
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
      </button>
    </div>

    <!-- Saldo Card -->
    <div class="w-full max-w-4xl bg-white rounded-xl p-5 mb-6 shadow-md flex justify-between items-center border border-gray-200">
      <div class="flex items-center gap-3">
        <div class="bg-blue-100 p-3 rounded-lg">
          <i class="fas fa-wallet text-blue-600"></i>
        </div>
        <span class="text-gray-600 font-medium">Saldo Tersedia</span>
      </div>
      <span class="text-xl md:text-2xl font-semibold text-gray-800">
        Rp {{ formatCurrency(user.wallet || 0) }}
      </span>
    </div>

    <!-- Paket Deposit -->
    <div class="w-full max-w-4xl mb-6">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg md:text-xl font-semibold text-gray-800">Pilih Paket Deposit</h2>
        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ selectedPackages.length }} terpilih</span>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div
          v-for="pkg in packages"
          :key="pkg.id"
          @click="togglePackage(pkg)"
          :class="[
            'p-4 rounded-xl border cursor-pointer flex flex-col justify-between transition-all duration-200 text-sm hover:shadow-sm',
            isPackageSelected(pkg.id) ? 'border-blue-500 bg-blue-50 shadow-md transform scale-[1.02]' : 'border-gray-200 bg-white'
          ]"
        >
          <div>
            <div class="flex justify-between items-start mb-2">
              <h3 class="font-semibold text-gray-800">{{ pkg.name }}</h3>
              <div :class="[
                'w-5 h-5 rounded-full border flex items-center justify-center transition-colors',
                isPackageSelected(pkg.id) ? 'bg-blue-500 border-blue-500' : 'bg-white border-gray-300'
              ]">
                <i v-if="isPackageSelected(pkg.id)" class="fas fa-check text-white text-xs"></i>
              </div>
            </div>
            <p class="text-blue-600 font-bold text-lg mb-2">Rp {{ formatCurrency(pkg.amount) }}</p>
            <div class="flex items-center text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full w-fit">
              <i class="fas fa-chart-line mr-1.5"></i>
              <span>{{ pkg.interest_rate }}% / bulan</span>
            </div>
          </div>
          <div class="mt-3 pt-2 border-t border-gray-100">
            <p class="text-xs text-gray-500">Durasi: {{ pkg.duration }} bulan</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Upload Bukti Pembayaran & Total -->
    <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
      <!-- Upload Bukti -->
      <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <label class="block text-gray-700 font-semibold mb-3 text-sm flex items-center">
          <i class="fas fa-file-upload mr-2 text-blue-500"></i>
          Upload Bukti Pembayaran
        </label>
        <input
          type="file"
          @change="handleFileUpload"
          accept="image/*,application/pdf"
          class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        />
        <p v-if="uploadedFileName" class="mt-2 text-xs text-gray-600">
          File terpilih: {{ uploadedFileName }}
        </p>
        <p class="mt-2 text-xs text-gray-500">
          Hanya file gambar atau PDF. Maksimum 5MB.
        </p>
      </div>

      <!-- Total Deposit + Submit -->
      <div class="bg-gradient-to-br from-blue-600 to-green-500 rounded-xl p-5 flex flex-col justify-between shadow-md text-white">
        <div>
          <div class="flex justify-between items-center mb-2">
            <span class="font-medium">Total Deposit</span>
            <span class="text-xl font-bold">Rp {{ formatCurrency(totalAmount) }}</span>
          </div>
          <p class="text-xs text-blue-100 mb-4">Durasi kontrak: {{ totalDuration }} bulan</p>
          <div class="bg-white/20 p-3 rounded-lg mb-4">
            <div class="flex justify-between text-xs text-white mb-1">
              <span>Estimasi keuntungan/bulan:</span>
              <span>Rp {{ formatCurrency(estimatedProfitPerMonth) }}</span>
            </div>
            <div class="flex justify-between text-xs text-white">
              <span>Total estimasi keuntungan:</span>
              <span>Rp {{ formatCurrency(estimatedProfitTotal) }}</span>
            </div>
          </div>
        </div>
        <button
          @click="submitDeposit"
          :disabled="!canSubmit || !uploadedFile"
          :class="[
            'w-full py-3 rounded-lg font-semibold transition-all duration-200 text-sm flex items-center justify-center',
            canSubmit && uploadedFile ? 'bg-white text-blue-600 hover:shadow-lg transform hover:scale-[1.02]' : 'bg-white/30 text-white cursor-not-allowed'
          ]"
        >
          <i class="fas fa-lock mr-2"></i>
          Lanjutkan Deposit
        </button>
      </div>
    </div>

    <!-- Notifikasi -->
    <transition name="fade">
      <div v-if="message" :class="[ 
        'w-full max-w-4xl mt-2 p-4 rounded-xl text-sm flex items-center',
        messageType === 'success' 
          ? 'bg-green-100 text-green-800 border border-green-200' 
          : 'bg-red-100 text-red-800 border border-red-200'
      ]">
        <i :class="[ 
          'mr-3 text-lg', 
          messageType === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle' 
        ]"></i>
        <div>
          <p class="font-medium">{{ message }}</p>
        </div>
      </div>
    </transition>
  </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import AppLayout from "@/Layouts/AppLayout.vue"

const { props } = usePage()
const user = props.auth?.user || {}
const packages = props.packages || []

const selectedPackages = ref([])
const uploadedFile = ref(null)
const uploadedFileName = ref('')
const message = ref('')
const messageType = ref('')

// Total deposit
const totalAmount = computed(() => selectedPackages.value.reduce((sum, pkg) => sum + pkg.amount, 0))
const totalDuration = computed(() => {
  if (!selectedPackages.value.length) return 0
  const total = selectedPackages.value.reduce((sum, pkg) => sum + (pkg.duration || 0), 0)
  return Math.round(total / selectedPackages.value.length)
})

// Estimasi keuntungan
const estimatedProfitPerMonth = computed(() => {
  return selectedPackages.value.reduce((sum, pkg) => sum + ((pkg.amount || 0) * (pkg.interest_rate || 0) / 100), 0)
})
const estimatedProfitTotal = computed(() => estimatedProfitPerMonth.value * totalDuration.value)

const canSubmit = computed(() => selectedPackages.value.length > 0)

function isPackageSelected(pkgId) {
  return selectedPackages.value.some(pkg => pkg.id === pkgId)
}

function togglePackage(pkg) {
  if (isPackageSelected(pkg.id)) {
    selectedPackages.value = selectedPackages.value.filter(p => p.id !== pkg.id)
  } else {
    selectedPackages.value.push(pkg)
  }
}

function handleFileUpload(event) {
  const file = event.target.files[0]
  if (!file) return

  if (file.size > 5 * 1024 * 1024) {
    message.value = 'Ukuran file terlalu besar, maksimal 5MB.'
    messageType.value = 'error'
    uploadedFile.value = null
    uploadedFileName.value = ''
    return
  }

  uploadedFile.value = file
  uploadedFileName.value = file.name
}

function formatCurrency(amount) {
  return new Intl.NumberFormat('id-ID').format(amount)
}

function submitDeposit() {
  if (!canSubmit.value || !uploadedFile.value) {
    message.value = 'Harap pilih paket dan upload bukti pembayaran.'
    messageType.value = 'error'
    return
  }

  const formData = new FormData()
  formData.append('package_ids', selectedPackages.value.map(pkg => pkg.id).join(','))
  formData.append('proof', uploadedFile.value)

  router.post('/deposit', formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
    onSuccess: () => {
      message.value = 'Deposit berhasil! Menunggu konfirmasi admin.'
      messageType.value = 'success'
      selectedPackages.value = []
      uploadedFile.value = null
      uploadedFileName.value = ''
    },
    onError: (errors) => {
      message.value = errors.message || 'Terjadi kesalahan'
      messageType.value = 'error'
    }
  })
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>