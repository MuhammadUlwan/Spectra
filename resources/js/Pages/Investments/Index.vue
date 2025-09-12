<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  investments: Array,
  packages: Array,
})

const form = useForm({
  package_id: '',
  proof_transfer: null,
})

// handle file upload
function handleFileChange(e) {
  form.proof_transfer = e.target.files[0]
}

function submitForm() {
  form.post(route('investments.store'), {
    onSuccess: () => {
      form.reset()
    },
  })
}
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Investasi Saya</h1>

    <!-- Form Tambah Investasi -->
    <div class="bg-white shadow rounded p-4 mb-6">
      <h2 class="font-semibold mb-3">Tambah Investasi</h2>

      <form @submit.prevent="submitForm" class="space-y-4">
        <!-- Pilih Paket -->
        <div>
          <label class="block font-medium mb-1">Pilih Paket</label>
          <select v-model="form.package_id" class="border rounded w-full p-2">
            <option value="">-- Pilih Paket --</option>
            <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">
              {{ pkg.name }} - USD {{ pkg.amount }}
            </option>
          </select>
          <div v-if="form.errors.package_id" class="text-red-500 text-sm">
            {{ form.errors.package_id }}
          </div>
        </div>

        <!-- Upload Bukti Transfer -->
        <div>
          <label class="block font-medium mb-1">Upload Bukti Transfer</label>
          <input type="file" @change="handleFileChange" class="w-full" />
          <div v-if="form.errors.proof_transfer" class="text-red-500 text-sm">
            {{ form.errors.proof_transfer }}
          </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
          Simpan
        </button>
      </form>
    </div>

    <!-- Daftar Investasi -->
    <div class="bg-white shadow rounded p-4">
      <h2 class="font-semibold mb-3">Daftar Investasi</h2>

      <table class="w-full border">
        <thead class="bg-gray-100">
          <tr>
            <th class="border p-2">Paket</th>
            <th class="border p-2">Jumlah</th>
            <th class="border p-2">Status</th>
            <th class="border p-2">Tanggal Mulai</th>
            <th class="border p-2">Tanggal Akhir</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="inv in investments" :key="inv.id">
            <td class="border p-2">{{ inv.package.name }}</td>
            <td class="border p-2">USD {{ inv.amount }}</td>
            <td class="border p-2">
              <span v-if="inv.status === 'pending'" class="text-yellow-600">Pending</span>
              <span v-else-if="inv.status === 'active'" class="text-green-600">Active</span>
              <span v-else class="text-gray-600">{{ inv.status }}</span>
            </td>
            <td class="border p-2">{{ inv.start_date ?? '-' }}</td>
            <td class="border p-2">{{ inv.end_date ?? '-' }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
