<template>
  <div class="min-h-screen bg-gray-100 p-6">
    <!-- Judul & tombol aksi -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
      <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
        Kelola Pengumuman
      </h1>
      <div class="flex space-x-2">
        <button
          @click="showForm = !showForm"
          class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition flex items-center"
        >
          <i class="fas fa-plus mr-2"></i> Tambah Pengumuman
        </button>
        <a
          href="/admin/dashboard"
          class="bg-gray-500 text-white px-5 py-2 rounded-lg shadow hover:bg-gray-600 transition flex items-center"
        >
          <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
      </div>
    </div>

    <!-- Form Tambah / Edit -->
    <div v-if="showForm" class="bg-white p-6 rounded-xl shadow-md mb-6">
      <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
          <input type="text" v-model="form.title"
            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
          <p v-if="errors.title" class="text-red-500 text-xs mt-1">{{ errors.title }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
          <input type="file" @change="handleFile" accept="image/*"
            class="w-full border rounded-lg px-3 py-2" />
          <p class="text-xs text-gray-500 mt-1">*Disarankan ukuran 1200x400 px (JPG/PNG)</p>
          <p v-if="errors.image" class="text-red-500 text-xs mt-1">{{ errors.image }}</p>
        </div>

        <div class="flex items-center space-x-4">
          <label class="flex items-center">
            <input type="checkbox" v-model="form.active" class="mr-2"> Aktif
          </label>
        </div>

        <button type="submit"
          class="bg-green-600 text-white px-5 py-2 rounded-lg shadow hover:bg-green-700 transition">
          Simpan
        </button>
      </form>
    </div>

    <!-- Daftar Pengumuman -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="a in announcements" :key="a.id" class="relative group rounded-xl overflow-hidden shadow-md">
        <!-- Gambar Full Card -->
        <div class="relative w-full aspect-[3/1]">
          <img :src="a.image_url" :alt="a.title" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
          
          <!-- Overlay judul -->
          <div class="absolute inset-0 bg-black bg-opacity-25 flex items-end p-4">
            <h3 class="text-white text-xl font-semibold">{{ a.title }}</h3>
          </div>

          <!-- Overlay tombol aksi (vertikal) -->
          <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition flex flex-col space-y-2">
            <button @click="toggle(a.id)"
              :class="a.active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600'"
              class="text-white px-4 py-2 rounded-lg text-sm shadow">
              {{ a.active ? 'Nonaktifkan' : 'Aktifkan' }}
            </button>
            <button @click="destroy(a.id)"
              class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm shadow">
              Hapus
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const { props } = usePage()
const announcements = ref(props.announcements || [])
const errors = ref({})
const showForm = ref(false)

const form = ref({
  title: '',
  image: null,
  active: true
})

function handleFile(e) {
  form.value.image = e.target.files[0]
}

function submit() {
  const data = new FormData()
  data.append('title', form.value.title)
  if (form.value.image) data.append('image', form.value.image)
  data.append('active', form.value.active ? 1 : 0)

  router.post('/admin/announcements', data, {
    onError: (err) => {
      errors.value = err
    },
    onSuccess: (page) => {
      announcements.value = page.props.announcements || []
      form.value = { title: '', image: null, active: true }
      showForm.value = false
    }
  })
}

function toggle(id) {
  router.post(`/admin/announcements/${id}/toggle`, {}, {
    onSuccess: (page) => {
      announcements.value = page.props.announcements || []
    }
  })
}

function destroy(id) {
  if (confirm('Yakin ingin menghapus pengumuman ini?')) {
    router.delete(`/admin/announcements/${id}`, {
      onSuccess: () => {
        announcements.value = announcements.value.filter(a => a.id !== id)
      }
    })
  }
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
</style>
