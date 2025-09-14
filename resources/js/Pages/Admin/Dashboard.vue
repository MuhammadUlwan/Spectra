<template>
  <AdminLayout
    :user="user"
    :profileUrl="profileUrl"
    :logoutUrl="logoutUrl"
    :sidebarMenu="sidebarMenu"
    pageTitle="Dashboard"
  >
    <div class="flex flex-col space-y-6 w-full p-6">

      <!-- TOTAL DOMPET -->
      <div class="bg-gradient-to-r from-blue-600 to-green-600 rounded-2xl p-6 text-white shadow-xl">
        <h2 class="text-lg font-semibold opacity-90">TOTAL DOMPET</h2>
        <p class="text-4xl font-bold mt-2">
          {{ currency }} {{ walletBalance }}
        </p>
        <p class="mt-2 text-blue-100 flex items-center">
          <i class="fas fa-user mr-1"></i> {{ user.name }}
        </p>
      </div>

      <!-- Statistik Keuangan -->
      <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-5">

        <!-- Deposit Aktif -->
        <div class="bg-white rounded-2xl shadow-md p-5">
          <div class="flex justify-between items-center">
            <h3 class="text-gray-500 font-medium">Saldo Deposit</h3>
            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Aktif</span>
          </div>
          <p class="text-2xl font-bold text-gray-800 mt-2">
            {{ currency }} {{ formatNumber(depositBalance) }}
          </p>
        </div>

        <!-- Profit -->
        <div class="bg-white rounded-2xl shadow-md p-5">
          <div class="flex justify-between items-center">
            <h3 class="text-gray-500 font-medium">Profit</h3>
            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Paid</span>
          </div>
          <p class="text-2xl font-bold text-gray-800 mt-2">
            {{ currency }} {{ formatNumber(totalProfit) }}
          </p>
        </div>

        <!-- Commission -->
        <div class="bg-white rounded-2xl shadow-md p-5">
          <div class="flex justify-between items-center">
            <h3 class="text-gray-500 font-medium">Commission</h3>
            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Paid</span>
          </div>
          <p class="text-2xl font-bold text-gray-800 mt-2">
            {{ currency }} {{ formatNumber(totalCommissions) }}
          </p>
        </div>

      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const { props } = usePage()
const user = props.auth?.user || {}
const depositBalance = Number(props.depositBalance).toFixed(2)
const totalProfit = Number(props.totalProfit).toFixed(2)
const totalCommissions = Number(props.totalCommissions).toFixed(2)
// Perbaikan penting: walletBalance dari backend sudah 2 desimal
const walletBalance = Number(props.walletBalance).toFixed(2)

const profileUrl = props.profileUrl || '/profile'
const logoutUrl = props.logoutUrl || '/logout'
const currency = props.user_preferences?.currency || 'USDT'

const sidebarMenu = [
  { label: "Dashboard", url: "/admin/dashboard", icon: "fas fa-home", active: true },
  { label: "Investasi", url: "/admin/investments", icon: "fas fa-chart-line" },
  { label: "Pengguna", url: "/admin/users", icon: "fas fa-users" },
  { label: "Withdraw", url: "/admin/withdraws", icon: "fas fa-money-bill-wave" },
  { label: "Pengaturan", url: "/admin/settings", icon: "fas fa-cog" },
]

// Hanya untuk menampilkan deposit / profit / commission
const formatNumber = (num) => {
  return Number(num).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}
</script>
