<template>
    <AdminLayout title="Dashboard" :user="user" :activeLink="activeLink">
      <div class="space-y-6">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
          <div
            v-for="stat in stats"
            :key="stat.label"
            class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div :class="['p-3 rounded-lg', stat.bgColor]">
                  <i :class="[stat.icon, 'text-white text-lg']"></i>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">{{ stat.label }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ stat.value }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activities & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Recent Activities -->
          <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
              <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                Lihat Semua
              </button>
            </div>

            <div class="space-y-4">
              <div
                v-for="activity in activities"
                :key="activity.id"
                class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors"
              >
                <div :class="['p-2 rounded-full', activity.iconBg]">
                  <i :class="[activity.icon, 'text-white text-sm']"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900">{{ activity.title }}</p>
                  <p class="text-xs text-gray-500">{{ activity.description }}</p>
                  <p class="text-xs text-gray-400 mt-1">{{ activity.time }}</p>
                </div>
              </div>

              <div v-if="activities.length === 0" class="text-center py-8">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-sm">Belum ada aktivitas</p>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Aksi Cepat</h3>
            <div class="space-y-3">
              <button
                v-for="action in quickActions"
                :key="action.label"
                @click="handleAction(action.action)"
                class="w-full flex items-center space-x-3 p-3 rounded-xl border border-gray-200 hover:border-blue-200 hover:bg-blue-50 transition-all duration-200"
              >
                <div :class="['p-2 rounded-lg', action.bgColor]">
                  <i :class="[action.icon, 'text-white']"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">{{ action.label }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from "vue"
import { usePage } from "@inertiajs/vue3"
import AdminLayout from "@/Layouts/AdminLayout.vue"

const { props } = usePage()
const activeLink = ref("/admin/dashboard")

// Data user dari props
const user = computed(() => props.auth?.user || {})

// Stats
const stats = ref([
  {
    label: "Total User",
    value: props.dashboard?.total_users || 0,
    icon: "fas fa-users",
    bgColor: "bg-blue-500",
  },
  {
    label: "Total Deposit",
    value: props.dashboard?.total_deposit || 0,
    icon: "fas fa-wallet",
    bgColor: "bg-green-500",
  },
])

// Aktivitas terbaru
const activities = ref(props.dashboard?.activities || [])

// Quick actions
const quickActions = [
  { label: "Tambah Pengguna", icon: "fas fa-user-plus", bgColor: "bg-blue-500", action: "addUser" },
  { label: "Verifikasi Deposit", icon: "fas fa-check-circle", bgColor: "bg-green-500", action: "verifyDeposit" },
  { label: "Proses Withdraw", icon: "fas fa-money-bill-wave", bgColor: "bg-purple-500", action: "processWithdraw" },
  { label: "Buat Pengumuman", icon: "fas fa-bullhorn", bgColor: "bg-orange-500", action: "createAnnouncement" },
]

// Aksi handler
const handleAction = (action) => {
  switch (action) {
    case "addUser":
      window.location.href = "/admin/manajemen_users?action=create"
      break
    case "verifyDeposit":
      window.location.href = "/admin/deposits?filter=pending"
      break
    case "processWithdraw":
      window.location.href = "/admin/withdraws?filter=pending"
      break
    case "createAnnouncement":
      window.location.href = "/admin/notifications?action=create"
      break
  }
}
</script>
