<template>
  <AdminLayout title="Pengaturan Sistem & Notifikasi" :activeLink="activeLink">
    <div class="min-h-screen bg-gray-50 py-4 px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Pengaturan Sistem</h1>
            <p class="text-gray-600 mt-1">Kelola pengaturan sistem dan notifikasi</p>
          </div>
        </div>

        <!-- Tabs -->
        <div class="flex flex-wrap gap-2 mb-6">
          <button
            v-for="tab in tabs"
            :key="tab.value"
            @click="currentTab = tab.value"
            :class="[
              'px-4 py-2 rounded-xl font-medium transition-all duration-200',
              currentTab === tab.value
                ? 'bg-blue-600 text-white shadow-md'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
          >
            <i :class="[tab.icon, 'mr-2']"></i>
            {{ tab.label }}
          </button>
        </div>

        <!-- Pengaturan Sistem -->
        <div v-if="currentTab === 'system'" class="space-y-6">
          <form @submit.prevent="saveSettings" class="space-y-6">
            <!-- Paket Investasi -->
            <div class="bg-gray-50 p-4 rounded-xl">
              <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-box-open mr-2 text-blue-500"></i>
                Paket Investasi
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Paket (Rp)</label>
                  <input
                    type="number"
                    v-model.number="settings.investment.value"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    min="0"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Minimum (Rp)</label>
                  <input
                    type="number"
                    v-model.number="settings.investment.min"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    min="0"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Maksimum (Rp)</label>
                  <input
                    type="number"
                    v-model.number="settings.investment.max"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    min="0"
                  />
                </div>
              </div>
            </div>

            <!-- Persentase Profit -->
            <div class="bg-gray-50 p-4 rounded-xl">
              <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-chart-line mr-2 text-green-500"></i>
                Persentase Profit
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Profit Investor (%)</label>
                  <input
                    type="number"
                    v-model.number="settings.profit_percentage"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    min="0"
                    max="100"
                    step="0.01"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Bonus Omset (%)</label>
                  <input
                    type="number"
                    v-model.number="settings.bonus"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    min="0"
                    max="100"
                    step="0.01"
                  />
                </div>
              </div>
            </div>

            <!-- Komisi Konsultan -->
            <div class="bg-gray-50 p-4 rounded-xl">
              <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-hand-holding-usd mr-2 text-yellow-500"></i>
                Komisi Konsultan
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Sponsor Fee (%)</label>
                  <input
                    type="number"
                    v-model.number="settings.commission.sponsor_fee"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    min="0"
                    max="100"
                    step="0.01"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Profit Sharing (%)</label>
                  <input
                    type="number"
                    v-model.number="settings.commission.profit_sharing"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    min="0"
                    max="100"
                    step="0.01"
                  />
                </div>
              </div>
            </div>

            <!-- Pengaturan Umum -->
            <div class="bg-gray-50 p-4 rounded-xl">
              <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-cog mr-2 text-gray-500"></i>
                Pengaturan Umum
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Durasi Kontrak (Hari)</label>
                  <input
                    type="number"
                    v-model.number="settings.contract_duration"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    min="1"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Masa Tenggang (Hari)</label>
                  <input
                    type="number"
                    v-model.number="settings.grace_period"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    min="0"
                  />
                </div>
              </div>
              <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Sistem</label>
                <textarea
                  v-model="settings.general_notes"
                  rows="3"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                  placeholder="Catatan penting untuk sistem..."
                ></textarea>
              </div>
            </div>

            <button
              type="submit"
              :disabled="isLoading"
              class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-colors flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <i class="fas fa-save mr-2"></i>
              {{ isLoading ? 'Menyimpan...' : 'Simpan Pengaturan' }}
            </button>
          </form>
        </div>

        <!-- Notifikasi & Broadcast -->
        <div v-if="currentTab === 'notifications'" class="space-y-6">
          <form @submit.prevent="sendNotification" class="space-y-6">
            <div class="bg-gray-50 p-4 rounded-xl">
              <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-bell mr-2 text-purple-500"></i>
                Kirim Notifikasi
              </h3>
              
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Notifikasi</label>
                <input
                  type="text"
                  v-model="notification.title"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                  placeholder="Judul notifikasi..."
                  required
                />
              </div>

              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                <textarea
                  v-model="notification.message"
                  rows="4"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                  placeholder="Isi pesan notifikasi..."
                  required
                ></textarea>
              </div>

              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Pengguna</label>
                <select
                  v-model="notification.user_type"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                >
                  <option value="all">Semua Pengguna</option>
                  <option value="investor">Investor</option>
                  <option value="consultant">Konsultan</option>
                  <option value="admin">Admin</option>
                </select>
              </div>

              <div class="flex items-center mb-4">
                <input
                  type="checkbox"
                  v-model="notification.is_urgent"
                  id="urgent"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                />
                <label for="urgent" class="ml-2 text-sm font-medium text-gray-700">Tandai sebagai penting</label>
              </div>

              <button
                type="submit"
                :disabled="isLoading"
                class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-colors flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <i class="fas fa-paper-plane mr-2"></i>
                {{ isLoading ? 'Mengirim...' : 'Kirim Notifikasi' }}
              </button>
            </div>
          </form>

          <!-- Riwayat Notifikasi -->
          <div class="bg-gray-50 p-4 rounded-xl">
            <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
              <i class="fas fa-history mr-2 text-gray-500"></i>
              Riwayat Notifikasi
            </h3>
            
            <div v-if="notifications.length === 0" class="text-center py-8">
              <i class="fas fa-bell-slash text-3xl text-gray-300 mb-3"></i>
              <p class="text-gray-500">Belum ada notifikasi terkirim</p>
            </div>

            <div v-else class="space-y-3">
              <div
                v-for="notif in notifications"
                :key="notif.id"
                class="bg-white p-4 rounded-lg border border-gray-200"
              >
                <div class="flex justify-between items-start mb-2">
                  <h4 class="font-medium text-gray-900">{{ notif.title }}</h4>
                  <span
                    :class="[
                      'px-2 py-1 rounded-full text-xs font-medium',
                      notif.is_urgent ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'
                    ]"
                  >
                    {{ notif.user_type }}
                  </span>
                </div>
                <p class="text-sm text-gray-600 mb-2">{{ notif.message }}</p>
                <div class="flex justify-between items-center text-xs text-gray-400">
                  <span>{{ formatDateTime(notif.created_at) }}</span>
                  <span>{{ notif.sent_to }} pengguna</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const { props } = usePage()
