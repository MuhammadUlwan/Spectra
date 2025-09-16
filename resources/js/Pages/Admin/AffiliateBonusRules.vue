<template>
  <AdminLayout
    :user="user"
    :profileUrl="profileUrl"
    :logoutUrl="logoutUrl"
    :sidebarMenu="sidebarMenu"
    pageTitle="Affiliate Bonus Rules"
  >
    <div class="max-w-6xl mx-auto p-6" :class="formThemeClass">

      <!-- Back Button -->
      <div class="mb-4">
        <button @click="goBack" class="flex items-center gap-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded">
          <i class="fas fa-arrow-left"></i> Back
        </button>
      </div>

      <h1 class="text-3xl font-bold mb-6" :class="textColorClass">Affiliate Bonus Rules</h1>

      <!-- Table -->
      <div :class="['overflow-x-auto shadow rounded-lg mb-6', tableBgClass]">
        <table class="min-w-full divide-y" :class="tableDivideClass">
          <thead>
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase" :class="tableTextClass">Role</th>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase" :class="tableTextClass">Target Omset</th>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase" :class="tableTextClass">Extra Persen</th>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase" :class="tableTextClass">Minimal Deposit</th>
              <th class="px-6 py-3 text-right text-xs font-medium uppercase" :class="tableTextClass">Aksi</th>
            </tr>
          </thead>
          <tbody :class="tableBodyClass">
            <tr v-if="rules.length === 0">
              <td :colspan="5" class="px-6 py-4 text-center" :class="textColorClass">No data available</td>
            </tr>
            <tr v-for="rule in rules" :key="rule.id" :class="{'bg-yellow-100 dark:bg-yellow-900': form.id === rule.id}">
              <td class="px-6 py-4 capitalize">Affiliate / Consultant</td>
              <td class="px-6 py-4">{{ formatNumber(rule.target_omset) }}</td>
              <td class="px-6 py-4">{{ formatPercent(rule.extra_percent) }}</td>
              <td class="px-6 py-4">{{ formatNumber(rule.required_min_deposit) }}</td>
              <td class="px-6 py-4 text-right">
                <button @click="editRule(rule)" class="text-blue-500 hover:underline mr-2">Edit</button>
                <button @click="deleteRule(rule.id)" class="text-red-500 hover:underline">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Add / Edit Form -->
      <div :class="['p-6 rounded-lg shadow', formBgClass]">
        <h2 class="text-xl font-semibold mb-4" :class="textColorClass">
          {{ form.id ? `Edit Rule: ${formatNumber(form.target_omset)}` : 'Add New Rule' }}
        </h2>
        <form @submit.prevent="submitForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1" :class="textColorClass">Role</label>
            <input type="text" value="Affiliate / Consultant" disabled :class="inputClass" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="textColorClass">Target Omset</label>
            <input
              type="text"
              v-model="formattedTargetOmset"
              :class="inputClass"
              placeholder="e.g. 10000"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="textColorClass">Extra Persen</label>
            <input
              type="text"
              v-model="formattedExtraPercent"
              :class="inputClass"
              placeholder="e.g. 1,25"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="textColorClass">Minimal Deposit</label>
            <input
              type="text"
              v-model="formattedMinDeposit"
              :class="inputClass"
              placeholder="e.g. 1000"
            />
          </div>
          <div class="md:col-span-2 text-right flex justify-end gap-2">
            <button type="button" v-if="form.id" @click="resetForm" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
              Cancel
            </button>
            <button type="submit" :disabled="loading" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
              {{ loading ? (form.id ? 'Updating...' : 'Adding...') : (form.id ? 'Update' : 'Add') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import axios from 'axios'

const { props: pageProps, url } = usePage()
const user = pageProps.auth?.user || { name: 'Admin' }
const profileUrl = pageProps?.profileUrl || '/profile'
const logoutUrl = pageProps?.logoutUrl || '/logout'

// Theme
const theme = pageProps.userPref?.theme || 'light'

// Sidebar menu
const sidebarMenu = [
  { label: "Dashboard", url: "/admin/dashboard", icon: "fas fa-home", active: url.startsWith('/admin/dashboard') },
  { label: "Investasi", url: "/admin/investments", icon: "fas fa-chart-line", active: url.startsWith('/admin/investments') },
  { label: "Pengguna", url: "/admin/users", icon: "fas fa-users", active: url.startsWith('/admin/users') },
  { label: "Withdraw", url: "/admin/withdraws", icon: "fas fa-money-bill-wave", active: url.startsWith('/admin/withdraws') },
  { label: "Affiliate Bonus", url: "/admin/affiliate-bonus-rules", icon: "fas fa-gift", active: url.startsWith('/admin/affiliate-bonus-rules') },
  { label: "Pengaturan", url: "/admin/settings", icon: "fas fa-cog", active: url.startsWith('/admin/settings') },
]

// Form & State
const rules = ref([])
const loading = ref(false)
const form = ref({
  id: null,
  target_omset: null,
  extra_percent: null,
  required_min_deposit: null,
})

// ---------- Formatting ----------
function formatNumber(value) {
  if (value === null || value === undefined) return ''
  const number = parseFloat(value)
  return number.toLocaleString('id-ID', { maximumFractionDigits: 0 })
}
function parseNumber(value) {
  if (!value) return 0
  return parseFloat(value.toString().replace(/\./g, '').replace(',', '.')) || 0
}
function formatPercent(value) {
  if (value === null || value === undefined) return ''
  const number = parseFloat(value)
  return number % 1 === 0 ? number.toString() : number.toFixed(2).replace(/\.?0+$/,'').replace('.', ',')
}
function parsePercent(value) {
  if (!value) return 0
  return parseFloat(value.toString().replace(',', '.')) || 0
}

// Computed for input
const formattedTargetOmset = computed({
  get: () => formatNumber(form.value.target_omset),
  set: val => form.value.target_omset = parseNumber(val)
})
const formattedExtraPercent = computed({
  get: () => formatPercent(form.value.extra_percent),
  set: val => form.value.extra_percent = parsePercent(val)
})
const formattedMinDeposit = computed({
  get: () => formatNumber(form.value.required_min_deposit),
  set: val => form.value.required_min_deposit = parseNumber(val)
})

// Theme Classes
const formThemeClass = computed(() => theme === 'light' ? 'bg-white text-gray-900' : 'bg-gray-900 text-white')
const formBgClass = computed(() => theme === 'light' ? 'bg-gray-50' : 'bg-gray-800/50')
const tableBgClass = computed(() => theme === 'light' ? 'bg-white' : 'bg-gray-800/50')
const tableDivideClass = computed(() => theme === 'light' ? 'divide-gray-200' : 'divide-gray-700')
const tableBodyClass = computed(() => theme === 'light' ? 'bg-white divide-y divide-gray-200' : 'bg-gray-900 divide-y divide-gray-700')
const textColorClass = computed(() => theme === 'light' ? 'text-gray-900' : 'text-white')
const inputClass = computed(() => theme === 'light'
  ? 'border border-gray-300 px-3 py-2 rounded-md w-full bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500'
  : 'border border-gray-600 px-3 py-2 rounded-md w-full bg-gray-700 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500'
)

// ---------- CRUD ----------
function fetchRules() {
  axios.get('/admin/affiliate-bonus-rules/json')
    .then(res => rules.value = Array.isArray(res.data) ? res.data : [])
    .catch(() => rules.value = [])
}

function submitForm() {
  loading.value = true
  const payload = {
    target_omset: form.value.target_omset,
    extra_percent: form.value.extra_percent,
    required_min_deposit: form.value.required_min_deposit,
  }
  const method = form.value.id ? 'put' : 'post'
  const url = form.value.id ? `/admin/affiliate-bonus-rules/${form.value.id}` : '/admin/affiliate-bonus-rules'
  axios[method](url, payload)
    .then(() => { fetchRules(); resetForm() })
    .catch(err => { console.error(err); alert(err.response?.data?.message || 'Something went wrong') })
    .finally(() => loading.value = false)
}

function editRule(rule) {
  form.value = { ...rule }
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

function deleteRule(id) {
  if(confirm('Are you sure you want to delete this rule?')) {
    loading.value = true
    axios.delete(`/admin/affiliate-bonus-rules/${id}`)
      .then(() => fetchRules())
      .catch(err => { console.error(err); alert(err.response?.data?.message || 'Failed to delete') })
      .finally(() => loading.value = false)
  }
}

function resetForm() {
  form.value = { id: null, target_omset: null, extra_percent: null, required_min_deposit: null }
}

// Tombol kembali
function goBack() {
  router.get('/admin/settings')
}

// Fetch on mount
onMounted(() => fetchRules())
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");
</style>
