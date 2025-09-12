<template>
  <AdminLayout title="Dashboard" :user="user">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div v-for="stat in stats" :key="stat.label" class="bg-white shadow rounded p-4">
        <p class="text-gray-500">{{ stat.label }}</p>
        <p class="text-2xl font-bold">{{ stat.value }}</p>
      </div>
    </div>

    <div class="bg-white shadow rounded p-4 mb-6">
      <h2 class="font-bold mb-4">Pertumbuhan Investasi</h2>
      <div class="h-64 flex items-center justify-center bg-gray-100 rounded">
        <p class="text-gray-500">[Grafik Chart.js / ApexCharts dinamis]</p>
      </div>
    </div>

    <div class="bg-white shadow rounded p-4">
      <h2 class="font-bold mb-4">Aktivitas Terbaru</h2>
      <ul class="space-y-2">
        <li v-for="activity in activities" :key="activity.id" class="border-b py-2">
          {{ activity.text }}
        </li>
      </ul>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
  name: "Dashboard",
  components: { AdminLayout },
  data() {
    return {
      user: { name: "Admin User", role: "Administrator" },
      stats: [
        { label: "Total User", value: 0 },
        { label: "Total Deposit", value: "$0" },
        { label: "Total Withdraw", value: "$0" },
      ],
      activities: [],
    };
  },
};
</script>
