
<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Link, useForm, router, usePage } from "@inertiajs/vue3";
import {
  MenuIcon,
  XIcon,
  SearchIcon,
  ShoppingCartIcon,
  User2,
  Package,
  LogOut,
  ChevronDown,
} from "lucide-vue-next";
import DRMLogo from "@/Pages/ClientSide/assets/DRMLogo.png";

// State
const isMenuOpen = ref(false);
const isProfileMenuOpen = ref(false);

const props = defineProps({
  searchTerm: {
    type: String,
    required: false,
  },
});
const form = useForm({
  search: props.searchTerm,
});

const searchProducts = () => {
  router.get(route("product.list", { search: form.search }));
};

const navigationItems = [
  { name: "Home", href: "/" },
  { name: "Products", href: "/product-list" },
  { name: "About", href: "/category-products/laptop" },
  { name: "Contact Us", href: "/category-products/desktop" },
];

// Get cart count directly from shared props
const cartCount = computed(() => usePage().props.cartCount);

// Add these new refs and computed properties
const customer = computed(() => usePage().props.auth?.customer);

const customerInitials = computed(() => {
  if (!customer.value) return "";
  return `${customer.value.first_name.charAt(0)}${customer.value.last_name.charAt(
    0
  )}`.toUpperCase();
});

// Add logout function
const logout = () => {
  router.post(route("customer.logout"));
};

// Close profile menu when clicking outside
const closeProfileMenu = (e) => {
  if (!e.target.closest(".relative.group")) {
    isProfileMenuOpen.value = false;
  }
};

// Add click outside listener
onMounted(() => {
  document.addEventListener("click", closeProfileMenu);
});

onUnmounted(() => {
  document.removeEventListener("click", closeProfileMenu);
});
</script>

<template>
  <!-- Header/Navigation -->
  <header class="sticky top-0 z-50 bg-white shadow-sm">
    <nav class="container mx-auto px-4 py-4">
      <div class="flex items-center justify-between">
        <!-- Logo -->
        <Link href="/" class="text-2xl flex flex-row items-center font-bold text-navy-900">
          <img :src="DRMLogo" alt="" class="h-16 w-16"> DRM
        </Link>

        <!-- Desktop Navigation -->
        <div class="hidden md:hidden lg:flex items-center space-x-8">
          <Link
            v-for="item in navigationItems"
            :key="item.name"
            :href="item.href"
            class="text-gray-700 hover:text-navy-600"
          >
            {{ item.name }}
          </Link>
        </div>

        <!-- Search and Cart -->
        <div class="flex items-center space-x-4">
          <div class="hidden md:block lg:block relative">
            <form @submit.prevent="searchProducts">
              <input
                type="search"
                placeholder="Search for products..."
                class="w-64 pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                v-model="form.search"
              />
              <SearchIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
            </form>
          </div>
          <Link
            href="/cart"
            class="relative inline-flex items-center p-2 text-gray-600 hover:text-gray-900"
          >
            <ShoppingCartIcon class="h-6 w-6" />
            <!-- Badge -->
            <div
              v-if="cartCount > 0"
              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"
            >
              {{ cartCount }}
            </div>
          </Link>
          <!-- Mobile Menu Toggle -->
          <button
            @click="isMenuOpen = !isMenuOpen"
            class="lg:hidden text-gray-700 hover:text-navy-600"
          >
            <MenuIcon v-if="!isMenuOpen" class="h-6 w-6" />
            <XIcon v-else class="h-6 w-6" />
          </button>
          <!-- Login/User Profile -->
          <template v-if="customer">
            <div class="flex items-center space-x-2">
              <div class="relative group">
                <button
                  class="flex items-center space-x-2 focus:outline-none"
                  @click="isProfileMenuOpen = !isProfileMenuOpen"
                >
                  <div
                    class="h-8 w-8 rounded-full bg-navy-600 flex items-center justify-center text-white font-medium"
                  >
                    {{ customerInitials }}
                  </div>
                  <span class="text-gray-700">{{ customer.first_name }}</span>
                  <ChevronDown class="h-4 w-4 text-gray-500" />
                </button>

                <!-- Dropdown Menu -->
                <div
                  v-if="isProfileMenuOpen"
                  class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5"
                >
                  <Link
                    :href="route('customer.profile')"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    <User2 class="h-4 w-4 inline-block mr-2" />
                    My Profile
                  </Link>
                  <Link
                    href="/customer/my-orders"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    <Package class="h-4 w-4 inline-block mr-2" />
                    My Orders
                  </Link>
                  <button
                    @click="logout"
                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    <LogOut class="h-4 w-4 inline-block mr-2" />
                    Logout
                  </button>
                </div>
              </div>
            </div>
          </template>
          <template v-else>
            <Link
              href="/customer/login"
              class="py-2 px-4 border bg-navy-900 text-white border-navy-600 rounded-md"
            >
              Login
            </Link>
          </template>
        </div>
      </div>

      <!-- Mobile Navigation -->
      <div v-if="isMenuOpen" class="lg:hidden mt-4 border-t pt-4">
        <Link
          v-for="item in navigationItems"
          :key="item.name"
          :href="item.href"
          class="block py-2 px-4 text-gray-700 hover:text-navy-600"
        >
          {{ item.name }}
        </Link>
        <!-- Mobile Search -->
        <div class="mt-4 relative">
          <form @submit.prevent="searchProducts">
            <input
              type="text"
              placeholder="Search for products..."
              class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
              v-model="form.search"
            />
            <SearchIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
          </form>
        </div>
      </div>
    </nav>
  </header>
</template>


<style scoped>
/* Optional: Add animation for badge */
.absolute {
  transition: all 0.2s ease-in-out;
}

/* Add these new styles */
.relative.group {
  position: relative;
}

/* Optional: Add transition for dropdown */
.absolute {
  transition: all 0.2s ease-in-out;
}
</style>
