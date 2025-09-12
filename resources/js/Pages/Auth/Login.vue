<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-indigo-100 p-4">
    <div class="flex flex-col md:flex-row w-full max-w-4xl bg-white rounded-2xl shadow-xl overflow-hidden">
      
      <!-- Kiri: Informasi & Logo -->
      <div class="md:w-2/5 bg-gradient-to-br from-green-600 to-indigo-700 text-white p-8 flex flex-col justify-center items-center">
        <h1 class="text-3xl font-bold mb-2 text-center">Selamat Datang di Spectra</h1>
        <p class="text-sm opacity-90 mb-6 text-center">Masuk untuk mengakses dashboard Anda</p>
        <img :src="logo" alt="Logo Spectra" class="h-24 w-24 mb-6 object-contain rounded-full shadow-lg" />
        <p class="text-sm text-center opacity-90">
          Belum punya akun? 
          <Link href="/register" class="underline font-semibold hover:opacity-80 transition-opacity">Daftar sekarang</Link>
        </p>
      </div>

      <!-- Kanan: Form Login -->
      <div class="md:w-3/5 p-8 md:p-10">
        <h2 class="text-2xl md:text-3xl font-bold mb-2 text-gray-800 text-center">Masuk ke Akun</h2>
        <p class="text-gray-600 text-sm md:text-base mb-6 text-center">Masukkan kredensial Anda untuk melanjutkan</p>

        <form @submit.prevent="submit" class="space-y-6">
          <!-- Email -->
          <div>
            <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
            <input 
              type="email" 
              id="email" 
              v-model="form.email"
              placeholder="Masukan Email kamu"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200"
              :class="{'border-red-500': errors.email}"
              @input="clearError('email')"
            />
            <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
          </div>

          <!-- Password -->
          <div class="relative">
            <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Kata Sandi</label>
            <input 
              :type="showPassword ? 'text' : 'password'" 
              id="password" 
              v-model="form.password"
              placeholder="••••••••"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200"
              :class="{'border-red-500': errors.password}"
              @input="clearError('password')"
            />
            <button type="button" @click="togglePassword" 
              class="absolute right-3 top-3.5 h-full flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
              <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.053 10.053 0 012.507-4.257m3.37-2.35a9.978 9.978 0 014.666-1.393M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
              </svg>
            </button>
            <p v-if="errors.password" class="text-red-500 text-xs mt-1">{{ errors.password }}</p>
          </div>

          <!-- Submit Button -->
          <button type="submit"
            :disabled="isLoading"
            class="w-full bg-green-600 text-white py-3 rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 hover:bg-green-700 flex items-center justify-center">
            <span v-if="!isLoading">Masuk</span>
            <span v-else>Memproses...</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Link } from '@inertiajs/vue3'
import logo from '../../assets/logo.png'

const form = reactive({
  email: '',
  password: ''
})

const errors = reactive({})
const isLoading = ref(false)
const showPassword = ref(false)

const togglePassword = () => {
  showPassword.value = !showPassword.value
}

const clearError = (field) => {
  if(errors[field]) delete errors[field]
}

const submit = () => {
  // Reset errors
  Object.keys(errors).forEach(k => delete errors[k])

  // Validasi
  if(!form.email) errors.email = 'Email harus diisi'
  else if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) errors.email = 'Format email tidak valid'
  if(!form.password) errors.password = 'Kata sandi harus diisi'
  if(Object.keys(errors).length) return

  // Submit
  isLoading.value = true
  Inertia.post('/login', form, {
    onSuccess: () => { isLoading.value = false },
    onError: (serverErrors) => {
      isLoading.value = false
      Object.assign(errors, serverErrors)
    }
  })
}
</script>

<style scoped>
/* Opsional: animasi ringan untuk input focus */
input:focus {
  transition: box-shadow 0.2s ease, border-color 0.2s ease;
}
</style>
