<template>
  <AdminLayout title="Manajemen Withdraw">
    <div class="bg-white shadow rounded p-4">
      <h2 class="font-bold mb-4 text-lg">Daftar Permintaan Withdraw</h2>

      <!-- Filter & Search -->
      <div
        class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-2 md:space-y-0"
      >
        <input
          v-model="search"
          type="text"
          placeholder="Cari user atau metode pembayaran..."
          class="border p-2 rounded w-full md:w-1/3"
        />

        <select v-model="filterStatus" class="border p-2 rounded w-full md:w-1/4">
          <option value="">Semua Status</option>
          <option value="pending">Pending</option>
          <option value="processing">Diproses</option>
          <option value="completed">Selesai</option>
          <option value="rejected">Ditolak</option>
        </select>

        <button
          @click="exportData"
          class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
        >
          Export Massal
        </button>
      </div>

      <!-- Tabel Withdraw -->
      <div class="overflow-x-auto">
        <table class="w-full table-auto border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 border">ID</th>
              <th class="px-4 py-2 border">User</th>
              <th class="px-4 py-2 border">Jumlah</th>
              <th class="px-4 py-2 border">Metode</th>
              <th class="px-4 py-2 border">Status</th>
              <th class="px-4 py-2 border">Tanggal</th>
              <th class="px-4 py-2 border">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="wd in filteredWithdraws" :key="wd.id">
              <td class="px-4 py-2 border">{{ wd.id }}</td>
              <td class="px-4 py-2 border">{{ wd.user.name }}</td>
              <td class="px-4 py-2 border">{{ formatCurrency(wd.amount) }}</td>
              <td class="px-4 py-2 border">{{ wd.method }}</td>
              <td class="px-4 py-2 border capitalize">{{ wd.status }}</td>
              <td class="px-4 py-2 border">{{ formatDate(wd.created_at) }}</td>
              <td class="px-4 py-2 border space-x-1">
                <button
                  v-if="wd.status === 'pending'"
                  @click="processWithdraw(wd)"
                  class="bg-green-500 text-white px-2 py-1 rounded"
                >
                  Proses
                </button>
                <button
                  v-if="wd.status === 'processing'"
                  @click="uploadProof(wd)"
                  class="bg-blue-500 text-white px-2 py-1 rounded"
                >
                  Upload Bukti
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <p v-if="filteredWithdraws.length === 0" class="text-center p-4 text-gray-500">
          Tidak ada permintaan withdraw ditemukan.
        </p>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";

export default {
  name: "AdminWithdraws",
  components: { AdminLayout },
  data() {
    return {
      withdraws: [],
      search: "",
      filterStatus: "",
    };
  },
  computed: {
    filteredWithdraws() {
      return this.withdraws.filter((w) => {
        return (
          (!this.search ||
            w.user.name.toLowerCase().includes(this.search.toLowerCase()) ||
            w.method.toLowerCase().includes(this.search.toLowerCase())) &&
          (!this.filterStatus || w.status === this.filterStatus)
        );
      });
    },
  },
  methods: {
    fetchWithdraws() {
      axios
        .get("/api/admin/withdraws")
        .then((res) => (this.withdraws = res.data))
        .catch((err) => console.error("Gagal ambil data withdraw:", err));
    },
    processWithdraw(wd) {
      axios
        .post(`/api/admin/withdraws/${wd.id}/process`)
        .then(() => (wd.status = "processing"))
        .catch((err) => console.error(err));
    },
    uploadProof(wd) {
      const fileInput = document.createElement("input");
      fileInput.type = "file";
      fileInput.accept = "image/*,application/pdf";
      fileInput.onchange = (e) => {
        const formData = new FormData();
        formData.append("proof", e.target.files[0]);
        axios
          .post(`/api/admin/withdraws/${wd.id}/upload-proof`, formData, {
            headers: { "Content-Type": "multipart/form-data" },
          })
          .then(() => (wd.status = "completed"))
          .catch((err) => console.error(err));
      };
      fileInput.click();
    },
    exportData() {
      axios
        .get("/api/admin/withdraws/export", { responseType: "blob" })
        .then((res) => {
          const url = window.URL.createObjectURL(new Blob([res.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "withdraws.xlsx");
          document.body.appendChild(link);
          link.click();
        })
        .catch((err) => console.error(err));
    },
    formatCurrency(amount) {
      return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
      }).format(amount);
    },
    formatDate(date) {
      return new Date(date).toLocaleString();
    },
  },
  mounted() {
    this.fetchWithdraws();
  },
};
</script>

<style scoped>
/* Responsive table scrolling for mobile */
@media (max-width: 768px) {
  table {
    display: block;
    width: 100%;
  }
  thead,
  tbody,
  tr,
  th,
  td {
    display: block;
  }
  th,
  td {
    text-align: left;
    border: none;
    border-bottom: 1px solid #e5e7eb;
    padding: 0.5rem;
  }
  thead tr {
    display: none;
  }
}
</style>
