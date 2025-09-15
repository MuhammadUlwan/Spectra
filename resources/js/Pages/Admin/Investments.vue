<template>
  <AdminLayout
    :user="user"
    :profileUrl="profileUrl"
    :logoutUrl="logoutUrl"
    :sidebarMenu="sidebarMenu"
  >
    <!-- Filter & Judul -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
      <h1 class="text-2xl font-extrabold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
        Daftar Investasi
      </h1>
      <select
        v-model="statusFilter"
        class="px-4 py-2 border rounded-lg w-full md:w-64 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
      >
        <option :value="''">Semua Status</option>
        <option :value="'pending'">Pending</option>
        <option :value="'active'">Aktif</option>
        <option :value="'completed'">Selesai</option>
        <option :value="'cancelled'">Batal</option>
      </select>
    </div>

    <!-- Tabel Investasi -->
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white shadow-md rounded-xl overflow-hidden table-auto">
        <thead class="bg-gray-100">
          <tr class="text-gray-700 uppercase text-sm">
            <th class="px-4 py-3">Investor</th>
            <th class="px-4 py-3">Jumlah</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Periode</th>
            <th class="px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="inv in filteredInvestments"
            :key="inv.id"
            class="hover:bg-gray-50 transition"
          >
            <td class="px-4 py-3">{{ inv.user?.name ?? '-' }}</td>
            <td class="px-4 py-3">{{ currencySymbol }} {{ formatAmount(inv.amount) }}</td>
            <td class="px-4 py-3">
              <span :class="statusClass(inv.status)">{{ statusLabel(inv.status) }}</span>
            </td>
            <td class="px-4 py-3">{{ inv.start_date ?? '-' }} s/d {{ inv.end_date ?? '-' }}</td>
            <td class="px-4 py-3 text-center">
              <button
                @click="openDetailModal(inv)"
                class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition"
              >
                Lihat Detail
              </button>
            </td>
          </tr>
          <tr v-if="filteredInvestments.length === 0">
            <td class="px-4 py-4 text-center text-gray-500" colspan="5">
              Tidak ada data investasi yang tersedia.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Detail Investasi -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 md:p-0"
    >
      <div class="bg-white rounded-xl shadow-lg w-full md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto p-6 relative">
        <h2 class="text-xl font-bold mb-4">Detail Investasi</h2>

        <div class="space-y-2">
          <p><strong>Investor:</strong> {{ selectedInvestment.user?.name ?? '-' }}</p>
          <p><strong>Paket:</strong> {{ selectedInvestment.package?.name ?? '-' }}</p>
          <p><strong>Jumlah:</strong> {{ currencySymbol }} {{ formatAmount(selectedInvestment.amount) }}</p>
          <p><strong>Status:</strong> {{ statusLabel(selectedInvestment.status) }}</p>
          <p><strong>Periode:</strong> {{ selectedInvestment.start_date ?? '-' }} s/d {{ selectedInvestment.end_date ?? '-' }}</p>
          <p><strong>Divalidasi Oleh:</strong> {{ selectedInvestment.validator?.name ?? '-' }}</p>
          <p><strong>Tanggal Validasi:</strong> {{ selectedInvestment.validated_at ?? '-' }}</p>
        </div>

        <div class="mt-4">
          <p class="font-semibold mb-2">Bukti Transfer:</p>
          <div v-if="selectedInvestment.proof_transfer">
            <img
              :src="`/storage/uploads/${selectedInvestment.proof_transfer}`"
              alt="Bukti Transfer"
              class="max-w-full rounded-md border"
            />
          </div>
          <p v-else class="text-gray-500">Belum ada bukti transfer</p>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row justify-end gap-2">
          <button
            v-if="selectedInvestment.status === 'pending'"
            @click="updateStatus(selectedInvestment.id, 'active')"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
          >
            Approve
          </button>
          <button
            v-if="selectedInvestment.status === 'pending'"
            @click="updateStatus(selectedInvestment.id, 'cancelled')"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
          >
            Reject
          </button>
          <button
            @click="closeModal"
            class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const { props } = usePage()
const user = props.auth?.user || {}
const profileUrl = props.profileUrl || "/profile"
const logoutUrl = props.logoutUrl || "/logout"
const currencySymbol = props.currencySymbol ?? 'USDT'
const investments = ref(props.investments ?? [])
const statusFilter = ref('')

// Filtered list
const filteredInvestments = computed(() =>
  investments.value.filter(inv => !statusFilter.value || inv.status === statusFilter.value)
)

// Modal
const showModal = ref(false)
const selectedInvestment = ref({})
function openDetailModal(inv) { selectedInvestment.value = inv; showModal.value = true }
function closeModal() { selectedInvestment.value = {}; showModal.value = false }

// Helpers
function formatAmount(amount) { return amount?.toLocaleString() ?? '0' }
function statusLabel(status) {
  const labels = { active: 'Aktif', pending: 'Pending', completed: 'Selesai', cancelled: 'Batal' }
  return labels[status] ?? status ?? '-'
}
function statusClass(status) {
  const classes = {
    active: 'text-green-600 font-semibold',
    pending: 'text-yellow-600 font-semibold',
    completed: 'text-gray-600 font-semibold',
    cancelled: 'text-red-600 font-semibold'
  }
  return classes[status] ?? ''
}

// Update status
function updateStatus(id, newStatus) {
  router.post(`/admin/investments/${id}/update-status`, { status: newStatus }, {
    onSuccess: () => {
      closeModal()
      router.visit('/admin/investments', { preserveState: false })
    },
    onError: () => alert('Gagal memperbarui status')
  })
}

// Flash message toast
onMounted(() => {
  const flash = props.flash?.success
  if (flash) {
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: flash,
      timer: 2000,
      showConfirmButton: true,
    })
  }
})

// Sidebar menu
const sidebarMenu = [
  { label: "Dashboard", url: "/admin/dashboard", icon: "fas fa-home" },
  { label: "Investasi", url: "/admin/investments", icon: "fas fa-chart-line" },
  { label: "Paket Deposit", url: "/admin/deposit-packages", icon: "fas fa-wallet" },
  { label: "Pengguna", url: "/admin/users", icon: "fas fa-users" },
  { label: "Withdraw", url: "/admin/withdraws", icon: "fas fa-money-bill-wave" },
  { label: "Referrals", url: "/admin/referrals", icon: "fas fa-user-friends" }, // <-- baru
  { label: "Pengaturan", url: "/admin/settings", icon: "fas fa-cog" },
]

</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
.table-auto td, .table-auto th { text-align: center; }
main::-webkit-scrollbar { width: 6px; }
main::-webkit-scrollbar-thumb { background-color: rgba(100,100,100,0.3); border-radius: 3px; }
main::-webkit-scrollbar-thumb:hover { background-color: rgba(100,100,100,0.5); }
</style>
