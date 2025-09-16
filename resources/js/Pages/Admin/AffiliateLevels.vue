<template>
  <AdminLayout
    :user="user"
    :profileUrl="profileUrl"
    :logoutUrl="logoutUrl"
    :sidebarMenu="sidebarMenu"
    pageTitle="Affiliate Levels"
  >
    <div class="max-w-6xl mx-auto p-6" :class="formThemeClass">

      <!-- Back Button -->
      <div class="mb-4">
        <button @click="goBack" class="flex items-center gap-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded">
          <i class="fas fa-arrow-left"></i> Back
        </button>
      </div>

      <h1 class="text-3xl font-bold mb-6" :class="textColorClass">Affiliate Levels</h1>

      <!-- Table -->
      <div :class="['overflow-x-auto shadow rounded-lg mb-6', tableBgClass]">
        <table class="min-w-full divide-y" :class="tableDivideClass">
          <thead>
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase" :class="tableTextClass">Level</th>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase" :class="tableTextClass">Type</th>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase" :class="tableTextClass">Percent (%)</th>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase" :class="tableTextClass">Description</th>
              <th class="px-6 py-3 text-right text-xs font-medium uppercase" :class="tableTextClass">Actions</th>
            </tr>
          </thead>
          <tbody :class="tableBodyClass">
            <tr v-if="levels.length === 0">
              <td :colspan="5" class="px-6 py-4 text-center" :class="textColorClass">No data available</td>
            </tr>
            <tr v-for="level in levels" :key="level.id" :class="{'bg-yellow-100 dark:bg-yellow-900': form.id === level.id}">
              <td class="px-6 py-4">{{ level.level }}</td>
              <td class="px-6 py-4">{{ level.type }}</td>
              <td class="px-6 py-4">{{ formatPercent(level.percent) }}</td>
              <td class="px-6 py-4">{{ level.description }}</td>
              <td class="px-6 py-4 text-right">
                <button @click="editLevel(level)" class="text-blue-500 hover:underline mr-2">Edit</button>
                <button @click="deleteLevel(level.id)" class="text-red-500 hover:underline">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Add / Edit Form -->
      <div :class="['p-6 rounded-lg shadow', formBgClass]">
        <h2 class="text-xl font-semibold mb-4" :class="textColorClass">{{ form.id ? `Edit Level: ${form.level}` : 'Add New Level' }}</h2>
        <form @submit.prevent="submitForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1" :class="textColorClass">Level</label>
            <input type="number" v-model="form.level" :class="inputClass" placeholder="e.g. 1"/>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="textColorClass">Type</label>
            <select v-model="form.type" :class="inputClass">
              <option v-for="t in types" :key="t" :value="t">{{ t }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="textColorClass">Percent (%)</label>
            <input type="text" v-model="formattedPercent" :class="inputClass" placeholder="e.g. 1,25"/>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="textColorClass">Description</label>
            <input type="text" v-model="form.description" :class="inputClass" placeholder="Description"/>
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

// Theme Classes
const theme = pageProps.userPref?.theme || 'light'
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

// Sidebar menu
const sidebarMenu = [
  { label: "Dashboard", url: "/admin/dashboard", icon: "fas fa-home", active: url.startsWith('/admin/dashboard') },
  { label: "Investasi", url: "/admin/investments", icon: "fas fa-chart-line", active: url.startsWith('/admin/investments') },
  { label: "Pengguna", url: "/admin/users", icon: "fas fa-users", active: url.startsWith('/admin/users') },
  { label: "Withdraw", url: "/admin/withdraws", icon: "fas fa-money-bill-wave", active: url.startsWith('/admin/withdraws') },
  { label: "Pengaturan", url: "/admin/settings", icon: "fas fa-cog", active: url.startsWith('/admin/settings') },
]

// Data
const levels = ref([])
const types = ref(['sponsor_fee', 'profit_sharing'])
const form = ref({ id: null, level: '', type: '', percent: 0, description: '' })
const loading = ref(false)

// Formatting percent
function formatPercent(value) {
  if (value === null || value === undefined) return ''
  const number = parseFloat(value)
  return number % 1 === 0 ? number.toString() : number.toFixed(2).replace(/\.?0+$/,'').replace('.', ',')
}
function parsePercent(value) {
  if (!value) return 0
  return parseFloat(value.toString().replace(',', '.')) || 0
}

// Computed v-model untuk input persen
const formattedPercent = computed({
  get: () => formatPercent(form.value.percent),
  set: val => form.value.percent = parsePercent(val)
})

// CRUD
function fetchLevels() {
  axios.get('/admin/affiliate-levels/json').then(res => {
    levels.value = Array.isArray(res.data) ? res.data : []
  }).catch(() => levels.value = [])
}

function submitForm() {
  loading.value = true
  const method = form.value.id ? 'put' : 'post'
  const url = form.value.id ? `/admin/affiliate-levels/${form.value.id}` : '/admin/affiliate-levels'
  axios[method](url, form.value)
    .then(() => { fetchLevels(); resetForm() })
    .catch(err => { console.error(err); alert(err.response?.data?.message || 'Something went wrong') })
    .finally(() => loading.value = false)
}

function editLevel(level) {
  form.value = { ...level }
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

function deleteLevel(id) {
  if (!confirm('Are you sure?')) return
  loading.value = true
  axios.delete(`/admin/affiliate-levels/${id}`)
    .then(() => fetchLevels())
    .finally(() => loading.value = false)
}

function resetForm() {
  form.value = { id: null, level: '', type: '', percent: 0, description: '' }
}

// Tombol kembali
function goBack() {
  router.get('/admin/settings')
}

// Mounted
onMounted(() => fetchLevels())
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");
</style>
