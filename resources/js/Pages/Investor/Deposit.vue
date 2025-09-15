<template>
  <InvestorLayout :user="user" :profileUrl="profileUrl" :logoutUrl="logoutUrl">
    <div class="min-h-screen p-4 md:p-6 flex flex-col items-center">

      <!-- Header -->
      <div class="w-full max-w-4xl mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
          <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Deposit</h1>
          <p class="text-gray-500 text-sm md:text-base">Pilih paket deposit untuk memulai investasi</p>
        </div>
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
              <p class="text-blue-600 font-bold text-lg mb-2">{{ formatCurrency(pkg.amount) }} USDT</p>
              
              <div class="flex items-center text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full w-fit mb-1">
                <i class="fas fa-chart-line mr-1.5"></i>
                <span>{{ pkg.profit_percent }}% / bulan</span>
              </div>

              <div class="flex items-center text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full w-fit">
                <i class="fas fa-clock mr-1.5"></i>
                <span>15 hari: {{ profit15days(pkg.amount) }} USDT</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-gray-500 mt-4">Belum ada paket deposit tersedia.</div>

      <!-- Total + Upload Bukti -->
      <div class="w-full max-w-4xl mb-6" v-if="Object.keys(selectedPackages).length">
        <div class="bg-gradient-to-br from-blue-600 to-green-500 rounded-xl p-5 flex flex-col shadow-md text-white">
          <div class="flex justify-between items-center mb-2">
            <span class="font-medium">Total Deposit</span>
            <span class="text-xl font-bold">{{ formatCurrency(totalAmount) }} USDT</span>
          </div>
          <p class="text-xs text-blue-100 mb-4">Durasi kontrak: {{ totalDuration }} bulan</p>
          <div class="bg-white/20 p-3 rounded-lg mb-4">
            <div class="flex justify-between text-xs text-white mb-1">
              <span>Estimasi keuntungan/bulan:</span>
              <span>{{ formatCurrency(estimatedProfitPerMonth) }} USDT</span>
            </div>
            <div class="flex justify-between text-xs text-white">
              <span>Total estimasi keuntungan:</span>
              <span>{{ formatCurrency(estimatedProfitTotal) }} USDT</span>
            </div>
          </div>

          <!-- Upload Bukti Transfer -->
          <div class="mb-4">
            <label class="block text-white font-medium mb-2">Bukti Transfer</label>
            <input type="file" @change="onFileChange" class="w-full rounded-lg p-2 text-gray-800" />
          </div>

          <button
            @click="submitDeposit"
            :disabled="loading"
            class="w-full py-3 rounded-lg font-semibold transition-all duration-200 text-sm flex items-center justify-center bg-white text-blue-600 hover:shadow-lg transform hover:scale-[1.02]"
          >
            <i class="fas fa-lock mr-2"></i>
            Lanjutkan Deposit
          </button>
        </div>
      </div>

    </div>
  </InvestorLayout>
</template>

<script setup>
import { reactive, computed, ref } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import InvestorLayout from '@/Layouts/InvestorLayout.vue'

const { props } = usePage()
const user = props.user || {}
const settings = props.settings || {}
const packages = Array.isArray(props.packages) ? props.packages.map(pkg => ({
  ...pkg,
  duration: pkg.duration_months,
  profit_percent: parseFloat(settings.profit_percent) || 7.5
})) : []

const selectedPackages = reactive({})
const transferFile = ref(null)
const loading = ref(false)

function isPackageSelected(pkgId) { return !!selectedPackages[pkgId] }
function togglePackage(pkg) {
  if (!isPackageSelected(pkg.id)) selectedPackages[pkg.id] = {}
  else delete selectedPackages[pkg.id]
}

function formatCurrency(amount) {
  if (!amount) return '0'
  return parseFloat(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

function profit15days(amount) {
  return (amount * (parseFloat(settings.profit_percent_15days) || 3.25) / 100).toFixed(2)
}

const totalAmount = computed(() => Object.keys(selectedPackages).reduce((sum, id) => {
  const pkg = packages.find(p => p.id == id)
  return sum + (pkg?.amount || 0)
}, 0))

const totalDuration = computed(() => Math.max(...Object.keys(selectedPackages).map(id => packages.find(p => p.id == id)?.duration || 0), 0))
const estimatedProfitPerMonth = computed(() => Object.keys(selectedPackages).reduce((sum, id) => {
  const pkg = packages.find(p => p.id == id)
  return sum + ((pkg?.amount || 0) * (pkg?.profit_percent || 0) / 100)
}, 0))
const estimatedProfitTotal = computed(() => estimatedProfitPerMonth.value * totalDuration.value)

function onFileChange(e) {
  transferFile.value = e.target.files[0] || null
}

function submitDeposit() {
  if (!transferFile.value) return alert('Bukti transfer wajib diunggah.')
  loading.value = true

  const formData = new FormData()
  formData.append('package_ids', JSON.stringify(Object.keys(selectedPackages)))
  formData.append('transfer_file', transferFile.value)
  formData.append('transfer_date', new Date().toISOString().slice(0,10))

  router.post('/deposit', formData, {
    onSuccess: () => {
      // Notification sederhana menggunakan alert browser
      alert('Deposit berhasil! Menunggu konfirmasi admin.')
      // reset pilihan
      for (const key of Object.keys(selectedPackages)) delete selectedPackages[key]
      transferFile.value = null
      router.visit('/dashboard')
    },
    onError: () => {
      alert('Terjadi kesalahan saat mengirim deposit.')
      loading.value = false
    }
  })
}
</script>

<style scoped>
input[type="file"] {
  border: 1px solid #ccc;
  background: white;
}
</style>