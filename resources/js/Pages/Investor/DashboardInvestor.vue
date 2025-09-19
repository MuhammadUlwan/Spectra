<template>
  <InvestorLayout :user="user" :profileUrl="profileUrl" :logoutUrl="logoutUrl">
    <div class="flex flex-col space-y-6 w-full">

      <!-- Total Dompet -->
      <div class="bg-gradient-to-r from-blue-600 to-green-600 rounded-2xl p-6 text-white shadow-xl">
        <h2 class="text-lg font-semibold opacity-90">TOTAL DOMPET</h2>
        <p class="text-4xl font-bold mt-2">
          {{ currency }} {{ formatNumber(walletBalance) }}
        </p>
        <p class="mt-2 text-blue-100 flex items-center">
          <i class="fas fa-user mr-1"></i> {{ user.name || '-' }}
        </p>
      </div>

      <!-- 4 Card Statistik -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Deposit -->
        <div class="bg-white rounded-2xl shadow-md p-5">
          <div class="flex justify-between items-center">
            <h3 class="text-gray-500 font-medium">Saldo Deposit</h3>
            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Deposit</span>
          </div>
          <p class="text-2xl font-bold text-gray-800 mt-2">{{ currency }} {{ formatNumber(depositBalance) }}</p>
        </div>

        <!-- Profit -->
        <div class="bg-white rounded-2xl shadow-md p-5">
          <div class="flex justify-between items-center">
            <h3 class="text-gray-500 font-medium">Total Profit</h3>
            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Profit</span>
          </div>
          <p class="text-2xl font-bold text-gray-800 mt-2">{{ currency }} {{ formatNumber(totalProfit) }}</p>
        </div>

        <!-- Extra Bonus -->
        <div class="bg-white rounded-2xl shadow-md p-5">
          <div class="flex justify-between items-center">
            <h3 class="text-gray-500 font-medium">Extra Bonus</h3>
            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Bonus</span>
          </div>
          <p class="text-2xl font-bold text-gray-800 mt-2">{{ currency }} {{ formatNumber(extraBonus) }}</p>
        </div>

        <!-- Sponsor Fee -->
        <div class="bg-white rounded-2xl shadow-md p-5">
          <div class="flex justify-between items-center">
            <h3 class="text-gray-500 font-medium">Sponsor Fee</h3>
            <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">Sponsor</span>
          </div>
          <p class="text-2xl font-bold text-gray-800 mt-2">{{ currency }} {{ formatNumber(sponsorFee) }}</p>
        </div>
      </div>

      <!-- Slider Pengumuman -->
      <section class="p-1 container mx-auto">
        <div class="rounded-3xl overflow-hidden">
          <div class="mt-4 w-full">
            <swiper v-if="announcements.length"
              :modules="[Navigation, Pagination, Autoplay]"
              :slides-per-view="1"
              :loop="true"
              :autoplay="{ delay: 4000 }"
              pagination
              navigation
              class="w-full max-w-[100%] mx-auto rounded-3xl shadow-lg aspect-[7/2]">
              <swiper-slide v-for="a in announcements" :key="a.id" class="flex justify-center items-center">
                <img :src="a.image_url" :alt="a.title" class="w-full h-full object-cover rounded-3xl" />
              </swiper-slide>
            </swiper>
            <p v-else class="text-gray-600 text-center text-lg font-medium mt-10">
              Belum ada pengumuman aktif.
            </p>
          </div>
        </div>
      </section>

      <!-- Popup Deposit -->
      <PopupDeposit :show="pendingDeposit" @close="pendingDeposit = false" />

    </div>
  </InvestorLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation, Pagination, Autoplay } from 'swiper/modules'
import InvestorLayout from '@/Layouts/InvestorLayout.vue'
import PopupDeposit from '@/Components/PopupDeposit.vue'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'
import axios from 'axios'

const { props } = usePage()

// Props Inertia
const user = props.auth?.user || {}
const announcements = ref(props.announcements ?? [])
const profileUrl = props.profileUrl ?? '/profile'
const logoutUrl = props.logoutUrl ?? '/logout'
const currency = props.user_preferences?.currency ?? 'USDT'

// Popup Deposit flag
const pendingDeposit = ref(false)

// Wallet balance
const depositBalance = ref(0)
const totalProfit = ref(0)
const extraBonus = ref(0)
const sponsorFee = ref(0)
const walletBalance = ref(0)

function formatNumber(value) {
  return Number(value || 0).toLocaleString('id-ID', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })
}

async function fetchWalletBalance() {
  try {
    const res = await axios.get('/investor/wallet-balance')
    depositBalance.value = res.data.depositBalance ?? 0
    totalProfit.value = res.data.totalProfit ?? 0
    extraBonus.value = res.data.extraBonus ?? 0
    sponsorFee.value = res.data.sponsorFee ?? 0
    walletBalance.value = depositBalance.value + totalProfit.value + extraBonus.value + sponsorFee.value

    // cek deposit pertama dari backend
    if (!res.data.firstDeposit || res.data.firstDeposit < 10) {
      pendingDeposit.value = true
    }
  } catch (error) {
    console.error('Gagal fetch wallet balance:', error)
  }
}

onMounted(() => {
  depositBalance.value = props.depositBalance ?? 0
  totalProfit.value = props.totalProfit ?? 0
  extraBonus.value = props.extraBonus ?? 0
  sponsorFee.value = props.sponsorFee ?? 0
  walletBalance.value = depositBalance.value + totalProfit.value + extraBonus.value + sponsorFee.value

  // Cek dari props dulu (server)
  if (!props.firstDeposit || props.firstDeposit < 10) {
    pendingDeposit.value = true
  }

  // fetch data terkini
  fetchWalletBalance()
})
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

.swiper-button-prev,
.swiper-button-next {
  top: 50%;
  transform: translateY(-50%);
  z-index: 20;
  width: 2rem;
  height: 2rem;
  color: white;
  background: rgba(0,0,0,0.3);
  border-radius: 9999px;
}
.swiper-button-prev { left: 0.5rem; }
.swiper-button-next { right: 0.5rem; }

.swiper-pagination { bottom: 0.5rem !important; }

.swiper-slide img { transition: transform 0.5s ease-in-out; }

@media (max-width: 640px) { .swiper-slide { height: 20rem; } }
@media (min-width: 641px) and (max-width: 1024px) { .swiper-slide { height: 24rem; } }
@media (min-width: 1025px) { .swiper-slide { height: 32rem; } }
</style>
