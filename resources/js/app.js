import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { InertiaProgress } from '@inertiajs/progress';
import { Link } from '@inertiajs/vue3';
import { createI18n } from 'vue-i18n';

// ========================
// Pesan i18n (gabungan)
// ========================
const messages = {
  id: {
    settingsTitle: 'Pengaturan Sistem',
    settingsDescription: 'Kelola preferensi dan konfigurasi sistem Spectra',
    generalSettings: 'Pengaturan Umum',
    bankInformation: 'Informasi Bank',
    urlSettings: 'Pengaturan URL',
    currencyLabel: 'Mata Uang',
    bankName: 'Nama Bank',
    bankNumber: 'Nomor Rekening',
    academyUrl: 'URL Akademi',
    tutorialUrl: 'URL Tutorial',
    chatbotUrl: 'URL Chatbot Telegram',
    languageLabel: 'Bahasa',
    themeLabel: 'Tema',
    saveButton: 'Simpan Perubahan',
    cancelButton: 'Batal',
    saveSuccess: 'Pengaturan berhasil disimpan!',
    saveError: 'Gagal menyimpan pengaturan!',
    themeLight: 'Terang',
    themeDark: 'Gelap',
    dashboard: 'Dashboard',
    investments: 'Investasi',
    users: 'Pengguna',
    withdraw: 'Withdraw',
    settings: 'Pengaturan',
    helloAdmin: 'Halo Admin',
    profile: 'Profil',
    logout: 'Keluar',
    notifications: 'Notifikasi',
    percentageLabel: 'Persentase', 
    profitSettings: 'Pengaturan Profit',
    profit_percent: 'Persentase Profit',
    sponsor_fee_direct: 'Biaya Sponsor Langsung',
    sponsor_fee_indirect: 'Biaya Sponsor Tidak Langsung',
    profit_sharing_level1: 'Profit Sharing Level 1',
    profit_sharing_level2: 'Profit Sharing Level 2',
    profit_sharing_level3: 'Profit Sharing Level 3',
    bonus_target_omset: 'Bonus Target Omset',
    bonus_profit_extra: "Extra Profit Bonus",
    profit_percent_15days : "Profit Percent for 15 Days",
  },
  en: {
    settingsTitle: 'System Settings',
    settingsDescription: 'Manage Spectra system preferences and configuration',
    generalSettings: 'General Settings',
    bankInformation: 'Bank Information',
    urlSettings: 'URL Settings',
    currencyLabel: 'Currency',
    bankName: 'Bank Name',
    bankNumber: 'Account Number',
    academyUrl: 'Academy URL',
    tutorialUrl: 'Tutorial URL',
    chatbotUrl: 'Chatbot Telegram URL',
    languageLabel: 'Language',
    themeLabel: 'Theme',
    saveButton: 'Save Changes',
    cancelButton: 'Cancel',
    saveSuccess: 'Settings saved successfully!',
    saveError: 'Failed to save settings!',
    themeLight: 'Light',
    themeDark: 'Dark',
    dashboard: 'Dashboard',
    investments: 'Investments',
    users: 'Users',
    withdraw: 'Withdraw',
    settings: 'Settings',
    helloAdmin: 'Hello Admin',
    profile: 'Profile',
    logout: 'Logout',
    notifications: 'Notifications',
    percentageLabel: 'Percentage',
    profitSettings: 'Profit Settings',
    profit_percent: 'Profit Percent',
    sponsor_fee_direct: 'Direct Sponsor Fee',
    sponsor_fee_indirect: 'Indirect Sponsor Fee',
    profit_sharing_level1: 'Profit Sharing Level 1',
    profit_sharing_level2: 'Profit Sharing Level 2',
    profit_sharing_level3: 'Profit Sharing Level 3',
    bonus_target_omset: 'Target Turnover Bonus',
    bonus_profit_extra: "Bonus Profit Tambahan",
    profit_percent_15days : "Persentase Profit 15 Hari"
  },
};

// ========================
// Inertia progress bar
// ========================
InertiaProgress.init({
  color: '#4ADE80',
  showSpinner: true,
});

// ========================
// Buat i18n instance
// ========================
const i18n = createI18n({
  legacy: false,
  locale: 'id',
  fallbackLocale: 'id',
  messages,
});

// ========================
// Buat Inertia app
// ========================
createInertiaApp({
  resolve: name =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')).then(
      module => module.default
    ),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) });

    app.use(plugin);
    app.use(i18n);
    app.component('Link', Link);

    if (props.initialPage.props.locale) {
      i18n.global.locale.value = props.initialPage.props.locale;
    }

    app.mount(el);
  },
});
