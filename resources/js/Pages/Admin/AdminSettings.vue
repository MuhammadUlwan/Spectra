<template>
  <AdminLayout title="Pengaturan Sistem & Notifikasi">
    <div class="bg-white shadow rounded p-4 space-y-6">

      <!-- Tabs -->
      <div class="flex flex-wrap gap-2 mb-4">
        <button
          v-for="tab in tabs"
          :key="tab.value"
          @click="currentTab = tab.value"
          :class="currentTab === tab.value ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'"
          class="px-4 py-2 rounded"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- Settings Form -->
      <div v-if="currentTab === 'system'">
        <form @submit.prevent="saveSettings" class="space-y-4">

          <!-- Paket Investasi -->
          <div class="space-y-2">
            <h3 class="font-semibold">Paket Investasi</h3>
            <div class="flex flex-col md:flex-row gap-2">
              <input type="number" v-model.number="settings.investment.value" placeholder="Nilai Paket" class="border p-2 rounded flex-1" />
              <input type="number" v-model.number="settings.investment.min" placeholder="Minimum" class="border p-2 rounded flex-1" />
              <input type="number" v-model.number="settings.investment.max" placeholder="Maksimum" class="border p-2 rounded flex-1" />
            </div>
          </div>

          <!-- Persentase Profit Investor -->
          <div class="space-y-2">
            <h3 class="font-semibold">Persentase Profit Investor (%)</h3>
            <input type="number" v-model.number="settings.profit_percentage" class="border p-2 rounded w-full md:w-1/4" />
          </div>

          <!-- Komisi Konsultan -->
          <div class="space-y-2">
            <h3 class="font-semibold">Komisi Konsultan</h3>
            <div class="flex flex-col md:flex-row gap-2">
              <input type="number" v-model.number="settings.commission.sponsor_fee" placeholder="Sponsor Fee (%)" class="border p-2 rounded flex-1" />
              <input type="number" v-model.number="settings.commission.profit_sharing" placeholder="Profit Sharing (%)" class="border p-2 rounded flex-1" />
            </div>
          </div>

          <!-- Bonus Omset -->
          <div class="space-y-2">
            <h3 class="font-semibold">Bonus Omset (%)</h3>
            <input type="number" v-model.number="settings.bonus" class="border p-2 rounded w-full md:w-1/4" />
          </div>

          <!-- Durasi Kontrak -->
          <div class="space-y-2">
            <h3 class="font-semibold">Durasi Kontrak (Hari)</h3>
            <input type="number" v-model.number="settings.contract_duration" class="border p-2 rounded w-full md:w-1/4" />
          </div>

          <!-- Pengaturan Umum Sistem -->
          <div class="space-y-2">
            <h3 class="font-semibold">Pengaturan Umum</h3>
            <textarea v-model="settings.general_notes" placeholder="Catatan/Informasi Umum Sistem" class="border p-2 rounded w-full"></textarea>
          </div>

          <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Simpan Pengaturan
          </button>
        </form>
      </div>

      <!-- Notifikasi & Broadcast -->
      <div v-if="currentTab === 'notifications'">
        <form @submit.prevent="sendNotification" class="space-y-4">
          <div class="space-y-2">
            <h3 class="font-semibold">Judul Notifikasi / Broadcast</h3>
            <input type="text" v-model="notification.title" class="border p-2 rounded w-full" placeholder="Judul..." />
          </div>

          <div class="space-y-2">
            <h3 class="font-semibold">Pesan</h3>
            <textarea v-model="notification.message" class="border p-2 rounded w-full" placeholder="Isi pesan..."></textarea>
          </div>

          <div class="space-y-2">
            <label class="inline-flex items-center gap-2">
              <input type="checkbox" v-model="notification.send_to_all" class="form-checkbox" />
              Kirim ke semua pengguna
            </label>
          </div>

          <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Kirim Notifikasi / Broadcast
          </button>
        </form>
      </div>

    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";

export default {
  name: "AdminSettings",
  components: { AdminLayout },
  data() {
    return {
      tabs: [
        { label: "Pengaturan Sistem", value: "system" },
        { label: "Notifikasi & Broadcast", value: "notifications" },
      ],
      currentTab: "system",
      settings: {
        investment: { value: 0, min: 0, max: 0 },
        profit_percentage: 0,
        commission: { sponsor_fee: 0, profit_sharing: 0 },
        bonus: 0,
        contract_duration: 0,
        general_notes: "",
      },
      notification: {
        title: "",
        message: "",
        send_to_all: true,
      },
    };
  },
  methods: {
    fetchSettings() {
      axios.get("/api/admin/settings")
        .then(res => {
          this.settings = res.data;
        })
        .catch(err => console.error("Gagal mengambil pengaturan:", err));
    },
    saveSettings() {
      axios.post("/api/admin/settings", this.settings)
        .then(() => alert("Pengaturan berhasil disimpan!"))
        .catch(err => console.error(err));
    },
    sendNotification() {
      axios.post("/api/admin/notifications", this.notification)
        .then(() => alert("Notifikasi / Broadcast terkirim!"))
        .catch(err => console.error(err));
    },
  },
  mounted() {
    this.fetchSettings();
  },
};
</script>
