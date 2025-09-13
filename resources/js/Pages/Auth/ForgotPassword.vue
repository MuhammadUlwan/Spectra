<template>
  <AppBackground bgClass="bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="min-h-screen flex items-center justify-center p-4">
      <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-green-500 p-6 text-center">
          <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
              <i class="fas fa-key text-blue-600 text-2xl"></i>
            </div>
          </div>
          <h1 class="text-2xl font-bold text-white mb-2">Reset Kata Sandi</h1>
          <p class="text-blue-100 text-sm">
            Masukkan email Anda untuk menerima link reset kata sandi
          </p>
        </div>

        <!-- Form -->
        <div class="p-6">
          <form @submit.prevent="submit" class="space-y-4">
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-envelope text-blue-500 mr-2"></i>
                Alamat Email
              </label>
              <input
                type="email"
                id="email"
                v-model="form.email"
                placeholder="email@example.com"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                :class="{'border-red-500': errors.email}"
                required
              />
              <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
            </div>

            <button
              type="submit"
              :disabled="isLoading"
              class="w-full bg-gradient-to-r from-blue-600 to-green-500 hover:from-blue-700 hover:to-green-600 text-white py-3 rounded-lg font-medium transition-all duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <i class="fas fa-paper-plane mr-2"></i>
              {{ isLoading ? 'Mengirim Link...' : 'Kirim Link Reset' }}
            </button>
          </form>

          <!-- Success Message -->
          <div v-if="message" class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
              <i class="fas fa-check-circle text-green-500 mr-2"></i>
              <p class="text-green-700 text-sm">{{ message }}</p>
            </div>
          </div>

          <!-- Error Message -->
          <div v-if="error" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center">
              <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
              <p class="text-red-700 text-sm">{{ error }}</p>
            </div>
          </div>

          <!-- Back to Login -->
          <div class="mt-6 pt-4 border-t border-gray-200">
            <Link 
              href="/login" 
              class="text-blue-600 hover:text-blue-800 text-sm transition-colors flex items-center justify-center"
            >
              <i class="fas fa-arrow-left mr-2"></i>
              Kembali ke halaman login
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppBackground>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Link } from '@inertiajs/vue3'
import AppBackground from '@/Layouts/AppBackground.vue'

const form = reactive({
  email: ''
})

const errors = reactive({})
const isLoading = ref(false)
const message = ref('')
const error = ref('')

const submit = async () => {
  // Reset messages
  message.value = ''
  error.value = ''
  Object.keys(errors).forEach(key => delete errors[key])

  // Client-side validation
  if (!form.email) {
    errors.email = 'Email harus diisi'
    return
  }

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    errors.email = 'Format email tidak valid'
    return
  }

  isLoading.value = true

  try {
    await Inertia.post('/forgot-password', form, {
      onSuccess: () => {
        message.value = 'Link reset kata sandi telah dikirim ke email Anda!'
        form.email = ''
      },
      onError: (err) => {
        if (err.email) {
          errors.email = err.email
        } else if (err.message) {
          error.value = err.message
        } else {
          error.value = 'Terjadi kesalahan. Silakan coba lagi.'
        }
      },
      onFinish: () => {
        isLoading.value = false
      }
    })
  } catch (err) {
    error.value = 'Terjadi kesalahan. Silakan coba lagi.'
    isLoading.value = false
  }
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>