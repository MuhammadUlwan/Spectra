<template>
  <AdminLayout
    :user="user"
    :profileUrl="profileUrl"
    :logoutUrl="logoutUrl"
    pageTitle="Dashboard"
    :sidebarMenu="defaultSidebarMenu"
  >
    <!-- Statistik Admin -->
    <section class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4 lg:gap-5 p-3 sm:p-4 md:p-6 w-full">
      <!-- Total Pengguna -->
      <a href="/admin/users" class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 hover:shadow-lg transition flex flex-col items-center">
        <i class="fas fa-users text-blue-500 text-3xl"></i>
        <h3 class="text-gray-500 dark:text-gray-400 font-medium mt-2">Total Pengguna</h3>
        <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ stats.total_users }}</p>
      </a>

      <!-- Investor Aktif -->
      <a href="/admin/investments?status=active" class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 hover:shadow-lg transition flex flex-col items-center">
        <i class="fas fa-user-check text-green-500 text-3xl"></i>
        <h3 class="text-gray-500 dark:text-gray-400 font-medium mt-2">Investor Aktif</h3>
        <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ stats.investor_aktif }}</p>
      </a>

      <!-- Total Investasi -->
      <a href="/admin/investments" class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 hover:shadow-lg transition flex flex-col items-center">
        <i class="fas fa-chart-line text-indigo-500 text-3xl"></i>
        <h3 class="text-gray-500 dark:text-gray-400 font-medium mt-2">Total Investasi</h3>
        <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ stats.currency }} {{ stats.total_investasi.toLocaleString() }}</p>
      </a>

      <!-- Total Profit -->
      <a href="/admin/withdraws" class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 hover:shadow-lg transition flex flex-col items-center">
        <i class="fas fa-coins text-yellow-500 text-3xl"></i>
        <h3 class="text-gray-500 dark:text-gray-400 font-medium mt-2">Total Profit</h3>
        <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ stats.currency }} {{ stats.total_profit.toLocaleString() }}</p>
      </a>

      <!-- Withdraw Pending -->
      <a href="/admin/withdraws" class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 hover:shadow-lg transition flex flex-col items-center">
        <i class="fas fa-money-bill-wave text-red-500 text-3xl"></i>
        <h3 class="text-gray-500 dark:text-gray-400 font-medium mt-2">Withdraw Pending</h3>
        <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ stats.withdraw_pending }}</p>
      </a>
    </section>

    <!-- Slider Pengumuman Aktif -->
    <section class="p-6 container mx-auto">
      <div class="rounded-3xl overflow-hidden">
        <!-- Header Gradient -->
        <div class="bg-gradient-to-r from-blue-600 to-green-500 p-4 flex justify-between items-center text-white">
          <h3 class="text-2xl font-bold flex items-center">
            <i class="fas fa-bullhorn mr-2"></i>
            Pengumuman Aktif
          </h3>
          <a href="/admin/announcements"
             class="bg-white text-blue-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100">
             Kelola
          </a>
        </div>

        <!-- Slider -->
        <div class="mt-4">
          <swiper v-if="announcements.length"
            :modules="[Navigation, Pagination, Autoplay]"
            :slides-per-view="1"
            :loop="true"
            :autoplay="{ delay: 4000 }"
            pagination
            navigation
            class="w-full mx-auto aspect-[16/9] sm:aspect-[4/1] rounded-xl sm:rounded-3xl shadow-lg overflow-hidden">
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
  </AdminLayout>
</template>

<script setup>
import { ref } from "vue"
import { usePage } from "@inertiajs/vue3"
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Swiper, SwiperSlide } from "swiper/vue"
import { Navigation, Pagination, Autoplay } from "swiper/modules"
import "swiper/css"
import "swiper/css/navigation"
import "swiper/css/pagination"

const { props } = usePage()
const user = props.auth?.user || {}
const logoutUrl = props.logoutUrl || "/logout"
const profileUrl = props.profileUrl || "/profile"

const stats = {
  total_users: props.stats?.total_users || 0,
  investor_aktif: props.stats?.investor_aktif || 0,
  total_investasi: props.stats?.total_investasi || 0,
  total_profit: props.stats?.total_profit || 0,
  withdraw_pending: props.stats?.withdraw_pending || 0,
  currency: props.stats?.currency || "USDT",
}

const announcements = ref(props.announcements || [])

const defaultSidebarMenu = [
  { label: "Dashboard", url: "/admin/dashboard", icon: "fas fa-home" },
  { label: "Investasi", url: "/admin/investments", icon: "fas fa-chart-line" },
  { label: "Paket Deposit", url: "/admin/deposit-packages", icon: "fas fa-wallet" },
  { label: "Pengguna", url: "/admin/users", icon: "fas fa-users" },
  { label: "Withdraw", url: "/admin/withdraws", icon: "fas fa-money-bill-wave" },
  { label: "Referrals", url: "/admin/referrals", icon: "fas fa-user-friends" },
  { label: "Pengaturan", url: "/admin/settings", icon: "fas fa-cog" },
]
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");
</style>
