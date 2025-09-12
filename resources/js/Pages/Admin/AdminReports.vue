<template>
  <AdminLayout title="Laporan Keuangan">
    <div class="bg-white shadow rounded p-4">

      <!-- Header -->
      <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 space-y-2 md:space-y-0">
        <h2 class="font-bold text-lg">Laporan Keuangan</h2>
        <div class="flex flex-wrap gap-2">
          <button
            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
            @click="exportExcel"
          >
            Export Excel
          </button>
          <button
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            @click="exportPDF"
          >
            Export PDF
          </button>
        </div>
      </div>

      <!-- Tabs Filter -->
      <div class="flex flex-wrap gap-2 mb-4">
        <button
          v-for="tab in tabs"
          :key="tab.value"
          @click="currentTab = tab.value"
          :class="currentTab === tab.value ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'"
          class="px-4 py-2 rounded"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- Filter Date -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-2 md:space-y-0">
        <input type="date" v-model="fromDate" class="border p-2 rounded w-full md:w-1/4" />
        <input type="date" v-model="toDate" class="border p-2 rounded w-full md:w-1/4" />
        <button
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
          @click="fetchReports"
        >
          Filter
        </button>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full table-auto border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 border">ID</th>
              <th class="px-4 py-2 border">Deskripsi</th>
              <th class="px-4 py-2 border">Jenis</th>
              <th class="px-4 py-2 border">Jumlah</th>
              <th class="px-4 py-2 border">Tanggal</th>
              <th class="px-4 py-2 border">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in filteredReports" :key="item.id">
              <td class="px-4 py-2 border">{{ item.id }}</td>
              <td class="px-4 py-2 border">{{ item.description }}</td>
              <td class="px-4 py-2 border capitalize">{{ item.type }}</td>
              <td class="px-4 py-2 border">{{ item.amount | currency }}</td>
              <td class="px-4 py-2 border">{{ item.date }}</td>
              <td class="px-4 py-2 border capitalize">{{ item.status }}</td>
            </tr>
          </tbody>
        </table>

        <p v-if="filteredReports.length === 0" class="text-center p-4 text-gray-500">
          Tidak ada data ditemukan.
        </p>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";

export default {
  name: "AdminReports",
  components: { AdminLayout },
  data() {
    return {
      tabs: [
        { label: "Deposit", value: "deposit" },
        { label: "Withdraw", value: "withdraw" },
        { label: "Profit Investor", value: "profit" },
        { label: "Komisi Konsultan", value: "commission" },
        { label: "Laba/Rugi Sistem", value: "laba_rugi" },
      ],
      currentTab: "deposit",
      reports: [],
      fromDate: "",
      toDate: "",
    };
  },
  computed: {
    filteredReports() {
      return this.reports.filter((r) => {
        const from = this.fromDate ? new Date(this.fromDate) : null;
        const to = this.toDate ? new Date(this.toDate) : null;
        const date = new Date(r.date);

        return (
          (!from || date >= from) &&
          (!to || date <= to)
        );
      });
    },
  },
  methods: {
    fetchReports() {
      axios
        .get(`/api/admin/reports?type=${this.currentTab}&from=${this.fromDate}&to=${this.toDate}`)
        .then((res) => {
          this.reports = res.data;
        })
        .catch((err) => console.error("Gagal mengambil laporan:", err));
    },
    exportExcel() {
      window.open(`/api/admin/reports/export?type=${this.currentTab}&format=excel&from=${this.fromDate}&to=${this.toDate}`, "_blank");
    },
    exportPDF() {
      window.open(`/api/admin/reports/export?type=${this.currentTab}&format=pdf&from=${this.fromDate}&to=${this.toDate}`, "_blank");
    },
  },
  mounted() {
    this.fetchReports();
  },
  filters: {
    currency(value) {
      if (!value) return "-";
      return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(value);
    },
  },
};
</script>
