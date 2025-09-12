<template>
  <AppLayout>
  <div class="min-h-screen bg-gray-50 p-4 md:p-6 flex flex-col items-center">

    <!-- Header -->
    <div class="w-full max-w-4xl mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Tarik Tunai</h1>
        <p class="text-gray-500 text-sm md:text-base">Tarik dana dari saldo ke rekening Anda</p>
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
        Rp {{ formatCurrency(user?.wallet ?? 0) }}
      </span>
    </div>

    <!-- Form Tarik Tunai -->
    <div class="w-full max-w-4xl bg-gradient-to-br from-blue-500 to-green-400 rounded-xl p-6 shadow-md text-white">
      <div class="mb-4">
        <label class="block font-semibold mb-2">Jumlah Tarik Tunai</label>
        <input
          type="number"
          v-model.number="withdrawAmount"
          :min="1"
          :max="user?.wallet ?? 0"
          placeholder="Masukkan jumlah"
          class="w-full p-3 rounded-lg text-black font-medium focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-300"
        />
        <p class="text-sm mt-1 text-white/80">Maks: Rp {{ formatCurrency(user?.wallet ?? 0) }}</p>
      </div>

      <div class="mb-4">
        <label class="block font-semibold mb-2">Metode Penarikan</label>
        <select
          v-model="selectedMethod"
          class="w-full p-3 rounded-lg text-black font-medium focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-300 appearance-none"
        >
          <option value="" disabled>Pilih metode penarikan</option>
          <option v-for="method in paymentMethods ?? []" :key="method?.id" :value="method?.id">
            {{ method?.name }}
          </option>
        </select>
      </div>

      <div class="flex justify-between items-center mt-6">
        <button
          @click="submitTarikTunai"
          :disabled="!canSubmit"
          :class="[ 
            'px-6 py-3 rounded-lg font-semibold text-sm flex items-center justify-center transition-all duration-200',
            canSubmit ? 'bg-white text-green-600 hover:shadow-lg transform hover:scale-[1.02]' : 'bg-white/30 text-white cursor-not-allowed' 
          ]"
        >
          <i class="fas fa-paper-plane mr-2"></i> Tarik Sekarang
        </button>
        <span class="font-medium text-lg">Total: Rp {{ formatCurrency(withdrawAmount) }}</span>
      </div>
    </div>

    <!-- Notifikasi -->
    <transition name="fade">
      <div v-if="message" :class="[ 
        'w-full max-w-4xl mt-4 p-4 rounded-xl text-sm flex items-center',
        messageType === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'
      ]">
        <i :class="[ 'mr-3 text-lg', messageType === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle' ]"></i>
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

// Aman walau backend belum siap
const user = props.auth?.user ?? {}
const paymentMethods = props.paymentMethods ?? []

const withdrawAmount = ref(0)
const selectedMethod = ref('')
const message = ref('')
const messageType = ref('')

// Tombol aktif jika ada jumlah dan metode
const canSubmit = computed(() =>
  withdrawAmount.value > 0 &&
  withdrawAmount.value <= (user?.wallet ?? 0) &&
  selectedMethod.value
)

function formatCurrency(amount) {
  return new Intl.NumberFormat('id-ID').format(amount ?? 0)
}

// Submit aman walau backend belum siap
function submitTarikTunai() {
  if (!canSubmit.value) {
    message.value = 'Isi jumlah dan pilih metode'
    messageType.value = 'error'
    return
  }

  router.post('/tarik-tunai', {
    amount: withdrawAmount.value,
    method_id: selectedMethod.value
  }, {
    onSuccess: () => {
      message.value = 'Permintaan tarik tunai berhasil! Menunggu konfirmasi admin.'
      messageType.value = 'success'
      withdrawAmount.value = 0
      selectedMethod.value = ''
    },
    onError: (errors) => {
      message.value = errors?.message || 'Terjadi kesalahan'
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