<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <div>
      <!-- Overlay Mobile -->
      <div
        v-if="!collapsed && isMobile"
        class="fixed inset-0 bg-black bg-opacity-50 z-40"
        @click="toggleSidebar"
      ></div>

      <aside
        :class="[
          'bg-white shadow-lg w-64 h-screen fixed z-50 transition-all duration-300',
          collapsed ? '-left-64' : 'left-0'
        ]"
      >
        <!-- Logo -->
        <div class="p-5 border-b border-gray-200 flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div
              class="h-10 w-10 bg-gradient-to-r from-blue-600 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-lg"
            >
              S
            </div>
            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
              Spectra
            </span>
          </div>
          <button class="md:hidden" @click="toggleSidebar">✕</button>
        </div>

        <!-- Menu -->
        <nav class="mt-4 p-2 overflow-y-auto h-[calc(100vh-5rem)]">
          <ul>
            <li v-for="item in menu" :key="item.name" class="mb-1">
              <a
                :href="item.link"
                class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100"
                :class="activeLink === item.link ? 'bg-blue-50 text-blue-600 font-medium' : ''"
                @click="setActiveLink(item.link)"
              >
                <span>{{ item.name }}</span>
              </a>
            </li>
          </ul>
        </nav>
      </aside>
    </div>

    <!-- Konten utama -->
    <div
      class="flex-1 flex flex-col transition-all duration-300"
      :class="!isMobile ? 'md:ml-64' : ''"
    >
      <!-- Navbar -->
      <header
        class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-40"
      >
        <div class="flex items-center space-x-3">
          <button class="md:hidden text-gray-600" @click="toggleSidebar">☰</button>
          <div class="text-lg font-semibold text-gray-800">{{ title }}</div>
        </div>

        <div class="flex items-center space-x-4">
          <div class="flex items-center space-x-3">
            <div class="hidden md:block text-right">
              <p class="text-sm font-medium text-gray-800">{{ user.name }}</p>
              <p class="text-xs text-gray-500">{{ user.role }}</p>
            </div>
            <div
              class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-sm"
            >
              {{ userInitials }}
            </div>
          </div>
        </div>
      </header>

      <!-- Konten halaman -->
      <main class="p-6 bg-gray-100 flex-1 overflow-auto">
        <slot></slot>
      </main>
    </div>
  </div>
</template>

<script>
export default {
  name: "AdminLayout",
  props: {
    title: { type: String, default: "Dashboard" },
    user: { type: Object, default: () => ({ name: "Admin", role: "Administrator" }) },
    activeLink: { type: String, default: "/admin/dashboard" },
  },
  data() {
    return {
      collapsed: false,
      isMobile: window.innerWidth < 768,
      menu: [
        { name: "Dashboard", link: "/admin/dashboard" },
        { name: "Manajemen Pengguna", link: "/admin/manajemen_users" },
        { name: "Manajemen Deposit", link: "/admin/deposits" },
        { name: "Manajemen Withdraw", link: "/admin/withdraws" },
        { name: "Manajemen Kontrak", link: "/admin/contracts" },
        { name: "Pembayaran Profit", link: "/admin/profit" },
        { name: "Komisi & Bonus", link: "/admin/commissions" },
        { name: "Laporan Keuangan", link: "/admin/reports" },
        { name: "Pengaturan Sistem", link: "/admin/settings" },
        { name: "Notifikasi & Broadcast", link: "/admin/notifications" },
        { name: "Audit Log", link: "/admin/audit" },
      ],
    };
  },
  computed: {
    userInitials() {
      return this.user.name
        ? this.user.name.split(" ").map((n) => n[0]).join("")
        : "A";
    },
  },
  methods: {
    toggleSidebar() {
      this.collapsed = !this.collapsed;
    },
    setActiveLink(link) {
      this.$emit("update:activeLink", link);
      if (this.isMobile) this.collapsed = true;
    },
  },
  mounted() {
    window.addEventListener("resize", () => {
      this.isMobile = window.innerWidth < 768;
      if (!this.isMobile) this.collapsed = false;
    });
  },
};
</script>
