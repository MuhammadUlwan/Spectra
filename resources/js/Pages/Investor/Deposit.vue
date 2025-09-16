<script setup>
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import InvestorLayout from '@/Layouts/InvestorLayout.vue'

const { props } = usePage()
const user = props.user || {}
const settings = props.settings || {}
const packages = Array.isArray(props.packages) ? props.packages : []
const bankInfo = props.bankInfo || {} // 🟢 data bank dari user_preferences admin

const selectedPackage = ref(null)
const transferFile = ref(null)
const loading = ref(false)

function selectPackage(pkg) {
  selectedPackage.value = selectedPackage.value?.id === pkg.id ? null : pkg
}

function formatCurrency(amount) {
  if (!amount) return '0'
  return parseFloat(amount).toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })
}

function onFileChange(e) {
  transferFile.value = e.target.files[0] || null
}

function submitDeposit() {
  if (!selectedPackage.value) return alert('Paket wajib dipilih.')
  if (!transferFile.value) return alert('Bukti transfer wajib diunggah.')
  loading.value = true

  const formData = new FormData()
  formData.append('package_id', selectedPackage.value.id)
  formData.append('transfer_file', transferFile.value)
  formData.append('transfer_date', new Date().toISOString().slice(0, 10))

  router.post('/deposit', formData, {
    onSuccess: () => {
      alert('Deposit berhasil! Menunggu konfirmasi admin.')
      selectedPackage.value = null
      transferFile.value = null
      router.visit('/dashboard')
    },
    onError: () => {
      alert('Terjadi kesalahan saat mengirim deposit.')
      loading.value = false
    }
  })
}

// 🟢 Hitung estimasi profit
const profitEstimation = computed(() => {
  if (!selectedPackage.value) return null
  const { amount, profit_percent } = selectedPackage.value
  const monthlyRate = profit_percent / 100
  return {
    halfMonth: amount * (monthlyRate / 2),
    oneMonth: amount * monthlyRate,
    sixMonths: amount * monthlyRate * 6
  }
})
</script>

<template>
  <InvestorLayout :user="user" :profileUrl="profileUrl" :logoutUrl="logoutUrl">
    <div class="min-h-screen p-4 md:p-6 flex flex-col items-center">

      <!-- Header -->
      <div class="w-full max-w-4xl mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Deposit</h1>
        <p class="text-gray-500 text-sm md:text-base">
          Pilih satu paket deposit untuk memulai investasi
        </p>
      </div>

      <!-- Paket Deposit -->
      <div class="w-full max-w-4xl mb-6" v-if="packages.length">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div
            v-for="pkg in packages"
            :key="pkg.id"
            class="p-4 rounded-xl border cursor-pointer flex flex-col transition-all duration-200"
            :class="
              selectedPackage?.id === pkg.id
                ? 'border-blue-500 bg-blue-50 shadow-md scale-[1.02]'
                : 'border-gray-200 bg-white'
            "
            @click="selectPackage(pkg)"
          >
            <h3 class="font-semibold text-gray-800">{{ pkg.name }}</h3>
            <p class="text-blue-600 font-bold text-lg">
              {{ formatCurrency(pkg.amount) }} USDT
            </p>
            <p class="text-sm text-gray-500">
              {{ pkg.profit_percent }}% / bulan
            </p>
          </div>
        </div>
      </div>
      <div v-else class="text-gray-500 mt-4">
        Belum ada paket deposit tersedia.
      </div>

      <!-- Total + Upload Bukti -->
      <div class="w-full max-w-4xl mb-6" v-if="selectedPackage">
        <div
          class="bg-gradient-to-br from-blue-600 to-green-500 rounded-xl p-5 shadow-md text-white"
        >
          <div class="flex justify-between items-center mb-4">
            <span class="font-medium">Total Deposit</span>
            <span class="text-xl font-bold">
              {{ formatCurrency(selectedPackage.amount) }} USDT
            </span>
          </div>

          <!-- Estimasi Profit -->
          <div
            class="bg-white/20 p-3 rounded-lg mb-4"
            v-if="profitEstimation"
          >
            <p class="text-sm font-medium mb-2">Estimasi Profit:</p>
            <ul class="text-sm space-y-1">
              <li>
                15 Hari:
                <span class="font-semibold">{{
                  formatCurrency(profitEstimation.halfMonth)
                }} USDT</span>
              </li>
              <li>
                1 Bulan:
                <span class="font-semibold">{{
                  formatCurrency(profitEstimation.oneMonth)
                }} USDT</span>
              </li>
              <li>
                6 Bulan:
                <span class="font-semibold">{{
                  formatCurrency(profitEstimation.sixMonths)
                }} USDT</span>
              </li>
            </ul>
          </div>

          <!-- Info Rekening / QR -->
          <div class="bg-white/20 p-3 rounded-lg mb-4">
            <p class="text-sm mb-1">
              Bank: {{ bankInfo.bank_name || settings.bank_name || '-' }}
            </p>
            <p class="text-sm mb-1">
              No Rekening: {{ bankInfo.bank_number || settings.bank_account || '-' }}
            </p>
          <div v-if="bankInfo.bank_qr || settings.qr_code">
            <img
              :src="bankInfo.bank_qr ? `/storage/${bankInfo.bank_qr}` : `/storage/${settings.qr_code}`"
              alt="QR Code"
              class="w-32 h-32 object-contain mt-2 rounded-lg bg-white p-2"
            />
          </div>
          <p v-else class="text-xs text-blue-100">QR Code belum tersedia</p>
          </div>

          <!-- Upload Bukti Transfer -->
          <div class="mb-4">
            <label class="block text-white font-medium mb-2">Bukti Transfer</label>
            <input
              type="file"
              @change="onFileChange"
              class="w-full rounded-lg p-2 text-gray-800"
            />
          </div>

          <button
            @click="submitDeposit"
            :disabled="loading"
            class="w-full py-3 rounded-lg font-semibold transition-all bg-white text-blue-600 hover:shadow-lg hover:scale-[1.02]"
          >
            Lanjutkan Deposit
          </button>
        </div>
      </div>
    </div>
  </InvestorLayout>
</template>