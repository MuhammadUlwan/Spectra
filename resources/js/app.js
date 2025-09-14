import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { InertiaProgress } from '@inertiajs/progress';
import { Link } from '@inertiajs/vue3';
import { createI18n } from 'vue-i18n';

// ========================
// Pesan i18n (diperluas)
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
    notifications: 'Notifikasi'
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
    notifications: 'Notifications'
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
resolvePageComponent(
  `./Pages/${name}.vue`,
  import.meta.glob('./Pages/**/*.vue')
).then(module => module.default),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) });

    app.use(plugin);
    app.use(i18n);
    app.component('Link', Link);

    // override locale dari props Inertia jika ada
    if (props.initialPage.props.locale) {
      i18n.global.locale.value = props.initialPage.props.locale;
    }

    app.mount(el);
  },
});