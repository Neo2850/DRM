<template>
  <button
    @click="isSidebarOpen = !isSidebarOpen"
    class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-white shadow-md"
  >
    <MenuIcon v-if="!isSidebarOpen" class="h-6 w-6" />
    <XIcon v-else class="h-6 w-6" />
  </button>

  <aside
    class="fixed inset-y-0 left-0 z-40 w-64 bg-white shadow-sm transform transition-transform duration-300 ease-in-out lg:translate-x-0"
    :class="{ 'translate-x-0': isSidebarOpen, '-translate-x-full': !isSidebarOpen }"
  >
    <div class="h-full flex flex-col">
      <div class="p-4 border-b">
        <h1 class="text-2xl text-navy-900 font-bold">ArtzworkPC</h1>
      </div>

      <nav class="flex-1 p-4 space-y-2">
        <Link
          v-for="item in menuItems"
          :key="item.name"
          :href="item.href"
          :class="[
            'flex items-center px-4 py-2 rounded-md',
            item.current
              ? 'bg-gray-100 text-gray-900'
              : 'text-gray-600 hover:bg-gray-100',
          ]"
        >
          <component :is="item.icon" class="h-5 w-5 mr-3" />
          {{ item.name }}
        </Link>
      </nav>
    </div>
  </aside>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
  HomeIcon,
  TagIcon,
  ShoppingBagIcon,
  UsersIcon,
  ClipboardListIcon,
  LogOutIcon,
  MenuIcon,
  XIcon,
  PackageIcon,
} from "lucide-vue-next";

const isSidebarOpen = ref(false);

// Get current URL path from Inertia's usePage function
const currentPath = computed(() => usePage().url);

const menuItems = computed(() =>
  [
    { name: "Dashboard", icon: HomeIcon, href: route("dashboard") },
    { name: "Category", icon: TagIcon, href: route("categories.index") },
    { name: "Brands", icon: ShoppingBagIcon, href: route("brands.index") },
    { name: "Products", icon: PackageIcon, href: route("products.index") },
    { name: "Transactions", icon: ClipboardListIcon, href: route("transactions") },
    { name: "Order History", icon: ClipboardListIcon, href: route("orders.show") },
    { name: "Customers", icon: UsersIcon, href: route("customers") },
    { name: "Employees", icon: UsersIcon, href: route("employees.index") },
  ].map((item) => ({
    ...item,
    current: currentPath.value.startsWith(item.href),
  }))
);

const logout = () => {
  // Implement logout functionality
};
</script>
