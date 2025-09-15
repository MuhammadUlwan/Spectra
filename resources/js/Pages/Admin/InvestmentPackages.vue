<template>
  <AdminLayout
    :user="user"
    :profileUrl="profileUrl"
    :logoutUrl="logoutUrl"
    :sidebarMenu="sidebarMenu"
  >
    <div class="max-w-6xl mx-auto p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <h1 class= "text-2xl font-extrabold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">Daftar Paket Deposit</h1>
        <button
          @click="openForm()"
          class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors flex items-center shadow-sm"
        >
          <i class="fas fa-plus mr-2"></i> Tambah Paket
        </button>
      </div>

      <!-- Tabel Paket -->
      <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Paket</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi (Bulan)</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profit (%)</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mata Uang</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(pkg, index) in packages" :key="pkg.id">
              <td class="px-6 py-4 text-sm text-gray-700">{{ index + 1 }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ pkg.name }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ formatCurrency(pkg.amount, pkg.currency) }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ pkg.duration_months }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ pkg.profit_percent }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ pkg.currency }}</td>
              <td class="px-6 py-4 text-sm text-gray-700 text-center flex justify-center gap-2">
                <button @click="openForm(pkg)" class="text-blue-600 hover:text-blue-800">
                  <i class="fas fa-edit"></i>
                </button>
                <button @click="deletePackage(pkg.id)" class="text-red-600 hover:text-red-800">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </td>
            </tr>
            <tr v-if="packages.length === 0">
              <td colspan="7" class="text-center py-4 text-gray-400">Belum ada paket deposit</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal Form Tambah / Edit -->
      <div v-if="showForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">
            {{ editingPackage ? 'Edit Paket' : 'Tambah Paket' }}
          </h2>
          <form @submit.prevent="savePackage">
            <div class="mb-3">
              <label class="block text-xs font-medium text-gray-500 mb-1">Nama Paket</label>
              <input type="text" v-model="form.name" class="w-full p-3 border rounded-md text-sm" required>
            </div>
            <div class="mb-3">
              <label class="block text-xs font-medium text-gray-500 mb-1">Nominal</label>
              <input type="number" v-model.number="form.amount" class="w-full p-3 border rounded-md text-sm" required>
            </div>
            <div class="mb-3">
              <label class="block text-xs font-medium text-gray-500 mb-1">Durasi (Bulan)</label>
              <input type="number" v-model.number="form.duration_months" class="w-full p-3 border rounded-md text-sm" required>
            </div>
            <div class="mb-3">
              <label class="block text-xs font-medium text-gray-500 mb-1">Profit (%)</label>
              <input type="number" v-model.number="form.profit_percent" class="w-full p-3 border rounded-md text-sm" required>
            </div>
            <div class="mb-4">
              <label class="block text-xs font-medium text-gray-500 mb-1">Mata Uang</label>
              <input type="text" v-model="form.currency" class="w-full p-3 border rounded-md text-sm" required>
            </div>
            <div class="flex justify-end gap-2">
              <button type="button" @click="closeForm" class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200">Batal</button>
              <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                {{ editingPackage ? 'Simpan Perubahan' : 'Tambah Paket' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const { props } = usePage()
const user = props.auth.user
const profileUrl = props.profileUrl
const logoutUrl = props.logoutUrl
const packages = ref(props.packages || [])


// Modal
const showForm = ref(false)
const editingPackage = ref(null)
const form = reactive({
  id: null,
  name: '',
  amount: 0,
  duration_months: 6,
  profit_percent: 0,
  currency: 'USD'
})

const openForm = (pkg = null) => {
  if(pkg) {
    editingPackage.value = pkg
    Object.assign(form, pkg)
  } else {
    editingPackage.value = null
    Object.assign(form, { id: null, name: '', amount: 0, duration_months: 6, profit_percent: 0, currency: 'USD' })
  }
  showForm.value = true
}

const closeForm = () => showForm.value = false

const savePackage = () => {
  const payload = {
    name: form.name,
    amount: form.amount,
    duration_months: form.duration_months,
    profit_percent: form.profit_percent,
    currency: form.currency
  }

  if(editingPackage.value) {
    Inertia.post(`/admin/deposit-packages/${form.id}`, payload, {
      onSuccess: (page) => {
        // Ambil paket yang baru diupdate dari response
        const updatedPkg = page.props.updatedPackage || { ...form }
        const index = packages.value.findIndex(p => p.id === form.id)
        if(index !== -1) packages.value[index] = updatedPkg
        closeForm()
        alert('Paket berhasil diperbarui!')
      },
      onError: (errors) => {
        alert('Gagal memperbarui paket!')
        console.log(errors)
      }
    })
  } else {
    Inertia.post('/admin/deposit-packages', payload, {
      onSuccess: (page) => {
        const newPkg = page.props.newPackage || payload
        packages.value.push(newPkg)
        closeForm()
        alert('Paket berhasil ditambahkan!')
      },
      onError: (errors) => {
        alert('Gagal menambahkan paket!')
        console.log(errors)
      }
    })
  }
}

const deletePackage = (id) => {
  if (confirm('Yakin ingin menghapus paket ini?')) {
    Inertia.post(`/admin/deposit-packages/${id}/delete`, {}, {
      preserveState: true,
      onSuccess: () => {
        packages.value = packages.value.filter(p => p.id !== id);
        alert('Paket berhasil dihapus!');
      },
      onError: (errors) => {
        console.log(errors);
        alert('Gagal menghapus paket!');
      }
    });
  }
}

const formatCurrency = (value, currency) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency }).format(value)
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
</style>
