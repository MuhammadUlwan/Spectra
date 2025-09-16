<template>
  <AdminLayout
    :user="user"
    :profileUrl="profileUrl"
    :logoutUrl="logoutUrl"
    :sidebarMenu="sidebarMenu"
    pageTitle="$t('settingsTitle')"
  >
    <div class="max-w-4xl mx-auto">
      <!-- Header section -->
      <div class="mb-8">
        <h1 :class="['text-3xl font-bold', form.theme === 'light' ? 'text-gray-900' : 'text-white']">
          {{ $t('settingsTitle') }}
        </h1>
        <p :class="['mt-2', form.theme === 'light' ? 'text-gray-700' : 'text-gray-400']">
          {{ $t('settingsDescription') }}
        </p>
      </div>

      <!-- Settings card -->
      <form @submit.prevent="submitSettings" :class="[formClass, 'rounded-xl shadow-lg divide-y', form.theme === 'light' ? 'divide-gray-200' : 'divide-gray-700']">

        <!-- General Settings -->
        <div class="p-6">
          <h2 :class="['text-xl font-semibold mb-4 flex items-center', form.theme === 'light' ? 'text-gray-900' : 'text-white']">
            <i class="fas fa-cog mr-2 text-blue-500"></i>
            {{ $t('generalSettings') }}
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label :class="['block text-sm font-medium mb-1', form.theme === 'light' ? 'text-gray-700' : 'text-gray-300']">
                {{ $t('themeLabel') }}
              </label>
              <select v-model="form.theme" :class="inputClass">
                <option value="light">{{ $t('themeLight') }}</option>
                <option value="dark">{{ $t('themeDark') }}</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Affiliate Settings Shortcut -->
        <div :class="['p-6 mt-6 rounded-xl shadow', form.theme === 'light' ? 'bg-gray-50' : 'bg-gray-800/50']">
          <h2 :class="['text-xl font-semibold mb-4 flex items-center', form.theme === 'light' ? 'text-gray-900' : 'text-white']">
            <i class="fas fa-network-wired mr-2 text-indigo-500"></i>
            Affiliate Settings
          </h2>
          <div class="flex flex-wrap gap-4">
            <Link
              href="/admin/affiliate-levels"
              class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md flex items-center justify-center transition cursor-pointer"
            >
              <i class="fas fa-cogs mr-2"></i>
              Manage Affiliate Levels
            </Link>

            <Link
              href="/admin/affiliate-bonus-rules"
              class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md flex items-center justify-center transition cursor-pointer"
            >
              <i class="fas fa-cogs mr-2"></i>
              Manage Bonus Rules
            </Link>
          </div>
        </div>

        <!-- Bank Information -->
        <div class="p-6">
          <h2 :class="['text-xl font-semibold mb-4 flex items-center', form.theme === 'light' ? 'text-gray-900' : 'text-white']">
            <i class="fas fa-university mr-2 text-green-500"></i>
            {{ $t('bankInformation') }}
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label :class="['block text-sm font-medium mb-1', form.theme === 'light' ? 'text-gray-700' : 'text-gray-300']">
                {{ $t('bankName') }}
              </label>
              <input type="text" v-model="form.bank_name" :placeholder="bankNameExample" :class="inputClass" />
            </div>
            <div>
              <label :class="['block text-sm font-medium mb-1', form.theme === 'light' ? 'text-gray-700' : 'text-gray-300']">
                {{ $t('bankNumber') }}
              </label>
              <input type="text" v-model="form.bank_number" :placeholder="bankNumberExample" :class="inputClass" />
            </div>
          </div>

          <!-- Upload QR Code -->
          <div class="mt-4">
            <label :class="['block text-sm font-medium mb-1', form.theme === 'light' ? 'text-gray-700' : 'text-gray-300']">
              {{ $t('bankQrCode') }}
            </label>
            <input type="file" @change="onFileChange" accept="image/*" :class="inputClass" />

            <!-- Preview -->
            <div v-if="form.previewQr" class="mt-3">
              <p class="text-sm mb-1">Preview:</p>
              <img
                :src="form.previewQr"
                alt="QR Code"
                class="w-32 h-32 object-contain border rounded-md bg-white p-2"
              />
            </div>
          </div>
        </div>

        <!-- URL Settings -->
        <div class="p-6">
          <h2 :class="['text-xl font-semibold mb-4 flex items-center', form.theme === 'light' ? 'text-gray-900' : 'text-white']">
            <i class="fas fa-link mr-2 text-purple-500"></i>
            {{ $t('urlSettings') }}
          </h2>
          <div class="space-y-4">
            <div>
              <label :class="['block text-sm font-medium mb-1', form.theme === 'light' ? 'text-gray-700' : 'text-gray-300']">
                {{ $t('academyUrl') }}
              </label>
              <div class="relative">
                <input type="text" v-model="form.academy_url" placeholder="https://..." :class="inputClass" />
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-graduation-cap"></i></span>
              </div>
            </div>
            <div>
              <label :class="['block text-sm font-medium mb-1', form.theme === 'light' ? 'text-gray-700' : 'text-gray-300']">
                {{ $t('tutorialUrl') }}
              </label>
              <div class="relative">
                <input type="text" v-model="form.tutorial_url" placeholder="https://..." :class="inputClass" />
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-video"></i></span>
              </div>
            </div>
            <div>
              <label :class="['block text-sm font-medium mb-1', form.theme === 'light' ? 'text-gray-700' : 'text-gray-300']">
                {{ $t('chatbotUrl') }}
              </label>
              <div class="relative">
                <input type="text" v-model="form.chatbot_url" placeholder="https://t.me/your_bot" :class="inputClass" />
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-robot"></i></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div :class="['p-6 rounded-b-xl flex justify-end', form.theme === 'light' ? 'bg-white' : 'bg-gray-800/50']">
          <button type="submit" class="bg-blue-600 hover:bg-blue-600 text-white px-5 py-2 rounded-md text-center transition cursor-pointer">
            <i class="fas fa-save mr-2"></i>
            {{ $t('saveButton') }}
          </button>
        </div>

      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage, router, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const { props: pageProps } = usePage()
