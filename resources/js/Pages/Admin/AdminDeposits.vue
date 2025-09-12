<template>
  <AdminLayout title="Manajemen Deposit" :user="user">
    <div class="bg-white shadow rounded p-4">
      <h2 class="font-bold mb-4 text-lg">Daftar Deposit</h2>

      <!-- Filter & Search -->
      <div
        class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-2 md:space-y-0"
      >
        <input
          v-model="search"
          type="text"
          placeholder="Cari deposit berdasarkan nama atau email..."
          class="border p-2 rounded w-full md:w-1/3"
        />

        <select v-model="filterStatus" class="border p-2 rounded w-full md:w-1/4">
          <option value="">Semua Status</option>
          <option value="pending">Pending</option>
          <option value="processing">Diproses</option>
          <option value="completed">Selesai</option>
          <option value="rejected">Ditolak</option>
        </select>
      </div>

      <!-- Tabel Deposit -->
      <div class="overflow-x-auto">
        <table class="w-full table-auto border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 border">ID</th>
              <th class="px-4 py-2 border">Nama Investor</th>
              <th class="px-4 py-2 border">Email</th>
              <th class="px-4 py-2 border">Jumlah</th>
              <th class="px-4 py-2 border">Metode</th>
              <th class="px-4 py-2 border">Status</th>
              <th class="px-4 py-2 border">Tanggal</th>
              <th class="px-4 py-2 border">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="deposit in filteredDeposits" :key="deposit.id">
              <td class="px-4 py-2 border">{{ deposit.id }}</td>
              <td class="px-4 py-2 border">{{ deposit.user.name }}</td>
              <td class="px-4 py-2 border">{{ deposit.user.email }}</td>
              <td class="px-4 py-2 border">Rp {{ deposit.amount.toLocaleString() }}</td>
              <td class="px-4 py-2 border">{{ deposit.method }}</td>
              <td class="px-4 py-2 border capitalize">{{ deposit.status }}</td>
              <td class="px-4 py-2 border">{{ formatDate(deposit.created_at) }}</td>
              <td class="px-4 py-2 border space-x-2">
                <button
                  class="bg-blue-500 text-white px-2 py-1 rounded"
                  @click="viewDetail(deposit)"
                >
                  Detail
                </button>
                <button
                  v-if="deposit.status === 'pending'"
                  class="bg-green-500 text-white px-2 py-1 rounded"
                  @click="validateDeposit(deposit)"
                >
                  Validasi
                </button>
                <button
                  v-if="deposit.status === 'pending'"
                  class="bg-yellow-500 text-white px-2 py-1 rounded"
                  @click="confirmManual(deposit)"
                >
                  Konfirmasi Manual
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <p v-if="filteredDeposits.length === 0" class="text-center p-4 text-gray-500">
          Tidak ada deposit ditemukan.
        </p>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";

export default {
  name: "AdminDeposits",
  components: { AdminLayout },
  data() {
    return {
      user: { name: "Admin", role: "Administrator" },
      deposits: [], // data akan diambil dari API
      search: "",
      filterStatus: "",
    };
  },
  computed: {
    filteredDeposits() {
      return this.deposits.filter((d) => {
        const matchesSearch =
          !this.search ||
          d.user.name.toLowerCase().includes(this.search.toLowerCase()) ||
          d.user.email.toLowerCase().includes(this.search.toLowerCase());
        const matchesStatus = !this.filterStatus || d.status === this.filterStatus;
        return matchesSearch && matchesStatus;
      });
    },
  },
  methods: {
    fetchDeposits() {
      axios
        .get("/api/admin/deposits")
        .then((res) => {
          this.deposits = res.data;
        })
        .catch((err) => console.error("Gagal mengambil deposit:", err));
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "short",
        day: "numeric",
      });
    },
    viewDetail(deposit) {
      // redirect ke detail deposit
      this.$inertia.get(`/admin/deposits/${deposit.id}`);
    },
    validateDeposit(deposit) {
      axios
        .post(`/api/admin/deposits/${deposit.id}/validate`)
        .then(() => {
          deposit.status = "processing";
        })
        .catch((err) => console.error(err));
    },
    confirmManual(deposit) {
      axios
        .post(`/api/admin/deposits/${deposit.id}/manual-confirm`)
        .then(() => {
          deposit.status = "completed";
        })
        .catch((err) => console.error(err));
    },
  },
  mounted() {
    this.fetchDeposits();
  },
};
</script>
