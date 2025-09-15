<template>
  <AdminLayout
    :user="user"
    :profileUrl="profileUrl"
    :logoutUrl="logoutUrl"
    :sidebarMenu="sidebarMenu"
    pageTitle="Investasi"
  >
    <!-- Filter & Judul -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
      <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
        Daftar Investasi
      </h1>
      <select v-model="statusFilter" class="px-4 py-2 border rounded-lg w-full md:w-auto">
        <option value="">Semua Status</option>
        <option value="pending">Pending</option>
        <option value="active">Aktif</option>
        <option value="completed">Selesai</option>
        <option value="cancelled">Batal</option>
      </select>
    </div>

    <!-- Tabel Investasi -->
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white shadow-md rounded-xl overflow-hidden table-fixed">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2 border w-1/5">Investor</th>
            <th class="px-4 py-2 border w-1/5">Jumlah</th>
            <th class="px-4 py-2 border w-1/5">Status</th>
            <th class="px-4 py-2 border w-1/5">Tanggal</th>
            <th class="px-4 py-2 border w-1/5">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="inv in filteredInvestments" :key="inv.id">
            <td class="px-4 py-2 border">{{ inv.user?.name ?? '-' }}</td>
            <td class="px-4 py-2 border">{{ currencySymbol }} {{ formatAmount(inv.amount) }}</td>
            <td class="px-4 py-2 border">
              <span :class="statusClass(inv.status)">{{ statusLabel(inv.status) }}</span>
            </td>
            <td class="px-4 py-2 border">
              {{ inv.start_date ? inv.start_date : '-' }} s/d {{ inv.end_date ? inv.end_date : '-' }}
            </td>
            <td class="px-4 py-2 border text-center">
              <button
                @click="openDetailModal(inv)"
                class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition"
              >
                Lihat Detail
              </button>
            </td>
          </tr>
          <tr v-if="filteredInvestments.length === 0">
            <td class="px-4 py-4 border text-center" colspan="5">
              Tidak ada data investasi yang tersedia.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Detail Investasi -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/2 p-6 relative">
        <h2 class="text-xl font-bold mb-4">Detail Investasi</h2>

        <p><strong>Investor:</strong> {{ selectedInvestment.user?.name ?? '-' }}</p>
        <p><strong>Paket:</strong> {{ selectedInvestment.package?.name ?? '-' }}</p>
        <p><strong>Jumlah:</strong> {{ currencySymbol }} {{ formatAmount(selectedInvestment.amount) }}</p>
        <p><strong>Status:</strong> {{ statusLabel(selectedInvestment.status) }}</p>
        <p><strong>Periode:</strong> {{ selectedInvestment.start_date ?? '-' }} s/d {{ selectedInvestment.end_date ?? '-' }}</p>
        <p><strong>Divalidasi Oleh:</strong> {{ selectedInvestment.validator?.name ?? '-' }}</p>
        <p><strong>Tanggal Validasi:</strong> {{ selectedInvestment.validated_at ?? '-' }}</p>

        <div class="mt-4">
          <p class="font-semibold">Bukti Transfer:</p>
          <div v-if="selectedInvestment.proof_transfer">
            <a
              :href="`/storage/${selectedInvestment.proof_transfer}`"
              target="_blank"
              class="text-blue-600 hover:underline"
            >
              Lihat Bukti Transfer
            </a>
            <img
              v-if="previewBukti"
              :src="`/storage/${selectedInvestment.proof_transfer}`"
              alt="Bukti Transfer"
              class="max-w-full rounded mt-2"
            />
            <button @click="previewBukti = !previewBukti" class="text-sm mt-1 text-gray-600 hover:text-gray-800">
              {{ previewBukti ? 'Sembunyikan' : 'Preview' }}
            </button>
          </div>
          <p v-else class="text-gray-500">Belum ada bukti transfer</p>
        </div>

        <div class="mt-6 flex justify-end gap-2">
          <button
            v-if="selectedInvestment.status === 'pending'"
            @click="updateStatus(selectedInvestment.id, 'active')"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
          >
            Approve
          </button>
          <button
            v-if="selectedInvestment.status === 'pending'"
            @click="updateStatus(selectedInvestment.id, 'cancelled')"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
          >
            Reject
          </button>
          <button @click="closeModal" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
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
const previewBukti = ref(false)

// Filtered list
const filteredInvestments = computed(() => {
  return investments.value.filter(inv => !statusFilter.value || inv.status === statusFilter.value)
})

// Modal
const showModal = ref(false)
const selectedInvestment = ref({})
function openDetailModal(inv) { selectedInvestment.value = inv; showModal.value = true }
function closeModal() { selectedInvestment.value = {}; showModal.value = false }

// Helpers
function formatAmount(amount) { return amount?.toLocaleString() ?? '0' }
function statusLabel(status) {
  switch (status) {
    case 'active': return 'Aktif'
    case 'pending': return 'Pending'
    case 'completed': return 'Selesai'
    case 'cancelled': return 'Batal'
    default: return status ?? '-'
  }
}
function statusClass(status) {
  switch (status) {
    case 'active': return 'text-green-600 font-semibold'
    case 'pending': return 'text-yellow-600 font-semibold'
    case 'completed': return 'text-gray-600 font-semibold'
    case 'cancelled': return 'text-red-600 font-semibold'
    default: return ''
  }
}

// Update status
function updateStatus(id, newStatus) {
  router.post(`/admin/investments/${id}/update-status`, { status: newStatus }, {
    onSuccess: (page) => {
      const idx = investments.value.findIndex(inv => inv.id === id)
      if (idx !== -1) {
        investments.value[idx].status = newStatus
        investments.value[idx].validator = {
          id: page.props.auth.user.id,
          name: page.props.auth.user.name
        }
        investments.value[idx].validated_at = new Date().toISOString().slice(0,19).replace('T', ' ')

        if (newStatus === 'active') {
          const pkg = investments.value[idx].package
          const start = new Date()
          const end = new Date()
          end.setMonth(end.getMonth() + (pkg?.duration_months ?? 1))
          investments.value[idx].start_date = start.toISOString().slice(0,10)
          investments.value[idx].end_date = end.toISOString().slice(0,10)
        } else {
          investments.value[idx].start_date = null
          investments.value[idx].end_date = null
        }
      }
      closeModal()
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Status investasi diperbarui',
        timer: 2000,
        showConfirmButton: true,
      })
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
  { label: "Investasi", url: "/admin/investments", icon: "fas fa-chart-line", active: true },
  { label: "Pengguna", url: "/admin/users", icon: "fas fa-users" },
  { label: "Withdraw", url: "/admin/withdraws", icon: "fas fa-money-bill-wave" },
  { label: "Pengaturan", url: "/admin/settings", icon: "fas fa-cog" },
]
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
a { transition: all 0.2s ease; }
.table-fixed td, .table-fixed th { text-align: center; }
main::-webkit-scrollbar { width: 6px; }
main::-webkit-scrollbar-thumb { background-color: rgba(100,100,100,0.3); border-radius: 3px; }
main::-webkit-scrollbar-thumb:hover { background-color: rgba(100,100,100,0.5); }
</style>
