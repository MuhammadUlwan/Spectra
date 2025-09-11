<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-indigo-100 p-4">
    <div class="flex flex-col md:flex-row w-full max-w-4xl bg-white rounded-2xl shadow-xl overflow-hidden">

      <!-- Kiri - Ilustrasi -->
      <div class="md:w-2/5 bg-gradient-to-br from-green-600 to-indigo-700 text-white p-8 flex flex-col justify-center items-center">
        <h1 class="text-3xl font-bold mb-2 text-center">Bergabung dengan Spectra</h1>
        <p class="text-sm opacity-90 mb-6 text-center">Buat akun baru untuk mengakses dashboard</p>

        <img :src="logo" alt="Logo Spectra" class="h-24 w-24 mb-6 object-contain rounded-full shadow-lg" />

        <p class="text-sm text-center opacity-90">
          Sudah punya akun? 
          <Link href="/login" class="underline font-semibold hover:opacity-80 transition-opacity">Masuk sekarang</Link>
        </p>
      </div>

      <!-- Kanan - Form Register -->
      <div class="md:w-3/5 p-8 md:p-10">
        <h2 class="text-2xl md:text-3xl font-bold mb-2 text-gray-800 text-center">Daftar Akun</h2>
        <p class="text-gray-600 text-sm md:text-base mb-6 text-center">Isi formulir di bawah untuk membuat akun baru</p>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-gray-700 text-sm font-medium mb-1">Nama Lengkap</label>
            <input type="text" v-model="form.name" placeholder="Nama Lengkap"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{'border-red-500': errors.name}" @input="clearError('name')"
            />
            <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</p>
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-medium mb-1">kode Konsultan</label>
            <input type="text" v-model="form.konsultan_kode" placeholder="kode Konsultan"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{'border-red-500': errors.konsultan_kode}" @input="clearError('konsultan_kode')"
            />
            <p v-if="errors.konsultan_kode" class="text-red-500 text-xs mt-1">{{ errors.konsultan_kode }}</p>
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-medium mb-1">Username</label>
            <input type="text" v-model="form.username" placeholder="Username"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{'border-red-500': errors.username}" @input="clearError('username')"
            />
            <p v-if="errors.username" class="text-red-500 text-xs mt-1">{{ errors.username }}</p>
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-medium mb-1">Email aktif</label>
            <input type="email" v-model="form.email" placeholder="Masukan Email Kamu"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{'border-red-500': errors.email}" @input="clearError('email')"
            />
            <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-medium mb-1">No WA aktif</label>
            <input type="text" v-model="form.wa" placeholder="08xxxxxxx"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{'border-red-500': errors.wa}" @input="clearError('wa')"
            />
            <p v-if="errors.wa" class="text-red-500 text-xs mt-1">{{ errors.wa }}</p>
          </div>

          <!-- Password Show/Hide -->
          <div class="relative">
            <label class="block text-gray-700 text-sm font-medium mb-1">Password</label>
            <input 
              :type="showPassword ? 'text' : 'password'" 
              v-model="form.password" placeholder="••••••••"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{'border-red-500': errors.password}" @input="clearError('password')"
            />
            <button type="button" @click="togglePassword" 
              class="absolute right-3 top-3.5 h-full flex items-center text-gray-500 hover:text-gray-700 focus:outline-none"
            >
              <!-- Mata terbuka -->
              <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              <!-- Mata tertutup -->
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.053 10.053 0 012.507-4.257m3.37-2.35a9.978 9.978 0 014.666-1.393M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
              </svg>
            </button>
            <p v-if="errors.password" class="text-red-500 text-xs mt-1">{{ errors.password }}</p>
          </div>

          <button type="submit"
            :disabled="isLoading"
            class="w-full bg-green-600 text-white py-3 rounded-lg font-medium hover:bg-green-700 flex items-center justify-center"
          >
            <span v-if="!isLoading">Register</span>
            <span v-else>Memproses...</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import logo from '../../assets/logo.png'

const form = reactive({
  name: '',
  konsultan_username: '',
  username: '',
  email: '',
  wa: '',
  password: ''
})

const errors = reactive({})
const isLoading = ref(false)

// Show/hide password
const showPassword = ref(false)
const togglePassword = () => showPassword.value = !showPassword.value

const clearError = (field) => { if(errors[field]) delete errors[field] }

const submit = () => {
  Object.keys(errors).forEach(k => delete errors[k])

  if(!form.name) errors.name = 'Nama harus diisi'
  if(!form.konsultan_username) errors.konsultan_username = 'Username Konsultan harus diisi'
  if(!form.username) errors.username = 'Username harus diisi'
  if(!form.email) errors.email = 'Email harus diisi'
  if(!form.wa) errors.wa = 'No WA harus diisi'
  if(!form.password) errors.password = 'Password harus diisi'

  if(Object.keys(errors).length) return

  isLoading.value = true
  setTimeout(() => {
    isLoading.value = false
    alert('Akun berhasil dibuat! (Demo)')
  }, 1200)
}
</script>
