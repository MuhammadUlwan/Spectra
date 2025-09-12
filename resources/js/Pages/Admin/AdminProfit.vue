<template>
  <AdminLayout title="Pembayaran Profit">
    <div class="bg-white shadow rounded p-4">

      <!-- Header -->
      <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 space-y-2 md:space-y-0">
        <h2 class="font-bold text-lg">Riwayat Pembayaran Profit</h2>
        <button
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
          @click="generateProfit"
        >
          Generate Pembayaran Profit
        </button>
      </div>

      <!-- Filter -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-2 md:space-y-0">
        <input
          v-model="search"
          type="text"
          placeholder="Cari investor atau paket..."
          class="border p-2 rounded w-full md:w-1/3"
        />
        <select v-model="filterStatus" class="border p-2 rounded w-full md:w-1/4">
          <option value="">Semua Status</option>
          <option value="pending">Pending</option>
          <option value="paid">Lunas</option>
        </select>
      </div>

      <!-- Tabel Profit -->
      <div class="overflow-x-auto">
        <table class="w-full table-auto border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 border">ID</th>
              <th class="px-4 py-2 border">Investor</th>
              <th class="px-4 py-2 border">Paket</th>
              <th class="px-4 py-2 border">Jadwal Pembayaran</th>
              <th class="px-4 py-2 border">Jumlah Profit</th>
              <th class="px-4 py-2 border">Status</th>
              <th class="px-4 py-2 border">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in filteredProfits" :key="item.id">
              <td class="px-4 py-2 border">{{ item.id }}</td>
              <td class="px-4 py-2 border">{{ item.investor_name }}</td>
              <td class="px-4 py-2 border">{{ item.package_name }}</td>
              <td class="px-4 py-2 border">{{ item.schedule_date }}</td>
              <td class="px-4 py-2 border">{{ item.amount | currency }}</td>
              <td class="px-4 py-2 border capitalize">{{ item.status }}</td>
              <td class="px-4 py-2 border space-x-2">
                <button
                  v-if="item.status === 'pending'"
                  class="bg-green-500 text-white px-2 py-1 rounded"
                  @click="markPaid(item)"
                >
                  Tandai Lunas
                </button>
                <button
                  class="bg-blue-500 text-white px-2 py-1 rounded"
                  @click="viewDetail(item)"
                >
                  Detail
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <p v-if="filteredProfits.length === 0" class="text-center p-4 text-gray-500">
          Tidak ada data pembayaran profit ditemukan.
        </p>
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
      profits: [],
      search: "",
      filterStatus: "",
    };
  },
  computed: {
    filteredProfits() {
      return this.profits.filter((p) => {
        return (
          (!this.search ||
            p.investor_name.toLowerCase().includes(this.search.toLowerCase()) ||
            p.package_name.toLowerCase().includes(this.search.toLowerCase())) &&
          (!this.filterStatus || p.status === this.filterStatus)
        );
      });
    },
  },
  methods: {
    fetchProfits() {
      axios
        .get("/api/admin/profits")
        .then((res) => {
          this.profits = res.data;
        })
        .catch((err) => console.error("Gagal mengambil data profit:", err));
    },
    generateProfit() {
      axios.post("/api/admin/profits/generate")
        .then(() => {
          this.fetchProfits();
        })
        .catch((err) => console.error("Gagal generate profit:", err));
    },
    markPaid(item) {
      axios.post(`/api/admin/profits/${item.id}/mark-paid`)
        .then(() => {
          item.status = "paid";
        })
        .catch((err) => console.error("Gagal tandai lunas:", err));
    },
    viewDetail(item) {
      this.$inertia.get(`/admin/profits/${item.id}`);
    },
  },
  mounted() {
    this.fetchProfits();
  },
  filters: {
    currency(value) {
      if (!value) return "-";
      return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
      }).format(value);
    },
  },
};
</script>
