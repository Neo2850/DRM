<template>
  <Head title=" | Customers" />
  <Toast ref="toast" />
  <div class="min-h-screen bg-gray-50">
    <!-- Mobile Sidebar Toggle -->
    <Sidebar></Sidebar>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
      <!-- Header -->
      <Header title="Customers"></Header>

      <!-- Employees Content -->
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
        </div>

        <!-- Customers Table -->
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
              <tr
                v-for="customer in customers.data"
                :key="customer.id"
                class="cursor-pointer hover:bg-gray-50"
                @click="$inertia.visit(route('customers.orders', customer.id))"
              >
                <td class="px-6 py-4 whitespace-nowrap">{{ customer.first_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ customer.last_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ customer.email }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ customer.phone }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100 text-navy-900 font-semibold">{{ customer.orders_count }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ getDate(customer.created_at) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination Links -->
        <div class="flex items-center justify-between mt-6">
          <div class="flex items-center">
            <span class="text-sm text-gray-700">
              Showing {{ customers.from }} to {{ customers.to }} of
              {{ customers.total }} results
            </span>
          </div>
          <div class="flex gap-2">
            <Link
              v-for="link in customers.links"
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
            <h3 class="mt-4 text-lg font-semibold">Delete Employee</h3>
            <p class="mt-2 text-sm text-gray-500">
              Are you sure you want to delete this employee?
            </p>
            <div class="mt-6 flex gap-4">
              <button
                @click="closeModal"
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
import { ref, onMounted } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import {
  MoreVerticalIcon,
  ChevronDownIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  TrashIcon,
} from "lucide-vue-next";
import Sidebar from "../../../Components/Sidebar.vue";
import Header from "../../../Components/Header.vue";
import Toast from "../../../Components/Toast.vue";

const props = defineProps({
  customers: Object,
});

const headers = ["First Name", "Last Name", "Email", "Phone", "Orders", "Created At"];

const getDate = (date) =>
  new Date(date).toLocaleDateString("en-us", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
const isFilterOpen = ref(false);
const showDeleteModal = ref(false);
const activeActionMenu = ref(null);
const dropdownPosition = ref({ top: 0, left: 0 });
const userToDelete = ref(null);
const errorMessage = ref(null);
const toast = ref(null);

onMounted(() => {
  const page = usePage().props;

  if (page.flash) {
    if (page.flash.message && toast.value) {
      toast.value.addToast(page.flash.message, page.flash.type || "success");
    }

    if (page.errors && Object.keys(page.errors).length > 0) {
      const errorMessage = Object.values(page.errors)[0];
      if (toast.value) {
        toast.value.addToast(errorMessage, "error");
      }
    }
  }
});

const openActionMenu = (user, event) => {
  if (activeActionMenu.value === user) {
    closeActionMenu();
  } else {
    activeActionMenu.value = user;
    dropdownPosition.value = {
      top: event.target.getBoundingClientRect().bottom + window.scrollY - 70,
      left: event.target.getBoundingClientRect().right - 100 + window.scrollX,
    };
  }
};

const openDeleteModal = (user) => {
  userToDelete.value = user;
  activeActionMenu.value = null;
  showDeleteModal.value = true;
};

const closeActionMenu = () => {
  activeActionMenu.value = null;
};

const confirmDelete = () => {
  //   if (!userToDelete.value) return;
  //   router.delete(route("employees.destroy", userToDelete.value.id), {
  //     preserveScroll: true,
  //     onSuccess: () => {
  //       showDeleteModal.value = false;
  //       userToDelete.value = null;
  //     },
  //     onError: (errors) => {
  //       showDeleteModal.value = false;
  //       if (errors.error) {
  //         toast.value.addToast(errors.error, "error");
  //       }
  //     },
  //   });
};

const closeModal = () => {
  showDeleteModal.value = false;
  userToDelete.value = null;
};

const makeLabel = (label) => {
  if (label.includes("Previous")) return "<";
  if (label.includes("Next")) return ">";
  return label;
};

const clearError = () => {
  errorMessage.value = null;
};
</script>