const user = pageProps.auth?.user || {}
const profileUrl = pageProps?.profileUrl || '/profile'
const logoutUrl = pageProps?.logoutUrl || '/logout'
const localeProp = pageProps?.locale || 'id'
const userPref = pageProps?.userPref || {}
const settings = pageProps?.settings || {}

// i18n
const { locale, t } = useI18n()
locale.value = localeProp

// Form
const form = ref({
  bank_name: userPref?.bank_name || '',
  bank_number: userPref?.bank_number || '',
  academy_url: settings?.url_academy || '',
  tutorial_url: settings?.url_tutorial || '',
  chatbot_url: settings?.chatbot_url || '',
  theme: userPref?.theme || 'light',
  bank_qr: null,
  previewQr: userPref?.bank_qr ? `/storage/${userPref.bank_qr}` : null,
})

const formClass = computed(() => form.value.theme === 'light' ? 'bg-white text-gray-900' : 'bg-gray-800 text-white')
const inputClass = computed(() => form.value.theme === 'light' 
  ? 'border border-gray-300 px-3 py-2.5 rounded-md w-full bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500'
  : 'border border-gray-600 px-3 py-2.5 rounded-md w-full bg-gray-700 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500'
)
const bankNameExample = computed(() => form.value.language === 'id' ? 'Contoh: BCA' : 'Example: BCA')
const bankNumberExample = computed(() => form.value.language === 'id' ? 'Contoh: 123456789' : 'Example: 123456789')

// Ganti file QR
function onFileChange(e) {
  const file = e.target.files[0]
  if (file) {
    form.value.bank_qr = file
    form.value.previewQr = URL.createObjectURL(file)
  }
}

// Submit settings
function submitSettings() {
  const data = new FormData()
  for (const key in form.value) {
    if (form.value[key] !== null) {
      data.append(key, form.value[key])
    }
  }

  router.post('/admin/settings/update', data, {
    forceFormData: true,
    onSuccess: () => alert(t('settingsSaved'))
  })
}

// Sidebar menu
const sidebarMenu = [
  { label: "Dashboard", url: "/admin/dashboard", icon: "fas fa-home" },
  { label: "Investasi", url: "/admin/investments", icon: "fas fa-chart-line" },
  { label: "Pengguna", url: "/admin/users", icon: "fas fa-users" },
  { label: "Withdraw", url: "/admin/withdraws", icon: "fas fa-money-bill-wave" },
  { label: "Pengaturan", url: "/admin/settings", icon: "fas fa-cog", active: true },
]
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");

main::-webkit-scrollbar { width: 6px; }
main::-webkit-scrollbar-thumb { background-color: rgba(100,100,100,0.3); border-radius: 3px; }
main::-webkit-scrollbar-thumb:hover { background-color: rgba(100,100,100,0.5); }
</style>
