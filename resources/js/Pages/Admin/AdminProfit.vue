<template>
  <AdminLayout title="Pembayaran Profit">
    <div class="bg-white shadow rounded-lg p-4 md:p-6">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 md:mb-6 space-y-2 md:space-y-0">
        <div>
          <h2 class="text-xl md:text-2xl font-bold text-gray-800">Riwayat Pembayaran Profit</h2>
          <p class="text-sm text-gray-600 mt-1">Kelola pembayaran profit untuk semua investor</p>
        </div>
        <button
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors"
          @click="generateProfit"
          :disabled="isLoading"
        >
          <span v-if="isLoading" class="animate-spin mr-2">⟳</span>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          {{ isLoading ? 'Memproses...' : 'Generate Pembayaran' }}
        </button>
      </div>

      <!-- Filter Section -->
      <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Cari Investor/Paket</label>
            <input
              v-model="search"
              type="text"
              placeholder="Cari investor atau paket..."
              class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
              :disabled="isLoading"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select 
              v-model="filterStatus" 
              class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
              :disabled="isLoading"
            >
              <option value="">Semua Status</option>
              <option value="pending">Pending</option>
              <option value="paid">Lunas</option>
              <option value="withdraw">Penarikan</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
            <input
              v-model="filterDate"
              type="date"
              class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
              :disabled="isLoading"
            />
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading && profits.length === 0" class="text-center py-12">
        <div class="animate-spin inline-block w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full"></div>
        <p class="mt-2 text-gray-600">Memuat data pembayaran...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="!isLoading && filteredProfits.length === 0" class="text-center py-8 bg-white rounded-lg shadow">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="mt-2 text-gray-500" v-if="search || filterStatus || filterDate">
          Tidak ada data pembayaran profit yang sesuai dengan filter.
        </p>
        <p class="mt-2 text-gray-500" v-else>
          Belum ada data pembayaran profit.
        </p>
      </div>

      <!-- Tabel Profit -->
      <div v-else class="overflow-x-auto rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Investor</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="item in filteredProfits" :key="item.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ item.investor_name }}</div>
                <div class="text-sm text-gray-500">{{ item.investor_email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ item.package_name }}</div>
                <div class="text-sm text-gray-500">{{ formatCurrency(item.package_amount) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(item.schedule_date) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ formatCurrency(item.amount) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="{
                  'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                  'bg-yellow-100 text-yellow-800': item.status === 'pending',
                  'bg-green-100 text-green-800': item.status === 'paid',
                  'bg-blue-100 text-blue-800': item.status === 'withdraw'
                }">
                  {{ getStatusText(item.status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <button
                  v-if="item.status === 'pending'"
                  @click="markPaid(item)"
                  class="text-green-600 hover:text-green-900"
                  :disabled="isLoading"
                  title="Tandai sebagai lunas"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </button>
                <button
                  @click="viewDetail(item)"
                  class="text-blue-600 hover:text-blue-900"
                  :disabled="isLoading"
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

      <!-- Pagination (akan diimplementasikan setelah integrasi API) -->
      <div v-if="!isLoading && filteredProfits.length > 0" class="mt-6 flex items-center justify-between">
        <div class="text-sm text-gray-700">
          Menampilkan {{ filteredProfits.length }} dari {{ profits.length }} hasil
        </div>
        <!-- Pagination akan ditambahkan setelah API ready -->
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";

export default {
  name: "AdminProfit",
  components: { AdminLayout },
  data() {
    return {
      profits: [],       // Data dari API
      search: "",        // Pencarian
      filterStatus: "",  // Filter status
      filterDate: "",    // Filter tanggal
      isLoading: false   // Status loading
    };
  },
  computed: {
    filteredProfits() {
      // Filter data berdasarkan kriteria pencarian
      return this.profits.filter((item) => {
        const matchesSearch = !this.search || 
          item.investor_name.toLowerCase().includes(this.search.toLowerCase()) ||
          item.package_name.toLowerCase().includes(this.search.toLowerCase());
        
        const matchesStatus = !this.filterStatus || item.status === this.filterStatus;
        
        const matchesDate = !this.filterDate || item.schedule_date.includes(this.filterDate);
        
        return matchesSearch && matchesStatus && matchesDate;
      });
    },
  },
  methods: {
    // Mengambil data profit dari API
    fetchProfits() {
      this.isLoading = true;
      axios.get("/api/admin/profits")
        .then(response => {
          this.profits = response.data.data || [];
        })
        .catch(error => {
          console.error("Gagal mengambil data profit:", error);
          this.$toast.error("Gagal memuat data pembayaran profit");
        })
        .finally(() => {
          this.isLoading = false;
        });
    },

    // Generate profit 15 hari
    generateProfit() {
      this.isLoading = true;
      axios.post("/api/admin/profits/generate")
        .then(response => {
          this.fetchProfits(); // Refresh data
          this.$toast.success("Pembayaran profit berhasil digenerate");
        })
        .catch(error => {
          console.error("Gagal generate profit:", error);
          this.$toast.error("Gagal menggenerate pembayaran profit");
        })
        .finally(() => {
          this.isLoading = false;
        });
    },

    // Tandai profit sebagai lunas
    markPaid(item) {
      this.isLoading = true;
      axios.post(`/api/admin/profits/${item.id}/mark-paid`)
        .then(() => {
          // Update status langsung di data lokal
          item.status = "paid";
          this.$toast.success("Pembayaran berhasil ditandai sebagai lunas");
        })
        .catch(error => {
          console.error("Gagal tandai lunas:", error);
          this.$toast.error("Gagal menandai pembayaran sebagai lunas");
        })
        .finally(() => {
          this.isLoading = false;
        });
    },

    // Lihat detail profit
    viewDetail(item) {
      this.$inertia.get(`/admin/profits/${item.id}`);
    },

    // Format currency
    formatCurrency(value) {
      if (!value) return "-";
      return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0
      }).format(value);
    },

    // Format tanggal
    formatDate(dateString) {
      if (!dateString) return "-";
      return new Date(dateString).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
      });
    },

    // Teks status
    getStatusText(status) {
      const statusMap = {
        'pending': 'Pending',
        'paid': 'Lunas',
        'withdraw': 'Penarikan'
      };
      return statusMap[status] || status;
    }
  },
  mounted() {
    this.fetchProfits();
  }
};
</script>