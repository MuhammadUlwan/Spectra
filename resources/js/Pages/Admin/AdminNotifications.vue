<template>
  <AdminLayout title="Notifikasi & Broadcast">
    <div class="bg-white shadow rounded p-4 space-y-6">

      <!-- Kirim Notifikasi -->
      <div>
        <h2 class="font-bold mb-4 text-lg">Kirim Notifikasi / Broadcast</h2>
        <form @submit.prevent="sendNotification" class="space-y-4">

          <div class="space-y-2">
            <label class="font-semibold">Judul Notifikasi</label>
            <input
              type="text"
              v-model="notification.title"
              placeholder="Judul..."
              class="border p-2 rounded w-full"
              required
            />
          </div>

          <div class="space-y-2">
            <label class="font-semibold">Isi Pesan</label>
            <textarea
              v-model="notification.message"
              placeholder="Isi pesan..."
              class="border p-2 rounded w-full"
              rows="4"
              required
            ></textarea>
          </div>

          <div class="flex flex-col md:flex-row md:items-center md:gap-4">
            <label class="inline-flex items-center gap-2">
              <input type="checkbox" v-model="notification.send_to_all" class="form-checkbox" />
              Kirim ke semua pengguna
            </label>

            <select v-model="notification.template" class="border p-2 rounded w-full md:w-1/3">
              <option value="">Pilih Template Email (Opsional)</option>
              <option v-for="tpl in templates" :key="tpl.id" :value="tpl.id">{{ tpl.name }}</option>
            </select>
          </div>

          <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Kirim Notifikasi
          </button>
        </form>
      </div>

      <!-- Riwayat Notifikasi -->
      <div>
        <h2 class="font-bold mb-4 text-lg">Riwayat Notifikasi</h2>
        <div class="overflow-x-auto">
          <table class="w-full table-auto border">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-4 py-2 border">ID</th>
                <th class="px-4 py-2 border">Judul</th>
                <th class="px-4 py-2 border">Pesan</th>
                <th class="px-4 py-2 border">Template</th>
                <th class="px-4 py-2 border">Waktu Kirim</th>
                <th class="px-4 py-2 border">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in notifications" :key="item.id">
                <td class="px-4 py-2 border">{{ item.id }}</td>
                <td class="px-4 py-2 border">{{ item.title }}</td>
                <td class="px-4 py-2 border">{{ item.message }}</td>
                <td class="px-4 py-2 border">{{ item.template_name || '-' }}</td>
                <td class="px-4 py-2 border">{{ formatDate(item.sent_at) }}</td>
                <td class="px-4 py-2 border">
                  <button
                    @click="resendNotification(item.id)"
                    class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600"
                  >
                    Kirim Ulang
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

          <p v-if="notifications.length === 0" class="text-center p-4 text-gray-500">
            Belum ada notifikasi terkirim.
          </p>
        </div>
      </div>

    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";

export default {
  name: "AdminNotifications",
  components: { AdminLayout },
  data() {
    return {
      notification: {
        title: "",
        message: "",
        send_to_all: true,
        template: "",
      },
      templates: [], // template email dari API
      notifications: [], // riwayat notifikasi
    };
  },
  methods: {
    fetchTemplates() {
      axios.get("/api/admin/notification-templates")
        .then(res => this.templates = res.data)
        .catch(err => console.error("Gagal ambil templates:", err));
    },
    fetchNotifications() {
      axios.get("/api/admin/notifications")
        .then(res => this.notifications = res.data)
        .catch(err => console.error("Gagal ambil notifikasi:", err));
    },
    sendNotification() {
      axios.post("/api/admin/notifications", this.notification)
        .then(() => {
          alert("Notifikasi berhasil dikirim!");
          this.notification.title = "";
          this.notification.message = "";
          this.notification.template = "";
          this.fetchNotifications();
        })
        .catch(err => console.error(err));
    },
    resendNotification(id) {
      axios.post(`/api/admin/notifications/${id}/resend`)
        .then(() => alert("Notifikasi dikirim ulang!"))
        .catch(err => console.error(err));
    },
    formatDate(date) {
      return new Date(date).toLocaleString();
    },
  },
  mounted() {
    this.fetchTemplates();
    this.fetchNotifications();
  },
};
</script>
