<template>
  <AdminLayout
    :user="user"
    :profileUrl="profileUrl"
    :logoutUrl="logoutUrl"
    :sidebarMenu="sidebarMenu"
  >
    <div class="max-w-6xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-extrabold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">Referrals
        </h1>
        <p class="mt-2 text-gray-600 dark:text-gray-300">
          Lihat daftar jaringan referral dan detail sponsor.
        </p>
      </div>

      <!-- Statistik Ringkas -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
          <p class="text-sm text-gray-500 dark:text-gray-400">Referral Langsung</p>
          <p class="text-2xl font-bold text-blue-600">{{ stats.level1 }}</p>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
          <p class="text-sm text-gray-500 dark:text-gray-400">Referral Level 2</p>
          <p class="text-2xl font-bold text-green-600">{{ stats.level2 }}</p>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
          <p class="text-sm text-gray-500 dark:text-gray-400">Referral Level 3</p>
          <p class="text-2xl font-bold text-purple-600">{{ stats.level3 }}</p>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
          <p class="text-sm text-gray-500 dark:text-gray-400">Total Referral</p>
          <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
        </div>
      </div>

      <!-- Tabel Referral -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div
          class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center"
        >
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
            <i class="fas fa-user-friends text-blue-500 mr-2"></i>
            Daftar Referral
          </h2>
          <input
            type="text"
            v-model="search"
            placeholder="Cari nama referral..."
            class="border rounded-md px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
          />
        </div>
        <table class="w-full text-left">
          <thead>
            <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
              <th class="p-3 border">ID</th>
              <th class="p-3 border">Nama</th>
              <th class="p-3 border">Sponsor (Kode)</th>
              <th class="p-3 border">Referral (Kode)</th>
              <th class="p-3 border">Level</th>
              <th class="p-3 border">Tanggal Join</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="r in filteredReferrals"
              :key="r.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-900 dark:text-gray-100"
            >
              <td class="p-3 border">{{ r.id }}</td>
              <td class="p-3 border">{{ r.name }}</td>
              <td class="p-3 border">{{ r.sponsor || '-' }}</td>
              <td class="p-3 border">{{ r.referral_code }}</td>
              <td class="p-3 border">Level {{ r.level }}</td>
              <td class="p-3 border">{{ r.created_at }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from "vue"
import { usePage } from "@inertiajs/vue3"
import AdminLayout from "@/Layouts/AdminLayout.vue"

const { props: pageProps } = usePage()
const user = pageProps.auth?.user || {}
const profileUrl = pageProps?.profileUrl || "/profile"
const logoutUrl = pageProps?.logoutUrl || "/logout"
const referrals = pageProps.referrals || []
const stats = pageProps.stats || { level1: 0, level2: 0, level3: 0, total: 0 }

const search = ref("")
const filteredReferrals = computed(() => {
  if (!search.value) return referrals
  return referrals.filter(r =>
    (r.name || "").toLowerCase().includes(search.value.toLowerCase())
  )
})

// Sidebar menu
const sidebarMenu = [
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
