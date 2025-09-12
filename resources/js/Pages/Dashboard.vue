<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg p-4 flex justify-between items-center">
      <div class="flex items-center">
        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">Spectr</h1>
      </div>

      <!-- User Menu -->
      <div class="relative flex items-center space-x-4">
        <div class="hidden md:flex flex-col text-right">
          <span class="text-sm text-gray-500">Selamat datang,</span>
          <button @click="toggleDropdown" class="font-semibold text-gray-800 focus:outline-none flex items-center">
            {{ user.name }}
            <i class="fas fa-caret-down ml-1 text-gray-400"></i>
          </button>
        </div>

        <button @click="toggleDropdown" class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-green-500 flex items-center justify-center text-white font-bold">
          {{ user.name.charAt(0) }}
        </button>

        <!-- Dropdown -->
        <transition name="fade">
          <div v-if="dropdownOpen" class="absolute right-0 mt-12 w-40 bg-white shadow-lg rounded-lg overflow-hidden z-50">
            <a :href="profileUrl" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</a>
            <form :action="logoutUrl" method="POST">
              <input type="hidden" name="_token" :value="csrf" />
              <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Keluar</button>
            </form>
          </div>
        </transition>
      </div>
    </nav>

    <!-- Total Dompet -->
    <section class="p-6">
      <div class="bg-gradient-to-r from-blue-600 to-green-600 rounded-2xl p-6 text-white shadow-xl">
        <h2 class="text-lg font-semibold opacity-90">TOTAL DOMPET</h2>
        <p class="text-4xl font-bold mt-2">
          Rp {{ (user.wallet ?? walletBalance).toLocaleString() }}
        </p>
        <p class="mt-2 text-blue-100 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          {{ user.name }}
        </p>
        <div class="flex mt-4 space-x-3">
          <button class="bg-white text-blue-600 px-4 py-2 rounded-lg font-medium text-sm flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Dana
          </button>
          <button class="bg-blue-800 bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium text-sm flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
            Transfer
          </button>
        </div>
      </div>
    </section>

    <!-- Menu Navigasi Utama -->
    <div class="flex justify-around bg-white shadow-md p-4 mx-6 rounded-xl">
      <a v-for="item in mainMenu" :key="item.label" :href="item.url"
         :class="['flex flex-col items-center py-2 px-4 rounded-lg transition-all',
                  item.active ? 'bg-blue-50 text-blue-600 font-semibold shadow-inner' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50']">
        <div :class="['p-2 rounded-full', item.active ? 'bg-blue-100' : 'bg-gray-100']">
          <i :class="item.icon"></i>
        </div>
        <span class="text-xs mt-1">{{ item.label }}</span>
      </a>
    </div>

    <!-- Statistik Keuangan -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-5 p-6">
      <div class="bg-white rounded-2xl shadow-md p-5">
        <div class="flex justify-between items-center">
          <h3 class="text-gray-500 font-medium">Saldo Deposit</h3>
          <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Aktif</span>
        </div>
        <p class="text-2xl font-bold text-gray-800 mt-2">
          Rp {{ (user.deposit ?? depositBalance).toLocaleString() }}
        </p>
      </div>

      <div class="bg-white rounded-2xl shadow-md p-5">
        <div class="flex justify-between items-center">
          <h3 class="text-gray-500 font-medium">Total Keuntungan</h3>
          <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
            {{ user.profitPercent ?? '0%' }}
          </span>
        </div>
        <p class="text-2xl font-bold text-gray-800 mt-2">
          Rp {{ (user.profit ?? totalProfit).toLocaleString() }}
        </p>
      </div>
    </section>

    <!-- Pengumuman Resmi -->
    <section class="p-6">
      <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-green-500 p-4 text-white">
          <h3 class="text-xl font-bold flex items-center">
            <i class="fas fa-bullhorn mr-2"></i>
            Pengumuman Resmi
          </h3>
        </div>
        <div class="p-5">
          <p class="text-gray-700">{{ props.announcement ?? announcement }}</p>
        </div>
      </div>
    </section>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white shadow-lg flex justify-around py-3 border-t border-gray-200">
      <a v-for="item in bottomMenu" :key="item.label" :href="item.url" class="flex flex-col items-center transition-all"
         :class="item.active ? 'text-blue-600 transform -translate-y-1' : 'text-gray-500'">
        <div :class="['p-2 rounded-full', item.active ? 'bg-blue-100' : '']">
          <i :class="[item.icon, 'text-lg']"></i>
        </div>
        <span class="text-xs mt-1">{{ item.label }}</span>
      </a>
    </nav>

    <div class="h-16"></div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();
const user = props.auth?.user || {};
const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const logoutUrl = props.logoutUrl ?? '/logout';
const profileUrl = props.profileUrl ?? '/profil'; // link halaman profil user

const walletBalance = ref(0);
const depositBalance = ref(0);
const totalProfit = ref(0);

// Menu Navigasi Utama
const mainMenu = props.mainMenu ?? [
  { label: 'Deposit', url: '/deposit', icon: 'fas fa-plus', active: true },
  { label: 'Tarik Dana', url: '/tarik-tunai', icon: 'fas fa-arrow-down', active: false },
  { label: 'Dompet', url: '/dompet', icon: 'fas fa-wallet', active: false },
];

// Bottom Navigation
const bottomMenu = props.bottomMenu ?? [
  { label: 'Akademi', url: '/academy', icon: 'fas fa-graduation-cap', active: false },
  { label: 'Chat', url: '/chat', icon: 'fas fa-comments', active: false },
  { label: 'Beranda', url: '/dashboard', icon: 'fas fa-home', active: true },
  { label: 'Karir', url: '/career', icon: 'fas fa-briefcase', active: false },
  { label: 'Menu', url: '/menu', icon: 'fas fa-bars', active: false },
];

const announcement = ref('Selamat datang di Dashboard Spectr!');

// Dropdown Avatar/Nama
const dropdownOpen = ref(false);
function toggleDropdown() {
  dropdownOpen.value = !dropdownOpen.value;
}

// Tutup dropdown saat klik di luar
document.addEventListener('click', (e) => {
  const dropdown = e.target.closest('.relative');
  if (!dropdown) dropdownOpen.value = false;
});
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

a { transition: all 0.2s ease; }

.fade-enter-active, .fade-leave-active {
  transition: all 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(-5px);
}
</style>