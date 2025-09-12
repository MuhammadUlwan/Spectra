<template>
  <AdminLayout title="Manajemen Kontrak">
    <div class="bg-white shadow rounded p-4">
      <h2 class="font-bold mb-4 text-lg">Daftar Kontrak</h2>

      <!-- Filter & Search -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-2 md:space-y-0">
        <input
          v-model="search"
          type="text"
          placeholder="Cari kontrak (Investor / Paket)..."
          class="border p-2 rounded w-full md:w-1/3"
        />
        <select v-model="filterStatus" class="border p-2 rounded w-full md:w-1/4">
          <option value="">Semua Status</option>
          <option value="active">Aktif</option>
          <option value="completed">Selesai</option>
        </select>
      </div>

      <!-- Tabel Kontrak -->
      <div class="overflow-x-auto">
        <table class="w-full table-auto border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 border">ID</th>
              <th class="px-4 py-2 border">Investor</th>
              <th class="px-4 py-2 border">Paket</th>
              <th class="px-4 py-2 border">Tanggal Mulai</th>
              <th class="px-4 py-2 border">Tanggal Akhir</th>
              <th class="px-4 py-2 border">Status</th>
              <th class="px-4 py-2 border">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="contract in filteredContracts" :key="contract.id">
              <td class="px-4 py-2 border">{{ contract.id }}</td>
              <td class="px-4 py-2 border">{{ contract.investor_name }}</td>
              <td class="px-4 py-2 border">{{ contract.package_name }}</td>
              <td class="px-4 py-2 border">{{ contract.start_date }}</td>
              <td class="px-4 py-2 border">{{ contract.end_date }}</td>
              <td class="px-4 py-2 border capitalize">{{ contract.status }}</td>
              <td class="px-4 py-2 border space-x-2">
                <button
                  class="bg-blue-500 text-white px-2 py-1 rounded"
                  @click="viewDetail(contract)"
                >
                  Detail
                </button>
                <button
                  class="bg-green-500 text-white px-2 py-1 rounded"
                  @click="monitorProfit(contract)"
                >
                  Profit
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <p v-if="filteredContracts.length === 0" class="text-center p-4 text-gray-500">
          Tidak ada kontrak ditemukan.
        </p>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";

export default {
  name: "AdminContracts",
  components: { AdminLayout },
  data() {
    return {
      contracts: [],
      search: "",
      filterStatus: "",
    };
  },
  computed: {
    filteredContracts() {
      return this.contracts.filter((c) => {
        return (
          (!this.search ||
            c.investor_name.toLowerCase().includes(this.search.toLowerCase()) ||
            c.package_name.toLowerCase().includes(this.search.toLowerCase())) &&
          (!this.filterStatus || c.status === this.filterStatus)
        );
      });
    },
  },
  methods: {
    fetchContracts() {
      axios
        .get("/api/admin/contracts")
        .then((res) => {
          this.contracts = res.data;
        })
        .catch((err) => console.error("Gagal mengambil data kontrak:", err));
    },
    viewDetail(contract) {
      this.$inertia.get(`/admin/contracts/${contract.id}`);
    },
    monitorProfit(contract) {
      this.$inertia.get(`/admin/contracts/${contract.id}/profit`);
    },
  },
  mounted() {
    this.fetchContracts();
  },
};
</script>
