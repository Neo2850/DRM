<template>
  <Head title=" | Product" />
  <div class="min-h-screen bg-gray-50">
    <!-- Mobile Sidebar Toggle -->
    <Sidebar></Sidebar>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
      <!-- Header -->
      <Header title="Product"></Header>

      <!-- Product Content -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
          <div class="relative">
            <button
              @click="isFilterOpen = !isFilterOpen"
              class="flex items-center px-4 py-2 border rounded-md bg-white hover:bg-gray-50"
            >
              Filter by
              <ChevronDownIcon class="ml-2 h-5 w-5" />
            </button>
            <!-- Filter Dropdown -->
            <div
              v-if="isFilterOpen"
              class="absolute mt-2 w-48 bg-white rounded-md shadow-lg z-10"
            >
              <!-- Add filter options here -->
            </div>
          </div>
          <button class="px-4 py-2 bg-navy-600 text-white rounded-md hover:bg-navy-700">
            <Link :href="route('products.create')"> New Product </Link>
          </button>
        </div>

        <!-- Product Table -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  v-for="header in headers"
                  :key="header"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  {{ header }}
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(product, index) in products.data" :key="product.id">
                <td class="px-6 py-4 whitespace-nowrap">{{ product.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ product.sku }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ product.category ? product.category.name : "N/A" }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ product.brand ? product.brand.name : "N/A" }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ product.price }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ product.stock }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div
                    class="h-3 w-3 rounded-full hover:cursor-help"
                    :class="product.stock > 10 ? 'bg-green-500' : 'bg-red-500'"
                    :title="product.stock > 10 ? 'Available' : 'Low Stock / Out of Stock'"
                  ></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                  <button
                    @click="openActionMenu(index, $event)"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none"
                  >
                    <MoreVerticalIcon class="h-5 w-5" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div
          v-if="activeActionMenu !== null"
          class="fixed z-50 bg-white border border-gray-200 shadow-lg rounded-md"
          :style="{
            top: dropdownPosition.top + 'px',
            left: dropdownPosition.left + 'px',
          }"
        >

          <Link :href="route('products.edit', products.data[activeActionMenu].id)">
            <button
              class="block w-full px-4 py-2 text-sm text-green-500 hover:bg-green-100 text-left"
            >
              Edit
            </button>
          </Link>
          <button
            @click="openDeleteModal(products.data[activeActionMenu])"
            class="block w-full px-4 py-2 text-sm text-red-600 hover:bg-red-100 text-left"
          >
            Delete
          </button>
        </div>

        <!-- Pagination Links-->
        <div class="flex items-center justify-between mt-6">
          <div class="flex items-center">
            <span class="text-sm text-gray-700"
              >Showing {{ products.from }} to {{ products.to }} of
              {{ products.total }} results</span
            >
          </div>
          <div class="flex gap-2">
            <Link
              v-for="link in products.links"
              :key="link.label"
              :href="link.url || '#'"
              :class="[
                'px-3 py-1 rounded-md',
                {
                  'pointer-events-none text-slate-300': !link.url,
                  'text-white bg-navy-900 font-medium hover:bg-navy-700': link.active,
                  'bg-stone-100': !link.active && link.url,
                },
              ]"
            >
              <template v-if="link.label.includes('Previous')">
                <ChevronLeftIcon class="h-5 w-5" />
              </template>
              <template v-else-if="link.label.includes('Next')">
                <ChevronRightIcon class="h-5 w-5" />
              </template>
              <template v-else>
                {{ link.label }}
              </template>
            </Link>
          </div>
        </div>
      </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full p-6">
          <div class="flex flex-col items-center">
            <div
              class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100"
            >
              <TrashIcon class="h-6 w-6 text-red-600" />
            </div>
            <h3 class="mt-4 text-lg font-semibold">Delete Item</h3>
            <p class="mt-2 text-sm text-gray-500">Are you sure you want to do this?</p>
            <div class="mt-6 flex gap-4">
              <button
                @click="showDeleteModal = false"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
              >
                Cancel
              </button>
              <button
                @click="confirmDelete"
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
              >
                Confirm
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import {
  MoreVerticalIcon,
  ChevronDownIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  TrashIcon,
} from "lucide-vue-next";
import Sidebar from "../../../Components/Sidebar.vue";
import Header from "../../../Components/Header.vue";
import { route } from "../../../../../vendor/tightenco/ziggy/src/js";
import { onMounted, onUnmounted } from "vue";

const props = defineProps({
  products: Object,
});

const isFilterOpen = ref(false);
const showDeleteModal = ref(false);
const productToDelete = ref(null);

const headers = ["NAME", "SKU", "CATEGORY", "BRAND", "PRICE", "STOCKS",  "", ""];

const activeActionMenu = ref(null);
const dropdownPosition = ref({ top: 0, left: 0 });

const openActionMenu = (index, event) => {
  if (activeActionMenu.value === index) {
    // If the same index is clicked again, close the modal
    closeActionMenu();
  } else {
    // Otherwise, open the modal for the clicked index
    activeActionMenu.value = index;
    dropdownPosition.value = {
      top: event.target.getBoundingClientRect().bottom + window.scrollY - 70,
      left: event.target.getBoundingClientRect().right - 100 + window.scrollX, // Align right
    };
  }
};

const closeActionMenu = () => {
  activeActionMenu.value = null;
};

const openDeleteModal = (item) => {
  productToDelete.value = item;
  activeActionMenu.value = null;
  showDeleteModal.value = true;
};

const confirmDelete = () => {
  router.delete(route("products.destroy", productToDelete.value.id), {
    onSuccess: () => {
      showDeleteModal.value = false;
      activeActionMenu.value = null;
    },
    onError: (errors) => {
      console.error(errors);
    },
  });
};

const logout = () => {
  // Implement logout logic
};

const getDate = (date) =>
  new Date(date).toLocaleDateString("en-us", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });

const makeLabel = (label) => {
  if (label.includes("Previous")) return "<";
  if (label.includes("Next")) return ">";
  return label;
};
</script>
<style scoped>
.bg-navy-600 {
  background-color: #001044;
}

.bg-navy-700 {
  background-color: #151c63;
}

.hover\:bg-navy-700:hover {
  background-color: #151c63;
}
</style>
