<template>
  <!-- Form deposit jika belum ada deposit -->
  <div
    v-if="show && !hasPending && !hasConfirmed"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
  >
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
      <h2 class="text-xl font-bold mb-4">Aktivasi Akun Konsultan</h2>
      <p class="mb-4">
        Untuk mengaktifkan akun konsultan, lakukan pembayaran deposit sebesar
        <b>10 {{ preferences.currency || 'USDT' }}</b>.
      </p>

      <p>Silakan transfer ke:</p>
      <ul class="list-disc ml-6 mb-4" v-if="preferences.bank_name && preferences.bank_number">
        <li>Bank: {{ preferences.bank_name }}</li>
        <li>No Rek: {{ preferences.bank_number }}</li>
      </ul>

      <div v-if="preferences.bank_qr" class="mb-4">
        <p>Atau scan QR berikut:</p>
        <img
          :src="`/storage/${preferences.bank_qr}`"
          alt="QR Code"
          class="w-40 h-40 object-contain mt-2"
        />
      </div>

      <!-- Upload bukti -->
      <input type="file" @change="onUpload" class="mt-2 mb-2" />

      <!-- Preview gambar -->
      <div v-if="previewUrl" class="mb-2">
        <p class="text-sm mb-1">Preview:</p>
        <img :src="previewUrl" alt="Preview" class="w-32 h-32 object-contain border" />
      </div>

      <!-- Error message -->
      <p v-if="errorMsg" class="text-red-600 text-sm mb-2">{{ errorMsg }}</p>

      <div class="flex justify-end gap-2">
        <button
          class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50"
          :disabled="!file || uploading"
          @click="submitProof"
        >
          <span v-if="uploading">Mengirim...</span>
          <span v-else>Kirim Bukti Transfer</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Notifikasi jika sudah ada pending deposit (tanpa tombol tutup) -->
  <div v-if="hasPending" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full text-center">
      <p class="mb-4 text-red-600 font-semibold">
        Anda sudah memiliki deposit yang menunggu konfirmasi admin.
      </p>
    </div>
  </div>

  <!-- Notifikasi jika sudah dikonfirmasi admin -->
  <div v-if="hasConfirmed && !closedConfirmed" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full text-center">
      <p class="mb-4 text-green-600 font-semibold">
        Deposit anda telah dikonfirmasi admin.
      </p>
      <button
        class="bg-gray-600 text-white px-4 py-2 rounded"
        @click="closeConfirmed"
      >
        Tutup
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  show: Boolean,
})

const emit = defineEmits(['close'])

const file = ref(null)
const previewUrl = ref(null)
const preferences = ref({})
const errorMsg = ref('')
const uploading = ref(false)
const hasPending = ref(false)
const hasConfirmed = ref(false)
const closedConfirmed = ref(false) // state popup konfirmasi

// Load company preferences
async function loadPreferences() {
  try {
    const { data } = await axios.get('/company-preferences')
    preferences.value = data
  } catch (err) {
    console.error('Gagal ambil company preferences', err)
  }
}

// Cek status deposit user
async function checkDeposit() {
  try {
    const { data } = await axios.get('/investor/deposit/check-pending')

    hasPending.value = data.hasPending
    hasConfirmed.value = data.hasConfirmed

    // kalau sudah confirmed, cek apakah sebelumnya popup sudah ditutup
    if (data.hasConfirmed) {
      const dismissed = localStorage.getItem('depositConfirmedDismissed')
      closedConfirmed.value = dismissed === 'true'
    }
  } catch (err) {
    console.error('Gagal cek status deposit', err)
  }
}

// File selected
function onUpload(e) {
  const selected = e.target.files[0]
  file.value = null
  previewUrl.value = null
  errorMsg.value = ''

  if (!selected) return

  const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf']
  if (!allowedTypes.includes(selected.type)) {
    errorMsg.value = 'File harus JPG, JPEG, PNG, atau PDF'
    return
  }

  if (selected.size > 2 * 1024 * 1024) {
    errorMsg.value = 'Ukuran file maksimal 2MB'
    return
  }

  file.value = selected

  if (selected.type.startsWith('image/')) {
    const reader = new FileReader()
    reader.onload = e => (previewUrl.value = e.target.result)
    reader.readAsDataURL(selected)
  }
}

// Submit file
async function submitProof() {
  if (!file.value) {
    alert('Pilih file bukti transfer terlebih dahulu')
    return
  }

  const formData = new FormData()
  formData.append('file', file.value)

  uploading.value = true
  errorMsg.value = ''

  try {
    const response = await axios.post('/investor/deposit/proof', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    alert(response.data.message || 'Bukti transfer berhasil dikirim.')
    emit('close')
  } catch (err) {
    console.error(err)
    if (err.response?.data?.message) {
      errorMsg.value = err.response.data.message
    } else if (err.response?.data?.errors?.file) {
      errorMsg.value = err.response.data.errors.file.join(', ')
    } else {
      errorMsg.value = 'Gagal mengirim bukti transfer.'
    }
  } finally {
    uploading.value = false
  }
}

// Tutup popup konfirmasi (hanya sekali)
function closeConfirmed() {
  closedConfirmed.value = true
  localStorage.setItem('depositConfirmedDismissed', 'true')
}

onMounted(() => {
  loadPreferences()
  checkDeposit()
})
</script>