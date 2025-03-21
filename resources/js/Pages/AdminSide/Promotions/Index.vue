<script setup>
import { ref, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import {
  MoreVerticalIcon,
  ChevronDownIcon,
  TrashIcon,
} from "lucide-vue-next";
import Sidebar from "../../../Components/Sidebar.vue";
import Header from "../../../Components/Header.vue";
import Toast from "@/Components/Toast.vue";

const props = defineProps({
  promotions: Array,
});

const headers = ["NAME", "CODE", "TYPE", "DISCOUNT", "DESCRIPTION", ""];
const isFilterOpen = ref(false);
const showDeleteModal = ref(false);
const promotionToDelete = ref(null);
const activeActionMenu = ref(null);
const currentFilter = ref('All');

const filteredPromotions = computed(() => {
  if (currentFilter.value === 'All') return props.promotions;
  return props.promotions.filter(promo => promo.type === currentFilter.value.toLowerCase());
});

const filterByType = (type) => {
  currentFilter.value = type;
  isFilterOpen.value = false;
};

const openActionMenu = (index) => {
  activeActionMenu.value = activeActionMenu.value === index ? null : index;
};

const openDeleteModal = (promotion) => {
  promotionToDelete.value = promotion;
  showDeleteModal.value = true;
  activeActionMenu.value = null;
};

const confirmDelete = () => {
  router.delete(route("promotions.destroy", promotionToDelete.value.id), {
    onSuccess: () => {
      showDeleteModal.value = false;
      promotionToDelete.value = null;
    },
  });
};
</script>

<template>
  <Head title=" | Promotions" />
  <Toast ref="toast" />
  <div class="min-h-screen bg-gray-50">
    <!-- Mobile Sidebar Toggle -->
    <Sidebar></Sidebar>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
      <!-- Header -->
      <Header title="Promotions"></Header>

      <!-- Promotions Content -->
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
              <button
                v-for="type in ['All', 'Item', 'Shipping']"
                :key="type"
                @click="filterByType(type)"
                class="block w-full text-left px-4 py-2 hover:bg-gray-100"
                :class="{ 'bg-gray-100': currentFilter === type }"
              >
                {{ type }}
              </button>
            </div>
          </div>
          <button class="px-4 py-2 bg-navy-600 text-white rounded-md hover:bg-navy-700">
            <Link :href="route('promotions.create')"> New Promotion </Link>
          </button>
        </div>

        <!-- Promotions Table -->
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
              <tr v-for="(promotion, index) in filteredPromotions" :key="promotion.id">
                <td class="px-6 py-4 whitespace-nowrap">{{ promotion.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ promotion.code }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ promotion.type }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ promotion.discount }}%</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ promotion.description }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right relative">
                  <button
                    @click="openActionMenu(index, $event)"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none"
                  >
                    <MoreVerticalIcon class="h-5 w-5" />
                  </button>
                  <!-- Action Menu -->
                  <div
                    v-if="activeActionMenu === index"
                    class="absolute z-50 bg-white border border-gray-200 shadow-lg rounded-md"
                    style="top: 0; right: 50%; margin-right: 0.5rem;"
                    ref="actionMenu"
                  >
                    <Link :href="route('promotions.edit', promotion.id)">
                      <button
                        class="block w-full px-4 py-2 text-sm text-green-500 hover:bg-green-100 text-left"
                      >
                        Edit
                      </button>
                    </Link>
                    <button
                      @click="openDeleteModal(promotion)"
                      class="block w-full px-4 py-2 text-sm text-red-600 hover:bg-red-100 text-left"
                    >
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
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
            <h3 class="mt-4 text-lg font-semibold">Delete Promotion</h3>
            <p class="mt-2 text-sm text-gray-500">Are you sure you want to delete this promotion?</p>
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
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.bg-navy-600 {
  background-color: #1a237e;
}

.bg-navy-700 {
  background-color: #151c63;
}

.hover\:bg-navy-700:hover {
  background-color: #151c63;
}
</style>
