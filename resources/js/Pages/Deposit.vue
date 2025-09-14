<template>
  <InvestorLayout :user="user" :profileUrl="profileUrl" :logoutUrl="logoutUrl">
    <div class="min-h-screen p-4 md:p-6 flex flex-col items-center">

      <!-- Header internal -->
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
      <div class="w-full max-w-4xl mb-6" v-if="packages.length">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg md:text-xl font-semibold text-gray-800">Pilih Paket Deposit</h2>
          <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ Object.keys(selectedPackages).length }} terpilih</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div
            v-for="pkg in packages"
            :key="pkg.id"
            class="p-4 rounded-xl border cursor-pointer flex flex-col justify-between transition-all duration-200 text-sm hover:shadow-sm"
            :class="isPackageSelected(pkg.id) ? 'border-blue-500 bg-blue-50 shadow-md transform scale-[1.02]' : 'border-gray-200 bg-white'"
            @click="togglePackage(pkg)"
          >
            <div>
              <div class="flex justify-between items-start mb-2">
                <h3 class="font-semibold text-gray-800">{{ pkg.name }}</h3>
                <div :class="['w-5 h-5 rounded-full border flex items-center justify-center transition-colors',
                              isPackageSelected(pkg.id) ? 'bg-blue-500 border-blue-500' : 'bg-white border-gray-300']">
                  <i v-if="isPackageSelected(pkg.id)" class="fas fa-check text-white text-xs"></i>
                </div>
              </div>
              <p class="text-blue-600 font-bold text-lg mb-2">Rp {{ formatCurrency(pkg.amount) }}</p>
              <div class="flex items-center text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full w-fit">
                <i class="fas fa-chart-line mr-1.5"></i>
                <span>{{ pkg.profit_percent }}% / bulan</span>
              </div>
            </div>

            <div class="mt-3 pt-2 border-t border-gray-100">
              <label class="block text-gray-700 text-xs mb-1">Jumlah Deposit</label>
              <input
                type="number"
                v-model.number="selectedPackages[pkg.id]?.amount"
                class="w-full border rounded px-2 py-1 text-sm"
                placeholder="Rp"
              />
              <label class="block text-gray-700 text-xs mt-2 mb-1">Upload Bukti</label>
              <input type="file" @change="handleFile($event, pkg.id)" class="w-full text-xs" />
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-gray-500 mt-4">Belum ada paket deposit tersedia.</div>

      <!-- Total + Submit -->
      <div class="w-full max-w-4xl mb-6" v-if="Object.keys(selectedPackages).length">
        <div class="bg-gradient-to-br from-blue-600 to-green-500 rounded-xl p-5 flex flex-col shadow-md text-white">
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
          <button
            @click="submitDeposit"
            class="w-full py-3 rounded-lg font-semibold transition-all duration-200 text-sm flex items-center justify-center bg-white text-blue-600 hover:shadow-lg transform hover:scale-[1.02]"
          >
            <i class="fas fa-lock mr-2"></i>
            Lanjutkan Deposit
          </button>
        </div>
      </div>

      <!-- Notifikasi -->
      <transition name="fade">
        <div v-if="message" :class="['w-full max-w-4xl mt-2 p-4 rounded-xl text-sm flex items-center',
                                    messageType === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200']">
          <i :class="[messageType === 'success' ? 'fas fa-check-circle mr-3 text-lg' : 'fas fa-exclamation-circle mr-3 text-lg']"></i>
          <div><p class="font-medium">{{ message }}</p></div>
        </div>
      </transition>

    </div>
  </InvestorLayout>
</template>

<script setup>
import { reactive, computed, ref } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import InvestorLayout from '@/Layouts/InvestorLayout.vue';

const { props } = usePage()

// fallback supaya tidak blank page
const user = props.user || { wallet: 0 }
const packages = Array.isArray(props.packages) ? props.packages.map(pkg => ({
  ...pkg,
  duration: pkg.duration_months,
  profit_percent: pkg.profit_percent
})) : []

const profileUrl = props.profileUrl || '/profile'
const logoutUrl = props.logoutUrl || '/logout'

const selectedPackages = reactive({}) // { pkgId: { amount, file } }
const message = ref('')
const messageType = ref('')

// Helper functions
function isPackageSelected(pkgId) { return !!selectedPackages[pkgId] }
function togglePackage(pkg) {
  if (!isPackageSelected(pkg.id)) selectedPackages[pkg.id] = { amount: pkg.amount || 0, file: null }
  else delete selectedPackages[pkg.id]
}
function handleFile(event, pkgId) {
  if (selectedPackages[pkgId]) selectedPackages[pkgId].file = event.target.files[0]
}
function formatCurrency(amount) { return new Intl.NumberFormat('id-ID').format(amount || 0) }

// Computed totals
const totalAmount = computed(() => Object.values(selectedPackages).reduce((sum, p) => sum + (p.amount || 0), 0))
const totalDuration = computed(() => {
  if (!Object.keys(selectedPackages).length) return 0
  const durations = Object.keys(selectedPackages).map(id => packages.find(p => p.id == id)?.duration || 0)
  return Math.round(durations.reduce((a,b)=>a+b,0) / durations.length)
})
const estimatedProfitPerMonth = computed(() => Object.keys(selectedPackages).reduce((sum, id) => {
  const pkg = packages.find(p => p.id == id)
  return sum + ((pkg?.amount || 0) * (pkg?.profit_percent || 0)/100)
}, 0))
const estimatedProfitTotal = computed(() => estimatedProfitPerMonth.value * totalDuration.value)

// Submit deposit
function submitDeposit() {
  if (!Object.keys(selectedPackages).length) {
    message.value = 'Harap pilih paket dan isi jumlah deposit.'
    messageType.value = 'error'
    return
  }

  const formData = new FormData()
  // Sesuaikan key supaya sesuai Laravel DepositController
  for (const [id, data] of Object.entries(selectedPackages)) {
    formData.append('package_id', id)
    formData.append('amount', data.amount)
    if (data.file) formData.append('proof_transfer', data.file)
  }

  router.post(route('deposit.store'), formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
    onSuccess: () => {
      message.value = 'Deposit berhasil! Menunggu konfirmasi admin.'
      messageType.value = 'success'
      for (const key of Object.keys(selectedPackages)) delete selectedPackages[key]
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
.fade-enter-active, .fade-leave-active { transition: opacity 0.5s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
