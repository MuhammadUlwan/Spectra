<template>
  <AdminLayout
    :user="user"
    :profileUrl="profileUrl"
    :logoutUrl="logoutUrl"
    :sidebarMenu="sidebarMenu"
  >
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header section -->
      <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
              {{ $t('settingsTitle') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm md:text-base">
              {{ $t('settingsSubtitle') }}
            </p>
          </div>
        </div>
      </div>

      <!-- Settings card -->
      <div :class="[formClass, 'rounded-xl shadow-lg divide-y', form.theme === 'light' ? 'divide-gray-200' : 'divide-gray-700']">

        <!-- General Settings -->
        <div class="p-6">
          <h2 :class="['text-xl font-semibold mb-4 flex items-center', form.theme === 'light' ? 'text-gray-900' : 'text-white']">
            <i class="fas fa-cog mr-2 text-blue-500"></i>
            {{ $t('generalSettings') }}
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label :class="['block text-sm font-medium mb-1', form.theme === 'light' ? 'text-gray-700' : 'text-gray-300']">
                {{ $t('currencyLabel') }}
              </label>
              <div class="relative">
                <input type="text" :value="form.currency" disabled :class="inputClass" />
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                  <i class="fas fa-dollar-sign"></i>
                </span>
              </div>
            </div>
            <div>
              <label :class="['block text-sm font-medium mb-1', form.theme === 'light' ? 'text-gray-700' : 'text-gray-300']">
                {{ $t('languageLabel') }}
              </label>
              <select v-model="form.language" :class="inputClass">
                <option value="id">Indonesia</option>
                <option value="en">English</option>
              </select>
            </div>
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
        </div>

      <!-- Profit & Fee Settings -->
      <div class="p-6">
        <h2 :class="['text-xl font-semibold mb-4 flex items-center', form.theme === 'light' ? 'text-gray-900' : 'text-white']">
          <i class="fas fa-percentage mr-2 text-yellow-500"></i>
          {{ $t('profitSettings') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div v-for="(value, key) in profitSettings" :key="key">
            <label :class="['block text-sm font-medium mb-1', form.theme === 'light' ? 'text-gray-700' : 'text-gray-300']">
              {{ $t(key) }}
            </label>
            <div class="relative">
              <input type="number" v-model.number="profitSettings[key]" step="0.01" min="0" :class="inputClass" />
              <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">%</span>
            </div>
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
          <button type="submit" @click="submitSettings" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition flex items-center">
            <i class="fas fa-save mr-2"></i>
            {{ $t('saveButton') }}
          </button>
        </div>

      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
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
  currency: userPref?.currency || 'USDT',
  bank_name: userPref?.bank_name || '',
  bank_number: userPref?.bank_number || '',
  academy_url: settings?.url_academy || '',
  tutorial_url: settings?.url_tutorial || '',
  chatbot_url: settings?.chatbot_url || '',
  language: userPref?.language || 'id',
  theme: userPref?.theme || 'light',
})

// Object profitSettings diambil dari props Inertia (database)
const profitSettings = ref({
  profit_percent: settings.profit_percent || 7.5,
  sponsor_fee_direct: settings.sponsor_fee_direct || 7.5,
  sponsor_fee_indirect: settings.sponsor_fee_indirect || 2.5,
  profit_sharing_level1: settings.profit_sharing_level1 || 1.25,
  profit_sharing_level2: settings.profit_sharing_level2 || 0.75,
  profit_sharing_level3: settings.profit_sharing_level3 || 0.5,
  bonus_target_omset: settings.bonus_target_omset || 10000,
  bonus_profit_extra: settings.bonus_profit_extra || 1.25,
  profit_percent_15days: settings.profit_percent_15days || 3.5,
})

// Watch language untuk i18n
watch(() => form.value.language, (newLang) => locale.value = newLang)

const formClass = computed(() => form.value.theme === 'light' ? 'bg-white text-gray-900' : 'bg-gray-800 text-white')
const inputClass = computed(() => form.value.theme === 'light' 
  ? 'border border-gray-300 px-3 py-2.5 rounded-md w-full bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500'
  : 'border border-gray-600 px-3 py-2.5 rounded-md w-full bg-gray-700 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500'
)
const bankNameExample = computed(() => form.value.language === 'id' ? 'Contoh: BCA' : 'Example: BCA')
const bankNumberExample = computed(() => form.value.language === 'id' ? 'Contoh: 123456789' : 'Example: 123456789')

// Submit settings
function submitSettings() {
  router.post('/admin/settings/update', {
    ...form.value,
    ...profitSettings.value
  }, {
    onSuccess: () => alert(t('saveSuccess')),
    onError: () => alert(t('saveError'))
  })
}

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
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");

/* Scrollbar untuk main content */
main::-webkit-scrollbar { width: 6px; }
main::-webkit-scrollbar-thumb { background-color: rgba(100,100,100,0.3); border-radius: 3px; }
main::-webkit-scrollbar-thumb:hover { background-color: rgba(100,100,100,0.5); }
</style>
