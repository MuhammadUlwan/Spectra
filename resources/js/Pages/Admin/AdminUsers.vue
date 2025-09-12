<template>
  <AdminLayout title="Manajemen Pengguna">
    <div class="bg-white shadow rounded p-4">
      <h2 class="font-bold mb-4 text-lg">Daftar Pengguna</h2>

      <!-- Filter & Search -->
      <div
        class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-2 md:space-y-0"
      >
        <input
          v-model="search"
          type="text"
          placeholder="Cari pengguna..."
          class="border p-2 rounded w-full md:w-1/3"
        />

        <select v-model="filterRole" class="border p-2 rounded w-full md:w-1/4">
          <option value="">Semua Tipe</option>
          <option value="investor">Investor</option>
          <option value="konsultan">Konsultan</option>
        </select>

        <select v-model="filterStatus" class="border p-2 rounded w-full md:w-1/4">
          <option value="">Semua Status</option>
          <option value="active">Aktif</option>
          <option value="inactive">Non-aktif</option>
        </select>
      </div>

      <!-- Tabel Pengguna -->
      <div class="overflow-x-auto">
        <table class="w-full table-auto border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 border">ID</th>
              <th class="px-4 py-2 border">Nama</th>
              <th class="px-4 py-2 border">Email</th>
              <th class="px-4 py-2 border">Tipe</th>
              <th class="px-4 py-2 border">Status</th>
              <th class="px-4 py-2 border">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in filteredUsers" :key="user.id">
              <td class="px-4 py-2 border">{{ user.id }}</td>
              <td class="px-4 py-2 border">{{ user.name }}</td>
              <td class="px-4 py-2 border">{{ user.email }}</td>
              <td class="px-4 py-2 border capitalize">{{ user.role }}</td>
              <td class="px-4 py-2 border capitalize">{{ user.status }}</td>
              <td class="px-4 py-2 border space-x-2">
                <button
                  class="bg-blue-500 text-white px-2 py-1 rounded"
                  @click="viewDetail(user)"
                >
                  Detail
                </button>
                <button
                  @click="toggleStatus(user)"
                  :class="user.status === 'active' ? 'bg-red-500' : 'bg-green-500'"
                  class="text-white px-2 py-1 rounded"
                >
                  {{ user.status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <p v-if="filteredUsers.length === 0" class="text-center p-4 text-gray-500">
          Tidak ada pengguna ditemukan.
        </p>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";

export default {
  name: "AdminUsers",
  components: { AdminLayout },
  data() {
    return {
      users: [],
      search: "",
      filterRole: "",
      filterStatus: "",
    };
  },
  computed: {
    filteredUsers() {
      return this.users.filter((u) => {
        return (
          (!this.search ||
            u.name.toLowerCase().includes(this.search.toLowerCase()) ||
            u.email.toLowerCase().includes(this.search.toLowerCase())) &&
          (!this.filterRole || u.role === this.filterRole) &&
          (!this.filterStatus || u.status === this.filterStatus)
        );
      });
    },
  },
  methods: {
    fetchUsers() {
      axios
        .get("/api/admin/users")
        .then((res) => {
          this.users = res.data;
        })
        .catch((err) => {
          console.error("Gagal mengambil data pengguna:", err);
        });
    },
    viewDetail(user) {
      // bisa redirect ke halaman detail user
      this.$inertia.get(`/admin/users/${user.id}`);
    },
    toggleStatus(user) {
      const newStatus = user.status === "active" ? "inactive" : "active";
      axios
        .post(`/api/admin/users/${user.id}/toggle-status`, { status: newStatus })
        .then(() => {
          user.status = newStatus;
        })
        .catch((err) => console.error(err));
    },
  },
  mounted() {
    this.fetchUsers();
  },
};
</script>
