<template>
  <div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <div>
      <!-- Overlay Mobile -->
      <div
        v-if="!collapsed && isMobile"
        class="fixed inset-0 bg-black bg-opacity-40 z-40"
        @click="toggleSidebar"
      ></div>

      <aside
        :class="[
          'bg-white shadow-xl w-64 h-screen fixed z-50 transition-all duration-300',
          collapsed ? '-left-64' : 'left-0'
        ]"
      >
        <!-- Logo -->
        <div
          class="p-5 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-blue-600 to-green-500"
        >
          <div class="flex items-center space-x-3">
            <div
              class="h-10 w-10 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-lg"
            >
              S
            </div>
            <span class="text-xl font-bold text-white tracking-wide">
              Spectra
            </span>
          </div>
          <button class="md:hidden text-white" @click="toggleSidebar">✕</button>
        </div>

        <!-- Menu -->
        <nav class="mt-4 p-2 overflow-y-auto h-[calc(100vh-5rem)]">
          <ul>
            <li v-for="item in menu" :key="item.name" class="mb-1">
              <a
                :href="item.link"
                class="flex items-center px-4 py-3 rounded-lg transition-all duration-200"
                :class="[
                  currentPath.startsWith(item.link)
                    ? 'bg-blue-600 text-white font-medium shadow-md'
                    : 'text-gray-600 hover:bg-gray-100 hover:text-blue-600'
                ]"
                @click="setActiveLink(item.link)"
              >
                <span class="truncate">{{ item.name }}</span>
              </a>
            </li>

            <!-- Logout -->
            <li class="mt-4">
              <button
                @click="logout"
                class="w-full text-left flex items-center px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700 transition-all duration-200"
              >
                Logout
              </button>
            </li>
          </ul>
        </nav>
      </aside>
    </div>

    <!-- Main Content -->
    <div
      class="flex-1 flex flex-col transition-all duration-300"
      :class="!isMobile ? 'md:ml-64' : ''"
    >
      <!-- Navbar -->
      <header
        class="bg-white shadow-md p-4 flex justify-between items-center sticky top-0 z-40"
      >
        <div class="flex items-center space-x-3">
          <button class="md:hidden text-gray-600 text-xl" @click="toggleSidebar">
            ☰
          </button>
          <div class="text-lg font-semibold text-gray-800">{{ title }}</div>
        </div>

        <!-- User Info -->
        <div class="flex items-center space-x-4 relative">
          <div class="hidden md:block text-right">
            <p class="text-sm font-medium text-gray-800">{{ user.name }}</p>
            <p class="text-xs text-gray-500">{{ user.role }}</p>
          </div>

          <div
            @click="toggleUserMenu"
            class="w-9 h-9 rounded-full bg-gradient-to-r from-blue-500 to-green-500 flex items-center justify-center text-white font-semibold text-sm shadow-md cursor-pointer"
          >
            {{ userInitials }}
          </div>

          <!-- Dropdown Menu -->
          <div
            v-if="showUserMenu"
            class="absolute right-0 mt-12 w-40 bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden z-50"
          >
            <button
              @click="logout"
              class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"
            >
              Logout
            </button>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-6 bg-gray-50 flex-1 overflow-auto">
        <slot></slot>
      </main>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "AdminLayout",
  props: {
    title: { type: String, default: "Dashboard" },
    user: { type: Object, default: () => ({ name: "Admin", role: "Administrator" }) },
  },
  data() {
    return {
      collapsed: false,
      isMobile: window.innerWidth < 768,
      showUserMenu: false,
      menu: [
        { name: "Dashboard", link: "/admin/dashboard" },
        { name: "Manajemen Pengguna", link: "/admin/manajemen_users" },
        { name: "Manajemen Deposit", link: "/admin/deposits" },
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
    currentPath() {
      return this.$page?.url || window.location.pathname;
    },
  },
  methods: {
    toggleSidebar() {
      this.collapsed = !this.collapsed;
    },
    setActiveLink() {
      if (this.isMobile) this.collapsed = true;
    },
    toggleUserMenu() {
      this.showUserMenu = !this.showUserMenu;
    },
    logout() {
      axios.post("/logout")
        .then(() => {
          window.location.href = "/login";
        })
        .catch(err => {
          console.error("Logout gagal:", err);
          window.location.href = "/login";
        });
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