const activeLink = ref('/admin/settings')

// State
const currentTab = ref('system')
const isLoading = ref(false)
const settings = ref({
  investment: { value: 0, min: 0, max: 0 },
  profit_percentage: 0,
  commission: { sponsor_fee: 0, profit_sharing: 0 },
  bonus: 0,
  contract_duration: 0,
  grace_period: 0,
  general_notes: ''
})
const notification = ref({
  title: '',
  message: '',
  user_type: 'all',
  is_urgent: false
})
const notifications = ref([])

// Tabs
const tabs = [
  { label: 'Pengaturan Sistem', value: 'system', icon: 'fas fa-cog' },
  { label: 'Notifikasi', value: 'notifications', icon: 'fas fa-bell' }
]

// Helper Functions
const formatDateTime = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date)
}

// Actions
const fetchSettings = async () => {
  try {
    const res = await fetch('/api/admin/settings')
    if (res.ok) {
      const data = await res.json()
      settings.value = data.settings || settings.value
      notifications.value = data.notifications || []
    }
  } catch (error) {
    console.error('Gagal mengambil pengaturan:', error)
  }
}

const saveSettings = async () => {
  isLoading.value = true
  try {
    const res = await fetch('/api/admin/settings', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(settings.value)
    })
    
    if (res.ok) {
      alert('Pengaturan berhasil disimpan!')
    }
  } catch (error) {
    console.error('Gagal menyimpan pengaturan:', error)
    alert('Gagal menyimpan pengaturan')
  } finally {
    isLoading.value = false
  }
}

const sendNotification = async () => {
  isLoading.value = true
  try {
    const res = await fetch('/api/admin/notifications', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(notification.value)
    })
    
    if (res.ok) {
      alert('Notifikasi berhasil dikirim!')
      notification.value = { title: '', message: '', user_type: 'all', is_urgent: false }
      fetchSettings() // Refresh data
    }
  } catch (error) {
    console.error('Gagal mengirim notifikasi:', error)
    alert('Gagal mengirim notifikasi')
  } finally {
    isLoading.value = false
  }
}

// Lifecycle
onMounted(() => {
  fetchSettings()
})
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>