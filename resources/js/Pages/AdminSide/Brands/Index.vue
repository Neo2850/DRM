<template>
  <Head title=" | Brand" />
  <div class="min-h-screen bg-gray-50">
    <!-- Mobile Sidebar Toggle -->
    <Sidebar></Sidebar>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
      <!-- Header -->
      <Header title="Brand"></Header>

      <!-- Brand Content -->
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
            <Link :href="route('brands.create')"> New Brand </Link>
          </button>
        </div>

        <!-- Brand Table -->
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
              <tr v-for="(brand, index) in brands.data" :key="brand.id">
                <td class="px-6 py-4 whitespace-nowrap">{{ brand.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <img
                    :src="brand.image ? '/storage/' + brand.image : 'storage/default.jpg'"
                    class="h-10 w-24 object-cover rounded-md shadow-sm"
                  />
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ brand.slug }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ getDate(brand.created_at) }}
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

        <!-- Pagination Links -->
        <div class="flex items-center justify-between mt-6">
          <div class="flex items-center">
            <span class="text-sm text-gray-700"
              >Showing {{ brands.from }} to {{ brands.to }} of
              {{ brands.total }} results</span
            >
          </div>
          <div class="flex gap-2">
            <Link
              v-for="link in brands.links"
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

    <!-- Action Menu -->
    <div
      v-if="activeActionMenu !== null"
      class="fixed z-50 bg-white border border-gray-200 shadow-lg rounded-md"
      :style="{ top: dropdownPosition.top + 'px', left: dropdownPosition.left + 'px' }"
    >
      <Link :href="route('brands.edit', brands.data[activeActionMenu].id)">
        <button
          class="block w-full px-4 py-2 text-sm text-green-500 hover:bg-green-100 text-left"
        >
          Edit
        </button>
      </Link>
      <button
        @click="openDeleteModal(brands.data[activeActionMenu])"
        class="block w-full px-4 py-2 text-sm text-red-600 hover:bg-red-100 text-left"
      >
        Delete
      </button>
    </div>

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

const props = defineProps({
  brands: Object,
});

const headers = ["NAME", "IMG", "SLUG", "CREATED_AT", ""];

const isFilterOpen = ref(false);
const showDeleteModal = ref(false);
const brandToDelete = ref(null);

const activeActionMenu = ref(null);
const dropdownPosition = ref({ top: 0, left: 0 });

const openActionMenu = (index, event) => {
  if (activeActionMenu.value === index) {
    closeActionMenu();
  } else {
    activeActionMenu.value = index;
    dropdownPosition.value = {
      top: event.target.getBoundingClientRect().bottom + window.scrollY - 70,
      left: event.target.getBoundingClientRect().right - 100 + window.scrollX,
    };
  }
};

const closeActionMenu = () => {
  activeActionMenu.value = null;
};

const openDeleteModal = (brand) => {
  brandToDelete.value = brand;
  closeActionMenu();
  showDeleteModal.value = true;
};

const confirmDelete = () => {
  router.delete(route("brands.destroy", brandToDelete.value.id), {
    onSuccess: () => {
      showDeleteModal.value = false;
      closeActionMenu();
    },
  });
};

const makeLabel = (label) => {
  if (label.includes("Previous")) return "<";
  if (label.includes("Next")) return ">";
  return label;
};

const getDate = (date) =>
  new Date(date).toLocaleDateString("en-us", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
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
