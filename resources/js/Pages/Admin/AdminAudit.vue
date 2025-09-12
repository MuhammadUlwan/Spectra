<template>
  <AdminLayout title="Audit Log">
    <div class="bg-white shadow rounded p-4 space-y-6">

      <!-- Filter & Search -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-2 md:space-y-0">
        <input
          v-model="search"
          type="text"
          placeholder="Cari aktivitas..."
          class="border p-2 rounded w-full md:w-1/3"
        />

        <select v-model="filterType" class="border p-2 rounded w-full md:w-1/4">
          <option value="">Semua Jenis</option>
          <option value="login">Login Admin</option>
          <option value="data_change">Perubahan Data</option>
          <option value="other">Lainnya</option>
        </select>
      </div>

      <!-- Tabel Audit -->
      <div class="overflow-x-auto">
        <table class="w-full table-auto border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 border">ID</th>
              <th class="px-4 py-2 border">Admin</th>
              <th class="px-4 py-2 border">Jenis Aktivitas</th>
              <th class="px-4 py-2 border">Deskripsi</th>
              <th class="px-4 py-2 border">Waktu</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="log in filteredLogs" :key="log.id">
              <td class="px-4 py-2 border">{{ log.id }}</td>
              <td class="px-4 py-2 border">{{ log.admin_name }}</td>
              <td class="px-4 py-2 border capitalize">{{ log.type.replace('_', ' ') }}</td>
              <td class="px-4 py-2 border">{{ log.description }}</td>
              <td class="px-4 py-2 border">{{ formatDate(log.created_at) }}</td>
            </tr>
          </tbody>
        </table>

        <p v-if="filteredLogs.length === 0" class="text-center p-4 text-gray-500">
          Tidak ada catatan audit.
        </p>
      </div>

    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";

export default {
  name: "AdminAudit",
  components: { AdminLayout },
  data() {
    return {
      logs: [],
      search: "",
      filterType: "",
    };
  },
  computed: {
    filteredLogs() {
      return this.logs.filter((log) => {
        return (
          (!this.search ||
            log.admin_name.toLowerCase().includes(this.search.toLowerCase()) ||
            log.description.toLowerCase().includes(this.search.toLowerCase())
          ) &&
          (!this.filterType || log.type === this.filterType)
        );
      });
    },
  },
  methods: {
    fetchAuditLogs() {
      axios.get("/api/admin/audit-logs")
        .then(res => this.logs = res.data)
        .catch(err => console.error("Gagal ambil audit log:", err));
    },
    formatDate(date) {
      return new Date(date).toLocaleString();
    },
  },
  mounted() {
    this.fetchAuditLogs();
  },
};
</script>
