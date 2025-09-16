\<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-indigo-100 p-4">
    <div class="flex flex-col md:flex-row w-full max-w-4xl bg-white rounded-2xl shadow-xl overflow-hidden">
      
      <!-- Kiri: Ilustrasi -->
      <div class="md:w-2/5 bg-gradient-to-br from-green-600 to-indigo-700 text-white p-8 flex flex-col justify-center items-center">
        <h1 class="text-3xl font-bold mb-2 text-center">Bergabung dengan Spectra</h1>
        <p class="text-sm opacity-90 mb-6 text-center">Buat akun baru untuk mengakses dashboard</p>
        <img :src="logo" alt="Logo Spectra" class="h-24 w-24 mb-6 object-contain rounded-full shadow-lg" />
        <p class="text-sm text-center opacity-90">
          Sudah punya akun? 
          <Link href="/login" class="underline font-semibold hover:opacity-80 transition-opacity">Masuk sekarang</Link>
        </p>
      </div>

      <!-- Kanan: Form Register -->
      <div class="md:w-3/5 p-8 md:p-10">
        <h2 class="text-2xl md:text-3xl font-bold mb-2 text-gray-800 text-center">Daftar Akun</h2>
        <p class="text-gray-600 text-sm md:text-base mb-6 text-center">Isi formulir di bawah untuk membuat akun baru</p>

        <form @submit.prevent="submit" class="space-y-4">

          <!-- Nama -->
          <div>
            <label class="block text-gray-700 text-sm font-medium mb-1">Nama Lengkap</label>
            <input type="text" v-model="form.name" placeholder="Nama Lengkap"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{'border-red-500': errors.name}" @input="clearError('name')"
            />
            <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</p>
          </div>

          <!-- Kode Konsultan -->
          <div class="relative">
            <label class="block text-gray-700 text-sm font-medium mb-1">Kode Konsultan (Opsional)</label>
            <input type="text" v-model="form.konsultan_kode" placeholder="Masukkan kode referral"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{
                'border-red-500': errors.konsultan_kode && form.konsultan_kode,
                'border-green-500': isReferralValid
              }"
            />
            <span v-if="isReferralValid" class="absolute right-3 top-3.5 text-green-500 font-bold">&#10003;</span>
            <span v-else-if="form.konsultan_kode && !isReferralValid" class="absolute right-3 top-3.5 text-red-500 font-bold">&#10007;</span>
            <p v-if="errors.konsultan_kode" class="text-red-500 text-xs mt-1">{{ errors.konsultan_kode }}</p>
          </div>

          <!-- Username -->
          <div>
            <label class="block text-gray-700 text-sm font-medium mb-1">Username</label>
            <input type="text" v-model="form.username" placeholder="Username"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{'border-red-500': errors.username}" @input="clearError('username')"
            />
            <p v-if="errors.username" class="text-red-500 text-xs mt-1">{{ errors.username }}</p>
          </div>

          <!-- Email -->
          <div>
            <label class="block text-gray-700 text-sm font-medium mb-1">Email aktif</label>
            <input type="email" v-model="form.email" placeholder="Masukkan Email Kamu"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{'border-red-500': errors.email}" @input="clearError('email')"
            />
            <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
          </div>

          <!-- WA -->
          <div>
            <label class="block text-gray-700 text-sm font-medium mb-1">Nomor aktif</label>
            <input type="text" v-model="form.wa" placeholder="08xxxxxxx"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{'border-red-500': errors.wa}" @input="clearError('wa')"
            />
            <p v-if="errors.wa" class="text-red-500 text-xs mt-1">{{ errors.wa }}</p>
          </div>

          <!-- Password -->
          <div class="relative">
            <label class="block text-gray-700 text-sm font-medium mb-1">Password</label>
            <input :type="showPassword ? 'text' : 'password'" v-model="form.password" placeholder="••••••••"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{'border-red-500': errors.password}" @input="clearError('password')"
            />
            <button type="button" @click="togglePassword" 
              class="absolute right-3 top-3.5 h-full flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
              <span v-if="!showPassword">👁️</span>
              <span v-else>🙈</span>
            </button>
            <p v-if="errors.password" class="text-red-500 text-xs mt-1">{{ errors.password }}</p>
          </div>

          <!-- Submit -->
          <button type="submit"
            :disabled="isLoading"
            class="w-full bg-green-600 text-white py-3 rounded-lg font-medium hover:bg-green-700 flex items-center justify-center">
            <span v-if="!isLoading">Register</span>
            <span v-else>Memproses...</span>
          </button>

        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, watch, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import logo from '../../assets/logo.png'
import axios from 'axios'
import { debounce } from 'lodash'

const props = defineProps({ referralCode: String })

const form = reactive({
  name: '',
  konsultan_kode: '',
  username: '',
  email: '',
  wa: '',
  password: ''
})

const errors = reactive({})
const isLoading = ref(false)
const showPassword = ref(false)
const isReferralValid = ref(false)

const togglePassword = () => showPassword.value = !showPassword.value
const clearError = (field) => { if(errors[field]) delete errors[field] }

// Isi otomatis referral dari query string
onMounted(() => {
  if(props.referralCode) form.konsultan_kode = props.referralCode
})

// Watch kode referral dengan debounce
watch(() => form.konsultan_kode, debounce(async (newVal) => {
  if (!newVal) {
    isReferralValid.value = false
    delete errors.konsultan_kode
    return
  }
  try {
    // Pastikan request ke endpoint yang benar
    const res = await axios.get(`/api/check-referral?kode=${newVal}`)
    console.log(res.data) // <-- debug, lihat valid true/false
    isReferralValid.value = res.data.valid
    if (!res.data.valid) errors.konsultan_kode = 'Kode konsultan tidak valid'
    else delete errors.konsultan_kode
  } catch (err) {
    console.error(err)
    isReferralValid.value = false
  }
}, 400))

// Submit form
const submit = async () => {
  Object.keys(errors).forEach(k => delete errors[k])

  if(!form.name) errors.name = 'Nama harus diisi'
  if(!form.username) errors.username = 'Username harus diisi'
  if(!form.email) errors.email = 'Email harus diisi'
  if(!form.wa) errors.wa = 'No WA harus diisi'
  if(!form.password) errors.password = 'Password harus diisi'

  if(Object.keys(errors).length) return

  isLoading.value = true

  try {
    const res = await axios.post('/register', {...form})
    alert('Akun berhasil dibuat! Kode referral Anda: ' + res.data.referral_code)
    window.location.href = res.data.dashboard
  } catch (err) {
    if(err.response?.data?.errors) Object.assign(errors, err.response.data.errors)
    else alert('Terjadi kesalahan server, coba lagi.')
  } finally {
    isLoading.value = false
  }
}
</script>