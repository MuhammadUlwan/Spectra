<template>
  <InvestorLayout :user="user" pageTitle="Karir & Referral">
    <div class="max-w-4xl mx-auto py-6 space-y-6">
      <h1 class="text-3xl font-bold">Karir & Referral</h1>

      <!-- Kode referral & Link -->
      <div class="bg-white shadow rounded-lg p-6 flex flex-col md:flex-row md:items-center md:space-x-6">
        <div class="flex-1">
          <h2 class="text-xl font-semibold mb-2">Kode Referral Saya</h2>
          <div class="flex items-center space-x-2">
            <input type="text" :value="referralCode" readonly class="border px-3 py-2 rounded w-full" />
            <button @click="copyCode" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
              Salin
            </button>
          </div>
          <p class="mt-2 text-gray-600">
            Link Referral: 
            <a :href="referralLink" class="text-blue-600 underline" target="_blank">{{ referralLink }}</a>
          </p>
        </div>
        <div class="mt-4 md:mt-0">
          <qrcode-vue :value="referralLink" :size="150" />
        </div>
      </div>

      <!-- Statistik referral -->
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Statistik Referral</h2>
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b">
              <th class="px-4 py-2">Level</th>
              <th class="px-4 py-2">Jumlah Orang</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-b hover:bg-gray-50">
              <td class="px-4 py-2">Level 1</td>
              <td class="px-4 py-2">
                {{ referralSummary['1']?.count || 0 }}
                <button 
                  v-if="referralSummary['1']?.users?.length > 0" 
                  @click="showLevelUsers(1)" 
                  class="ml-2 bg-gray-200 text-gray-700 px-2 py-1 rounded text-sm">
                  Lihat User
                </button>
              </td>
            </tr>
            <tr class="border-b hover:bg-gray-50">
              <td class="px-4 py-2">Level 2</td>
              <td class="px-4 py-2">
                {{ referralSummary['2']?.count || 0 }}
                <button 
                  v-if="referralSummary['2']?.users?.length > 0" 
                  @click="showLevelUsers(2)" 
                  class="ml-2 bg-gray-200 text-gray-700 px-2 py-1 rounded text-sm">
                  Lihat User
                </button>
              </td>
            </tr>
            <tr class="border-b hover:bg-gray-50">
              <td class="px-4 py-2">Level 3</td>
              <td class="px-4 py-2">
                {{ referralSummary['3']?.count || 0 }}
                <button 
                  v-if="referralSummary['3']?.users?.length > 0" 
                  @click="showLevelUsers(3)" 
                  class="ml-2 bg-gray-200 text-gray-700 px-2 py-1 rounded text-sm">
                  Lihat User
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Tree referral -->
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Tree Referral</h2>
        <ReferralTree :nodes="referralTree" />
        <p v-if="referralTree.length === 0" class="text-gray-500">Belum ada referral.</p>
      </div>
    </div>

    <!-- Modal Per Level -->
    <div v-for="level in [1,2,3]" :key="level">
      <div v-if="showModal[level]" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
          <h3 class="text-lg font-semibold mb-4">Daftar User - Level {{ level }}</h3>
          <button @click="closeModal(level)" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
          
          <div class="overflow-y-auto max-h-80">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="border-b">
                  <th class="px-4 py-2">ID</th>
                  <th class="px-4 py-2">Nama</th>
                  <th class="px-4 py-2">Email</th>
                  <th class="px-4 py-2">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in modalUsers[level]" :key="user.id" class="border-b hover:bg-gray-50">
                  <td class="px-4 py-2">{{ user.id }}</td>
                  <td class="px-4 py-2">{{ user.name }}</td>
                  <td class="px-4 py-2">{{ user.email }}</td>
                  <td class="px-4 py-2">{{ user.is_active ? 'Active' : 'Inactive' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </InvestorLayout>
</template>

<script setup>
import { ref, onMounted, computed, defineComponent, h } from 'vue'
import { usePage } from '@inertiajs/vue3'
import InvestorLayout from '@/Layouts/InvestorLayout.vue'
import axios from 'axios'
import QrcodeVue from 'qrcode.vue'

const { props: pageProps } = usePage()
const user = pageProps.auth?.user || {}
const referralCode = ref(pageProps.referralCode || '')
const referralSummary = ref({ '1': { users: [] }, '2': { users: [] }, '3': { users: [] } })
const referralTree = ref([])

const referralLink = computed(() => `${window.location.origin}/register?ref=${referralCode.value}`)

// Modal state per level
const showModal = ref({1:false, 2:false, 3:false})
const modalUsers = ref({1:[], 2:[], 3:[]})

const copyCode = () => {
  navigator.clipboard.writeText(referralCode.value)
  alert('Kode referral disalin!')
}

const showLevelUsers = (level) => {
  modalUsers.value[level] = referralSummary.value[level]?.users || []
  showModal.value[level] = true
}

const closeModal = (level) => {
  showModal.value[level] = false
}

// Load data dari API
onMounted(async () => {
  try {
    const summaryRes = await axios.get('/api/referrals-summary', { withCredentials: true })
    referralSummary.value = summaryRes.data || {}

    const treeRes = await axios.get('/api/referrals-tree', { withCredentials: true })
    referralTree.value = treeRes.data || []
  } catch (err) {
    console.error(err)
  }
})

// Komponen rekursif tree
const ReferralTree = defineComponent({
  props: { nodes: { type: Array, default: () => [] } },
  setup(props) {
    return () =>
      h('ul', { class: 'list-disc ml-4 space-y-1' },
        props.nodes.map(node =>
          h('li', { key: node.id }, [
            `${node.name} (Level ${node.level})`,
            node.children?.length ? h(ReferralTree, { nodes: node.children }) : null
          ])
        )
      )
  }
})
</script>

<style scoped>
table th, table td { border: 1px solid #e5e7eb; }
</style>
