<template>
  <AdminLayout title="Komisi & Bonus">
    <div class="bg-white shadow rounded p-4">

      <!-- Header -->
      <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 space-y-2 md:space-y-0">
        <h2 class="font-bold text-lg">Riwayat Komisi & Bonus</h2>
        <button
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
          @click="generateCommissions"
        >
          Generate Komisi
        </button>
      </div>

      <!-- Filter -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-2 md:space-y-0">
        <input
          v-model="search"
          type="text"
          placeholder="Cari konsultan atau sponsor..."
          class="border p-2 rounded w-full md:w-1/3"
        />
        <select v-model="filterStatus" class="border p-2 rounded w-full md:w-1/4">
          <option value="">Semua Status</option>
          <option value="pending">Pending</option>
          <option value="paid">Lunas</option>
        </select>
      </div>

      <!-- Tabel Komisi -->
      <div class="overflow-x-auto">
        <table class="w-full table-auto border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 border">ID</th>
              <th class="px-4 py-2 border">Konsultan</th>
              <th class="px-4 py-2 border">Sponsor Fee</th>
              <th class="px-4 py-2 border">Profit Sharing</th>
              <th class="px-4 py-2 border">Bonus Omset</th>
              <th class="px-4 py-2 border">Status</th>
              <th class="px-4 py-2 border">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in filteredCommissions" :key="item.id">
              <td class="px-4 py-2 border">{{ item.id }}</td>
              <td class="px-4 py-2 border">{{ item.consultant_name }}</td>
              <td class="px-4 py-2 border">{{ item.sponsor_fee | currency }}</td>
              <td class="px-4 py-2 border">{{ item.profit_sharing | currency }}</td>
              <td class="px-4 py-2 border">{{ item.bonus_omset | currency }}</td>
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

        <p v-if="filteredCommissions.length === 0" class="text-center p-4 text-gray-500">
          Tidak ada data komisi ditemukan.
        </p>
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
    };
  },
  computed: {
    filteredCommissions() {
      return this.commissions.filter((c) => {
        return (
          (!this.search ||
            c.consultant_name.toLowerCase().includes(this.search.toLowerCase())) &&
          (!this.filterStatus || c.status === this.filterStatus)
        );
      });
    },
  },
  methods: {
    fetchCommissions() {
      axios
        .get("/api/admin/commissions")
        .then((res) => {
          this.commissions = res.data;
        })
        .catch((err) => console.error("Gagal mengambil data komisi:", err));
    },
    generateCommissions() {
      axios.post("/api/admin/commissions/generate")
        .then(() => this.fetchCommissions())
        .catch((err) => console.error("Gagal generate komisi:", err));
    },
    markPaid(item) {
      axios.post(`/api/admin/commissions/${item.id}/mark-paid`)
        .then(() => {
          item.status = "paid";
        })
        .catch((err) => console.error("Gagal tandai lunas:", err));
    },
    viewDetail(item) {
      this.$inertia.get(`/admin/commissions/${item.id}`);
    },
  },
  mounted() {
    this.fetchCommissions();
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
