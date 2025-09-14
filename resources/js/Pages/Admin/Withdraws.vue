<template>
  <AdminLayout
    :user="user"
    :profileUrl="profileUrl"
    :logoutUrl="logoutUrl"
    :sidebarMenu="sidebarMenu"
    pageTitle="Withdraws"
  >
    <!-- Filter -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
      <h1 class="text-2xl font-bold text-gray-800">Daftar Withdraw</h1>
      <div class="flex gap-2 flex-wrap">
        <select v-model="typeFilter" class="border px-4 py-2 rounded">
          <option value="">Semua Tipe</option>
          <option value="profit">Profit</option>
          <option value="commission">Commission</option>
          <option value="capital">Capital</option>
        </select>
        <select v-model="statusFilter" class="border px-4 py-2 rounded">
          <option value="">Semua Status</option>
          <option value="pending">Pending</option>
          <option value="processing">Processing</option>
          <option value="completed">Completed</option>
          <option value="rejected">Rejected</option>
        </select>
        <input type="date" v-model="dateFrom" class="border px-4 py-2 rounded" />
        <input type="date" v-model="dateTo" class="border px-4 py-2 rounded" />
        <button @click="exportData" class="bg-green-600 text-white px-4 py-2 rounded">Export</button>
      </div>
    </div>

    <!-- Tabel Withdraw -->
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white shadow-md rounded-xl overflow-hidden table-fixed">
        <thead class="bg-gray-100">
          <tr>
            <th>User</th><th>Amount</th><th>Type</th><th>Status</th><th>Processed At</th><th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="wd in filteredWithdraws" :key="wd.id">
            <td>{{ wd.user_name }}</td>
            <td>{{ wd.amount }}</td>
            <td>{{ wd.type }}</td>
            <td>
              <span :class="{
                'text-green-600 font-semibold': wd.status==='completed',
                'text-yellow-600 font-semibold': wd.status==='processing',
                'text-red-600 font-semibold': wd.status==='pending',
                'text-gray-600 font-semibold': wd.status==='rejected'
              }">{{ wd.status }}</span>
            </td>
            <td>{{ wd.processed_at ?? '-' }}</td>
            <td class="px-4 py-2 border text-center">
              <button 
                v-if="wd.status!=='completed' && !(wd.type==='capital' && wd.status==='rejected')"
                @click="openProcessModal(wd)" 
                :disabled="wd.type!=='capital' && !wd.is_due"
                class="px-4 py-2 rounded text-white 
                       bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition">
                Proses
              </button>
              <button 
                v-else 
                @click="openDetailModal(wd)" 
                class="px-4 py-2 rounded text-white 
                       bg-gray-600 hover:bg-gray-700 transition">
                Detail
              </button>
            </td>
          </tr>
          <tr v-if="filteredWithdraws.length===0">
            <td colspan="6" class="text-center py-4">Tidak ada withdraw</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Process -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/3 p-6 relative">
        <h2 class="text-xl font-bold mb-4">Proses Withdraw</h2>

        <p><strong>User:</strong> {{ selectedWithdraw.user_name }}</p>
        <p><strong>Amount:</strong> {{ selectedWithdraw.amount }}</p>

        <p v-if="selectedWithdraw.method==='wallet'"><strong>Wallet:</strong> {{ selectedWithdraw.user_wallet || '-' }}</p>

        <div class="mt-4">
          <label>Method</label>
          <select v-model="selectedWithdraw.method" class="border px-3 py-2 rounded w-full">
            <option value="wallet">Wallet</option>
            <option value="bank">Bank</option>
          </select>
        </div>

        <div v-if="selectedWithdraw.method==='bank'" class="mt-4">
          <label>Bank Name</label>
          <input type="text" v-model="selectedWithdraw.bank_name" class="border px-3 py-2 rounded w-full" readonly/>
          <label>Bank Account</label>
          <input type="text" v-model="selectedWithdraw.bank_account" class="border px-3 py-2 rounded w-full" readonly/>
        </div>

        <div class="mt-4">
          <label>Upload Bukti Transfer</label>
          <input type="file" @change="handleFileUpload"/>
        </div>

        <div class="mt-6 flex justify-end gap-2">
          <button @click="submitProcess" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Submit</button>
          <button @click="closeModal" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Tutup</button>
        </div>
      </div>
    </div>

    <!-- Modal Detail -->
    <div v-if="showDetailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/3 p-6 relative">
        <h2 class="text-xl font-bold mb-4">Detail Withdraw</h2>
        <p><strong>User:</strong> {{ selectedWithdraw.user_name }}</p>
        <p><strong>Amount:</strong> {{ selectedWithdraw.amount }}</p>
        <p><strong>Status:</strong> {{ selectedWithdraw.status }}</p>
        <p><strong>Method:</strong> {{ selectedWithdraw.method==='wallet' ? 'Wallet' : 'Bank' }}</p>

        <p v-if="selectedWithdraw.method==='wallet'"><strong>Wallet:</strong> {{ selectedWithdraw.user_wallet || '-' }}</p>
        <p v-if="selectedWithdraw.method==='bank'"><strong>Bank Name:</strong> {{ selectedWithdraw.bank_name || '-' }}</p>
        <p v-if="selectedWithdraw.method==='bank'"><strong>Bank Account:</strong> {{ selectedWithdraw.bank_account || '-' }}</p>

        <p><strong>Proof Transfer:</strong> {{ selectedWithdraw.proof_transfer || '-' }}</p>
        <p><strong>Processed At:</strong> {{ selectedWithdraw.processed_at || '-' }}</p>

        <div class="mt-6 flex justify-end">
          <button @click="closeDetailModal" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Tutup</button>
        </div>
      </div>
    </div>

  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const { props } = usePage()
