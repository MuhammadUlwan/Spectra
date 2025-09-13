<template>
  <AdminLayout title="Komisi & Bonus">
    <div class="bg-white shadow-xl rounded-2xl p-4 md:p-6 space-y-6">

      <!-- Header Section -->
      <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">Manajemen Komisi & Bonus</h2>
          <p class="text-gray-600 mt-1">Kelola seluruh komisi dan bonus konsultan</p>
        </div>
        <button
          class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-xl flex items-center justify-center transition-all duration-200 shadow-md hover:shadow-lg"
          @click="generateCommissions"
          :disabled="isLoading"
        >
          <svg v-if="!isLoading" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          <span class="animate-spin mr-2" v-if="isLoading">⟳</span>
          {{ isLoading ? 'Sedang Memproses...' : 'Generate Komisi' }}
        </button>
      </div>

      <!-- Stats Overview -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-4">
          <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-xl">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-blue-600">Total Komisi</p>
              <p class="text-2xl font-bold text-gray-800">{{ formatCurrency(stats.totalCommission) }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-2xl p-4">
          <div class="flex items-center">
            <div class="bg-yellow-100 p-3 rounded-xl">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-yellow-600">Pending</p>
              <p class="text-2xl font-bold text-gray-800">{{ stats.pendingCount }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-4">
          <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-xl">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-green-600">Telah Dibayar</p>
              <p class="text-2xl font-bold text-gray-800">{{ stats.paidCount }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filter Section -->
      <div class="bg-gray-50 p-5 rounded-2xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Konsultan</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <input
                v-model="search"
                type="text"
                placeholder="Nama konsultan..."
                class="pl-10 w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :disabled="isLoading"
              />
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select
              v-model="filterStatus"
              class="w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :disabled="isLoading"
            >
              <option value="">Semua Status</option>
              <option value="pending">Pending</option>
              <option value="paid">Lunas</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
            <select
              v-model="filterPeriod"
              class="w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :disabled="isLoading"
            >
              <option value="">Semua Periode</option>
              <option v-for="period in availablePeriods" :key="period" :value="period">{{ period }}</option>
            </select>
          </div>
        </div>
      </div>

<!-- Loading State -->
<div v-if="isLoading && commissions.length === 0" class="text-center py-16">
  <div class="animate-spin inline-block w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full"></div>
  <p class="mt-4 text-gray-600 font-medium">Memuat data komisi...</p>
</div>

<!-- Empty State -->
<div v-else-if="!isLoading && filteredCommissions.length === 0" class="text-center py-12 bg-gray-50 rounded-2xl">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>
  <p class="mt-4 text-gray-500 text-lg font-medium" v-if="search || filterStatus || filterPeriod">
    Tidak ada data komisi yang sesuai dengan filter.
  </p>
  <p class="mt-4 text-gray-500 text-lg font-medium" v-else>
    Belum ada data komisi.
  </p>
  <p class="text-gray-400 mt-2">Data akan muncul setelah generate komisi</p>
</div>

<!-- Desktop Table -->
<div v-if="!isLoading && filteredCommissions.length > 0" class="hidden md:block overflow-x-auto rounded-2xl shadow-sm border border-gray-200">
  <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Konsultan</th>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sponsor Fee</th>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit Sharing</th>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bonus</th>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="item in filteredCommissions" :key="item.id" class="hover:bg-gray-50 transition-colors duration-150">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="font-medium text-blue-600">{{ getInitials(item.consultant_name) }}</span>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ item.consultant_name }}</div>
                    <div class="text-sm text-gray-500">ID: {{ item.id }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ formatCurrency(item.sponsor_fee) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatCurrency(item.profit_sharing) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatCurrency(item.bonus_omset) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm font-bold text-blue-600">
                  {{ formatCurrency(calculateTotal(item)) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="statusClass(item.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                  {{ capitalize(item.status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ item.period || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <button
                  v-if="item.status === 'pending'"
                  @click="markPaid(item)"
                  :disabled="isLoading"
                  class="text-green-600 hover:text-green-900 transition-colors duration-200"
                  title="Tandai sebagai lunas"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </button>
                <button
                  @click="viewDetail(item)"
                  :disabled="isLoading"
                  class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                  title="Lihat detail"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Mobile Cards -->
      <div v-else class="md:hidden space-y-4">
        <div v-for="item in filteredCommissions" :key="item.id" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
          <div class="flex justify-between items-start mb-4">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="font-medium text-blue-600">{{ getInitials(item.consultant_name) }}</span>
              </div>
              <div class="ml-3">
                <div class="font-medium text-gray-900">{{ item.consultant_name }}</div>
                <div class="text-sm text-gray-500">ID: {{ item.id }}</div>
              </div>
            </div>
            <span :class="statusClass(item.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
              {{ capitalize(item.status) }}
            </span>
          </div>
          
          <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
              <p class="text-xs text-gray-500">Sponsor Fee</p>
              <p class="text-sm font-medium">{{ formatCurrency(item.sponsor_fee) }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500">Profit Sharing</p>
              <p class="text-sm font-medium">{{ formatCurrency(item.profit_sharing) }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500">Bonus Omset</p>
              <p class="text-sm font-medium">{{ formatCurrency(item.bonus_omset) }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500">Total</p>
              <p class="text-sm font-bold text-blue-600">{{ formatCurrency(calculateTotal(item)) }}</p>
            </div>
          </div>
          
          <div class="flex space-x-2">
            <button
              v-if="item.status === 'pending'"
              @click="markPaid(item)"
              :disabled="isLoading"
              class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 px-3 rounded-xl text-sm font-medium transition-colors duration-200 flex items-center justify-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              Lunas
            </button>
            <button
              @click="viewDetail(item)"
              :disabled="isLoading"
              class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded-xl text-sm font-medium transition-colors duration-200 flex items-center justify-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              Detail
            </button>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="!isLoading && filteredCommissions.length > 0" class="flex items-center justify-between border-t border-gray-200 pt-4">
        <div class="text-sm text-gray-700">
          Menampilkan <span class="font-medium">{{ filteredCommissions.length }}</span> dari <span class="font-medium">{{ commissions.length }}</span> hasil
        </div>
        <!-- Pagination controls would go here -->
      </div>

    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";

export default {
  name: "AdminCommissions",
  components: { AdminLayout },
  data() {
    return {
      commissions: [],
      search: "",
      filterStatus: "",
      filterPeriod: "",
      isLoading: false,
      stats: {
        totalCommission: 0,
        pendingCount: 0,
        paidCount: 0
      },
      availablePeriods: []
    };
  },
  computed: {
    filteredCommissions() {
      return this.commissions.filter((c) => {
        const matchesSearch = !this.search ||
          c.consultant_name.toLowerCase().includes(this.search.toLowerCase());
        
        const matchesStatus = !this.filterStatus || c.status === this.filterStatus;
        
        const matchesPeriod = !this.filterPeriod || c.period === this.filterPeriod;
        
        return matchesSearch && matchesStatus && matchesPeriod;
      });
    },
  },
  methods: {
    fetchCommissions() {
      this.isLoading = true;
      axios
        .get("/api/admin/commissions")
        .then((response) => {
          this.commissions = response.data.commissions || [];
          this.stats = response.data.stats || {
            totalCommission: 0,
            pendingCount: 0,
            paidCount: 0
          };
          this.availablePeriods = response.data.periods || [];
        })
        .catch((error) => {
          console.error("Gagal mengambil data komisi:", error);
          this.$toast.error("Gagal memuat data komisi");
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    generateCommissions() {
      this.isLoading = true;
      axios.post("/api/admin/commissions/generate")
        .then(() => {
          this.fetchCommissions();
          this.$toast.success("Komisi berhasil digenerate");
        })
        .catch((error) => {
          console.error("Gagal generate komisi:", error);
          this.$toast.error("Gagal menggenerate komisi");
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    markPaid(item) {
      this.isLoading = true;
      axios.post(`/api/admin/commissions/${item.id}/mark-paid`)
        .then(() => {
          item.status = "paid";
          this.$toast.success("Komisi berhasil ditandai sebagai lunas");
          this.fetchCommissions(); // Refresh stats
        })
        .catch((error) => {
          console.error("Gagal tandai lunas:", error);
          this.$toast.error("Gagal menandai komisi sebagai lunas");
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    viewDetail(item) {
      this.$inertia.get(`/admin/commissions/${item.id}`);
    },
    formatCurrency(value) {
      if (!value) return "-";
      return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0
      }).format(value);
    },
    capitalize(str) {
      if (!str) return "";
      return str.charAt(0).toUpperCase() + str.slice(1);
    },
    statusClass(status) {
      if (status === "pending") return "bg-yellow-100 text-yellow-800";
      if (status === "paid") return "bg-green-100 text-green-800";
      return "bg-gray-100 text-gray-800";
    },
    getInitials(name) {
      if (!name) return "?";
      return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
    },
    calculateTotal(item) {
      return (item.sponsor_fee || 0) + (item.profit_sharing || 0) + (item.bonus_omset || 0);
    }
  },
  mounted() {
    this.fetchCommissions();
  },
};
</script>