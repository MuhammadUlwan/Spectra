<template>
  <AdminLayout title="Laporan Keuangan">
    <div class="bg-white shadow-xl rounded-2xl p-4 md:p-6">

      <!-- Header Section -->
      <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">Laporan Keuangan</h2>
          <p class="text-gray-600 mt-1">Analisis dan pantau performa keuangan sistem</p>
        </div>
        <div class="flex flex-wrap gap-2">
          <button @click="exportExcel" :disabled="isLoading" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-2.5 rounded-xl flex items-center shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Excel
          </button>
          <button @click="exportPDF" :disabled="isLoading" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-4 py-2.5 rounded-xl flex items-center shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
            PDF
          </button>
        </div>
      </div>

      <!-- Tabs Filter -->
      <div class="flex flex-wrap gap-2 mb-6 bg-gray-50 p-3 rounded-2xl">
        <button v-for="tab in tabs" :key="tab.value" @click="currentTab = tab.value"
          :class="currentTab === tab.value 
            ? 'bg-blue-600 text-white shadow-md' 
            : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'"
          class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200">
          {{ tab.label }}
        </button>
      </div>

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Cards sama seperti kode sebelumnya -->
      </div>

      <!-- Date Filter -->
      <div class="bg-gray-50 p-4 rounded-2xl mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Inputs tanggal sama seperti kode sebelumnya -->
        </div>
      </div>

      <!-- Conditional Rendering Sesuai Konsep -->
      <template v-if="isLoading && reports.length === 0">
        <!-- Loading -->
        <div class="text-center py-12">
          <div class="animate-spin inline-block w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full"></div>
          <p class="mt-2 text-gray-600">Memuat data laporan...</p>
        </div>
      </template>

      <template v-else-if="!isLoading && filteredReports.length === 0">
        <!-- Empty State -->
        <div class="text-center py-8 bg-gray-50 rounded-2xl">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="mt-2 text-gray-500">Tidak ada data laporan ditemukan.</p>
          <p class="text-sm text-gray-400 mt-1">Coba ubah filter atau periode tanggal</p>
        </div>
      </template>

      <template v-else>
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto rounded-2xl shadow-sm border border-gray-200">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="item in filteredReports" :key="item.id" class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatDate(item.date) }}</td>
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">{{ item.description }}</div>
                  <div class="text-sm text-gray-500">ID: {{ item.id }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="typeClass(item.type)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                    {{ capitalize(item.type) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" :class="amountColor(item.type)">
                  {{ formatCurrency(item.amount) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="statusClass(item.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                    {{ capitalize(item.status) }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4">
          <div v-for="item in filteredReports" :key="item.id" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200">
            <div class="flex justify-between items-start mb-3">
              <div>
                <span :class="typeClass(item.type)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mb-2">
                  {{ capitalize(item.type) }}
                </span>
                <h3 class="text-sm font-medium text-gray-900">{{ item.description }}</h3>
                <p class="text-xs text-gray-500">ID: {{ item.id }}</p>
              </div>
              <span :class="statusClass(item.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                {{ capitalize(item.status) }}
              </span>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-xs text-gray-500">Tanggal</p>
                <p class="text-sm font-medium">{{ formatDate(item.date) }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500">Jumlah</p>
                <p class="text-sm font-medium" :class="amountColor(item.type)">{{ formatCurrency(item.amount) }}</p>
              </div>
            </div>
          </div>
        </div>
      </template>

      <!-- Pagination -->
      <div v-if="!isLoading && filteredReports.length > 0" class="mt-6 flex items-center justify-between border-t border-gray-200 pt-4">
        <div class="text-sm text-gray-700">
          Menampilkan <span class="font-medium">{{ filteredReports.length }}</span> dari <span class="font-medium">{{ reports.length }}</span> hasil
        </div>
        <!-- Pagination controls -->
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

      ],
      currentTab: "deposit",
      reports: [],
      fromDate: "",
      toDate: "",
      isLoading: false,
      summary: {
        totalIncome: 0,
        totalExpense: 0,
        netProfit: 0,
        totalTransactions: 0
      }
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
      this.isLoading = true;
      axios
        .get(`/api/admin/reports?type=${this.currentTab}&from=${this.fromDate}&to=${this.toDate}`)
        .then((response) => {
          this.reports = response.data.reports || [];
          this.summary = response.data.summary || {
            totalIncome: 0,
            totalExpense: 0,
            netProfit: 0,
            totalTransactions: 0
          };
        })
        .catch((error) => {
          console.error("Gagal mengambil laporan:", error);
          this.$toast.error("Gagal memuat data laporan");
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    exportExcel() {
      window.open(`/api/admin/reports/export?type=${this.currentTab}&format=excel&from=${this.fromDate}&to=${this.toDate}`, "_blank");
    },
    exportPDF() {
      window.open(`/api/admin/reports/export?type=${this.currentTab}&format=pdf&from=${this.fromDate}&to=${this.toDate}`, "_blank");
    },
    formatCurrency(value) {
      if (!value) return "-";
      return new Intl.NumberFormat("id-ID", { 
        style: "currency", 
        currency: "IDR",
        minimumFractionDigits: 0
      }).format(value);
    },
    formatDate(dateString) {
      if (!dateString) return "-";
      return new Date(dateString).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
      });
    },
    capitalize(str) {
      if (!str) return "";
      return str.charAt(0).toUpperCase() + str.slice(1);
    },
    typeClass(type) {
      const typeMap = {
        'deposit': 'bg-blue-100 text-blue-800',
        'withdraw': 'bg-purple-100 text-purple-800',
        'profit': 'bg-green-100 text-green-800',
        'commission': 'bg-yellow-100 text-yellow-800',
        'laba_rugi': 'bg-indigo-100 text-indigo-800'
      };
      return typeMap[type] || 'bg-gray-100 text-gray-800';
    },
    statusClass(status) {
      const statusMap = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'completed': 'bg-green-100 text-green-800',
        'failed': 'bg-red-100 text-red-800'
      };
      return statusMap[status] || 'bg-gray-100 text-gray-800';
    },
    amountColor(type) {
      return type === 'withdraw' ? 'text-red-600' : 'text-green-600';
    }
  },
  watch: {
    currentTab() {
      this.fetchReports();
    }
  },
  mounted() {
    // Set default date range to current month
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    this.fromDate = firstDay.toISOString().split('T')[0];
    this.toDate = today.toISOString().split('T')[0];
    
    this.fetchReports();
  },
};
</script>