const user = props.auth.user || {}
const logoutUrl = props.logoutUrl || '/logout'
const profileUrl = props.profileUrl || '/profile'

const withdraws = ref(props.withdraws ?? [])
const typeFilter = ref('')
const statusFilter = ref('')
const dateFrom = ref('')
const dateTo = ref('')

const filteredWithdraws = computed(() => {
  return withdraws.value.filter(wd => {
    const typeOk = !typeFilter.value || wd.type === typeFilter.value
    const statusOk = !statusFilter.value || wd.status === statusFilter.value
    const dateOk = (!dateFrom.value || new Date(wd.processed_at ?? wd.created_at) >= new Date(dateFrom.value))
                && (!dateTo.value || new Date(wd.processed_at ?? wd.created_at) <= new Date(dateTo.value))
    return typeOk && statusOk && dateOk
  })
})

const showModal = ref(false)
const showDetailModal = ref(false)
const selectedWithdraw = ref({})
const selectedFile = ref(null)

function openProcessModal(wd) {
  selectedWithdraw.value = { ...wd, method: wd.method ?? 'wallet', bank_name: wd.bank_name || '', bank_account: wd.bank_account || '' }
  showModal.value = true
}
function closeModal() { selectedWithdraw.value={}; selectedFile.value=null; showModal.value=false }
function openDetailModal(wd) { selectedWithdraw.value={...wd}; showDetailModal.value=true }
function closeDetailModal() { selectedWithdraw.value={}; showDetailModal.value=false }
function handleFileUpload(e) { selectedFile.value = e.target.files[0] }

function submitProcess() {
  const formData = new FormData()
  formData.append('status','completed')
  formData.append('method',selectedWithdraw.value.method)
  if(selectedWithdraw.value.method==='bank'){
    formData.append('bank_name',selectedWithdraw.value.bank_name||'')
    formData.append('bank_account',selectedWithdraw.value.bank_account||'')
  }
  if(selectedFile.value) formData.append('proof_transfer',selectedFile.value)

  router.post(`/admin/withdraws/${selectedWithdraw.value.id}/process`, formData, {
    preserveScroll: true,
    preserveState: false, // pastikan state reload
    onSuccess: () => {
        closeModal()
        // reload data terbaru dari server
        router.reload({ only: ['withdraws'] })
    },
  })
}

function exportData() {
  const params = new URLSearchParams()
  if(typeFilter.value) params.append('type',typeFilter.value)
  if(statusFilter.value) params.append('status',statusFilter.value)
  if(dateFrom.value) params.append('date_from',dateFrom.value)
  if(dateTo.value) params.append('date_to',dateTo.value)
  window.location.href = `/admin/withdraws/export?${params.toString()}`
}

const sidebarMenu = [
  { label:"Dashboard", url:"/admin/dashboard", icon:"fas fa-home" },
  { label:"Investasi", url:"/admin/investments", icon:"fas fa-chart-line" },
  { label:"Pengguna", url:"/admin/users", icon:"fas fa-users" },
  { label:"Withdraw", url:"/admin/withdraws", icon:"fas fa-money-bill-wave", active:true },
  { label:"Pengaturan", url:"/admin/settings", icon:"fas fa-cog" },
]
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
.table-fixed td, .table-fixed th { text-align: center; word-wrap: break-word; }
.table-fixed th:nth-child(1), .table-fixed td:nth-child(1) { width: 20%; }
.table-fixed th:nth-child(2), .table-fixed td:nth-child(2) { width: 15%; }
.table-fixed th:nth-child(3), .table-fixed td:nth-child(3) { width: 15%; }
.table-fixed th:nth-child(4), .table-fixed td:nth-child(4) { width: 10%; }
.table-fixed th:nth-child(5), .table-fixed td:nth-child(5) { width: 15%; }
.table-fixed th:nth-child(6), .table-fixed td:nth-child(6) { width: 25%; }
</style>
