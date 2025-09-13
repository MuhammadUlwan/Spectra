<template>
  <AppBackground bgClass="bg-gradient-to-r from-blue-600 to-green-400">
    <div class="min-h-screen flex items-center justify-center p-4">
      <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
        
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-green-500 p-6 text-white text-center">
          <h2 class="text-2xl font-bold mb-2">Reset Kata Sandi</h2>
          <p class="text-sm opacity-90">Buat kata sandi baru untuk akun Anda</p>
        </div>

        <div class="p-6 md:p-8">
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Email (readonly) -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <input 
                  type="email" 
                  v-model="form.email" 
                  readonly
                  class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600 cursor-not-allowed focus:outline-none"
                />
              </div>
            </div>

            <!-- Password Baru -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </div>
                <input 
                  :type="showPassword ? 'text' : 'password'" 
                  v-model="form.password"
                  placeholder="Masukkan password baru"
                  class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{'border-red-500': errors.password}"
                  @input="clearError('password')"
                />
                <button 
                  type="button" 
                  @click="togglePasswordVisibility" 
                  class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                >
                  <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.053 10.053 0 012.507-4.257m3.37-2.35a9.978 9.978 0 014.666-1.393M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                  </svg>
                </button>
              </div>
              <p v-if="errors.password" class="text-red-500 text-xs mt-2">{{ errors.password }}</p>
            </div>

            <!-- Konfirmasi Password -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 极 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                </div>
                <input 
                  :type="showConfirmPassword ? 'text' : 'password'" 
                  v-model="form.password_confirmation"
                  placeholder="Konfirmasi password baru"
                  class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{'border-red-500': errors.password_confirmation}"
                  @input="clearError('password_confirmation')"
                />
                <button 
                  type="button" 
                  @click="toggleConfirmPasswordVisibility" 
                  class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                >
                  <svg v-if="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="极 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-极.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.053 10.053 0 012.507-4.257m3.37-2.35a9.978 9.978 0 014.666-1.393M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                  </svg>
                </button>
              </div>
              <p v-if="errors.password_confirmation" class="text-red-500 text-xs mt-2">{{ errors.password_confirmation }}</p>
            </div>

            <!-- Strength Meter -->
            <div v-if="form.password" class="bg-gray-50 p-3 rounded-xl">
              <p class="text-xs font-medium text-gray-600 mb-2">Kekuatan password:</p>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div 
                  class="h-2 rounded-full transition-all duration-300" 
                  :class="passwordStrengthClass"
                  :style="{ width: passwordStrength + '%' }"
                ></div>
              </div>
              <p class="text-xs text-gray-500 mt-2">{{ passwordStrengthText }}</p>
            </div>

            <!-- Tombol Submit -->
            <button 
              type="submit"
              :disabled="isLoading"
              class="w-full bg-gradient-to-r from-blue-600 to-green-500 hover:from-blue-700 hover:to-green-600 text-white py-3.5 rounded-xl font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="!isLoading" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Reset Password
              </span>
              <span v-else class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
              </span>
            </button>
          </form>

          <!-- Link kembali ke login -->
          <div class="text-center mt-6 pt-4 border-t border-gray-200">
            <Link 
              href="/login" 
              class="text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors duration-200 flex items-center justify-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Kembali ke halaman login
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppBackground>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Link } from '@inertiajs/vue3';
import AppBackground from '@/Layouts/AppBackground.vue';

// Ambil parameter dari URL
const urlParams = new URLSearchParams(window.location.search);
const token = urlParams.get('token') || '';
const emailFromQuery = urlParams.get('email') || '';

const form = reactive({
  token: token,
  email: emailFromQuery,
  password: '',
  password_confirmation: ''
});

const errors = reactive({});
const isLoading = ref(false);
const showPassword = ref(false);
const showConfirmPassword = ref(false);

// Toggle visibility
const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};

const toggleConfirmPasswordVisibility = () => {
  showConfirmPassword.value = !showConfirmPassword.value;
};

// Clear error
const clearError = (field) => {
  if (errors[field]) delete errors[field];
};

// Password strength calculator
const passwordStrength = computed(() => {
  if (!form.password) return 0;
  
  let strength = 0;
  
  // Length check
  if (form.password.length >= 8) strength += 25;
  if (form.password.length >= 12) strength += 15;
  
  // Character variety
  if (/[A-Z]/.test(form.password)) strength += 15;
  if (/[a-z]/.test(form.password)) strength += 15;
  if (/[0-9]/.test(form.password)) strength += 15;
  if (/[^A-Za-z0-9]/.test(form.password)) strength += 15;
  
  return Math.min(strength, 100);
});

const passwordStrengthClass = computed(() => {
  if (passwordStrength.value < 40) return 'bg-red-500';
  if (passwordStrength.value < 70) return 'bg-yellow-500';
  return 'bg-green-500';
});

const passwordStrengthText = computed(() => {
  if (passwordStrength.value < 40) return 'Lemah';
  if (passwordStrength.value < 70) return 'Sedang';
  return 'Kuat';
});

const submit = () => {
  // Reset errors
  Object.keys(errors).forEach(key => delete errors[key]);
  
  // Client-side validation
  let hasError = false;
  
  if (!form.password) {
    errors.password = 'Password wajib diisi';
    hasError = true;
  } else if (form.password.length < 8) {
    errors.password = 'Password minimal 8 karakter';
    hasError = true;
  }
  
  if (!form.password_confirmation) {
    errors.password_confirmation = 'Konfirmasi password wajib diisi';
    hasError = true;
  } else if (form.password !== form.password_confirmation) {
    errors.password_confirmation = 'Konfirmasi password tidak cocok';
    hasError = true;
  }
  
  if (hasError) return;
  
  isLoading.value = true;
  
  Inertia.post('/reset-password', form, {
    onSuccess: () => {
      isLoading.value = false;
      // Redirect akan ditangani oleh Laravel
    },
    onError: (serverErrors) => {
      isLoading.value = false;
      if (serverErrors && serverErrors.errors) {
        Object.assign(errors, serverErrors.errors);
      }
    },
    onFinish: () => {
      isLoading.value = false;
    }
  });
};

// Validasi saat component mounted
onMounted(() => {
  if (!form.token || !form.email) {
    alert('Token atau email tidak valid. Silakan minta link reset password kembali.');
    window.location.href = '/forgot-password';
  }
});
</script>