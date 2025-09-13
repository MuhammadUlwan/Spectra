<template>
  <AdminLayout title="Notifikasi & Broadcast">
    <div class="bg-white shadow-xl rounded-2xl p-4 md:p-6 space-y-8">

      <!-- Header -->
      <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">Manajemen Notifikasi</h2>
          <p class="text-gray-600 mt-1">Kelola pengiriman notifikasi dan broadcast ke pengguna</p>
        </div>
      </div>

      <!-- Kirim Notifikasi -->
      <div class="bg-gray-50 p-5 rounded-2xl">
        <h3 class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          Kirim Notifikasi / Broadcast
        </h3>
        
        <form @submit.prevent="sendNotification" class="space-y-6">
          <!-- Judul -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Notifikasi</label>
            <input
              type="text"
              v-model="notification.title"
              placeholder="Masukkan judul notifikasi..."
              class="w-full border border-gray-300 rounded-xl py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              required
              :disabled="isLoading"
            />
          </div>

          <!-- Pesan -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Isi Pesan</label>
            <textarea
              v-model="notification.message"
              placeholder="Tulis isi pesan notifikasi di sini..."
              class="w-full border border-gray-300 rounded-xl py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              rows="4"
              required
              :disabled="isLoading"
            ></textarea>
          </div>

          <!-- Opsi Pengiriman -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center">
              <input 
                type="checkbox" 
                v-model="notification.send_to_all" 
                id="send-to-all"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                :disabled="isLoading"
              />
              <label for="send-to-all" class="ml-2 block text-sm text-gray-700">
                Kirim ke semua pengguna
              </label>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Template Email</label>
              <select 
                v-model="notification.template" 
                class="w-full border border-gray-300 rounded-xl py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :disabled="isLoading"
              >
                <option value="">Pilih Template (Opsional)</option>
                <option v-for="tpl in templates" :key="tpl.id" :value="tpl.id">
                  {{ tpl.name }}
                </option>
              </select>
            </div>
          </div>

          <button 
            type="submit" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl transition-colors duration-200 flex items-center justify-center"
            :disabled="isLoading"
          >
            <span class="animate-spin mr-2" v-if="isLoading">⟳</span>
            {{ isLoading ? 'Mengirim...' : 'Kirim Notifikasi' }}
          </button>
        </form>
      </div>

      <!-- Riwayat Notifikasi -->
      <div class="bg-gray-50 p-5 rounded-2xl">
        <h3 class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Riwayat Notifikasi
        </h3>

        <div v-if="isLoading && notifications.length === 0" class="text-center py-8">
          <div class="animate-spin inline-block w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full"></div>
          <p class="mt-2 text-gray-600">Memuat riwayat notifikasi...</p>
        </div>

        <div v-else>
          <div v-if="notifications.length === 0" class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <p class="mt-2 text-gray-500">Belum ada notifikasi terkirim.</p>
          </div>

          <!-- Desktop Table -->
          <div v-else class="hidden md:block overflow-x-auto rounded-2xl shadow-sm border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-100">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penerima</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Template</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Kirim</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="item in notifications" :key="item.id" class="hover:bg-gray-50 transition-colors duration-150">
                  <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ item.title }}</div>
                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ truncateText(item.message, 50) }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ item.send_to_all ? 'Semua Pengguna' : 'Pengguna Tertentu' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ item.template_name || '-' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(item.sent_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="statusClass(item.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                      {{ getStatusText(item.status) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button
                      @click="resendNotification(item.id)"
                      class="text-blue-600 hover:text-blue-900 transition-colors duration-200 flex items-center"
                      :disabled="isLoading"
                      title="Kirim Ulang"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                      </svg>
                      Kirim Ulang
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Mobile Cards -->
          <div v-if="notifications.length > 0" class="md:hidden space-y-4">
            <div v-for="item in notifications" :key="item.id" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200">
              <div class="flex justify-between items-start mb-3">
                <div>
                  <h3 class="font-medium text-gray-900">{{ item.title }}</h3>
                  <p class="text-sm text-gray-500 mt-1">{{ truncateText(item.message, 80) }}</p>
                </div>
                <span :class="statusClass(item.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                  {{ getStatusText(item.status) }}
                </span>
              </div>
              
              <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                  <p class="text-xs text-gray-500">Penerima</p>
                  <p class="font-medium">{{ item.send_to_all ? 'Semua' : 'Tertentu' }}</p>
                </div>
                <div>
                  <p class="text-xs text-gray-500">Template</p>
                  <p class="font-medium">{{ item.template_name || '-' }}</p>
                </div>
                <div>
                  <p class="text-xs text-gray-500">Waktu</p>
                  <p class="font-medium">{{ formatDateShort(item.sent_at) }}</p>
                </div>
              </div>
              
              <div class="mt-4">
                <button
                  @click="resendNotification(item.id)"
                  class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded-xl text-sm font-medium transition-colors duration-200 flex items-center justify-center"
                  :disabled="isLoading"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                  Kirim Ulang
                </button>
              </div>
            </div>
          </div>
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
      templates: [],
      notifications: [],
      isLoading: false
    };
  },
  methods: {
    fetchTemplates() {
      this.isLoading = true;
      axios.get("/api/admin/notification-templates")
        .then(response => {
          this.templates = response.data || [];
        })
        .catch(error => console.error(error))
        .finally(() => { this.isLoading = false });
    },
    fetchNotifications() {
      this.isLoading = true;
      axios.get("/api/admin/notifications")
        .then(response => {
          this.notifications = response.data || [];
        })
        .catch(error => console.error(error))
        .finally(() => { this.isLoading = false });
    },
    sendNotification() {
      this.isLoading = true;
      axios.post("/api/admin/notifications", this.notification)
        .then(() => {
          this.notification.title = "";
          this.notification.message = "";
          this.notification.template = "";
          this.fetchNotifications();
        })
        .catch(error => console.error(error))
        .finally(() => { this.isLoading = false });
    },
    resendNotification(id) {
      this.isLoading = true;
      axios.post(`/api/admin/notifications/${id}/resend`)
        .then(() => { this.fetchNotifications() })
        .catch(error => console.error(error))
        .finally(() => { this.isLoading = false });
    },
    formatDate(dateString) {
      if (!dateString) return "-";
      return new Date(dateString).toLocaleDateString('id-ID', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
      });
    },
    formatDateShort(dateString) {
      if (!dateString) return "-";
      return new Date(dateString).toLocaleDateString('id-ID', {
        day: '2-digit', month: 'short', year: 'numeric'
      });
    },
    truncateText(text, length) {
      if (!text) return "";
      return text.length > length ? text.substring(0, length) + "..." : text;
    },
    statusClass(status) {
      const map = { sent: 'bg-green-100 text-green-800', pending: 'bg-yellow-100 text-yellow-800', failed: 'bg-red-100 text-red-800' };
      return map[status] || 'bg-gray-100 text-gray-800';
    },
    getStatusText(status) {
      const map = { sent: 'Terkirim', pending: 'Pending', failed: 'Gagal' };
      return map[status] || status;
    }
  },
  mounted() {
    this.fetchTemplates();
    this.fetchNotifications();
  }
};
</script>
