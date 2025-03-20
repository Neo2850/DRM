<template>
  <Head title=" | Transactions" />
  <Toast ref="toast" />
  <div class="min-h-screen bg-gray-50">
    <!-- Mobile Sidebar Toggle -->
    <Sidebar></Sidebar>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
      <!-- Header -->
      <Header title="Transactions"></Header>

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
              class="absolute mt-2 w-48 bg-white roun ded-md shadow-lg z-10"
            >
              <!-- Add filter options here -->
            </div>
          </div>
        </div>

        <!-- Employees Table -->
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
              <tr v-for="order in orders.data" :key="order.id">
                <td class="px-6 py-4 whitespace-nowrap uppercase">{{ order.reference_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ order.customer_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ formatDateTime(order.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ order.total_amount }}</td>
                <td class="px-6 py-4 whitespace-nowrap upSpercase">
                  {{ order.payment_status }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap uppercase">
                  {{ order.payment_method }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="[
                      ' py-1 text-md uppercase ',
                      order.order_status === 'pending'
                        ? ' text-yellow-500'
                        : 'bg-red-100 text-red-800',
                    ]"
                  >
                    {{ order.order_status }}
                  </span>
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-right">
                  <button
                    @click="openActionMenu(order, $event)"
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
            <span class="text-sm text-gray-700">
              Showing {{ orders.from }} to {{ orders.to }} of {{ orders.total }} results
            </span>
          </div>
          <div class="flex gap-2">
            <Link
              v-for="link in orders.links"
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
      <button
        @click="openAcceptModal(activeActionMenu)"
        class="block w-full px-4 py-2 text-sm text-green-600 hover:bg-green-50 text-left"
      >
        Approve
      </button>
      <button
        @click="openDeleteModal(activeActionMenu)"
        class="block w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 text-left"
      >
        Decline
      </button>
    </div>

    <!-- Approve Modal -->
    <div v-if="showAcceptModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full p-6">
          <div class="flex flex-col items-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
              <CheckIcon class="h-6 w-6 text-green-600" />
            </div>
            <h3 class="mt-4 text-lg font-semibold">Approve Transaction</h3>
            <p class="mt-2 text-sm text-gray-500">
              Are you sure you want to do this?
            </p>
            <div class="mt-6 flex gap-4">
              <button
                @click="closeModal"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
              >
                Cancel
              </button>
              <button
                @click="confirmAccept"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
              >
                Confirm
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Decline Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full p-6">
          <div class="flex flex-col items-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
              <XIcon class="h-6 w-6 text-red-600" />
            </div>
            <h3 class="mt-4 text-lg font-semibold">Decline Transaction</h3>
            <p class="mt-2 text-sm text-gray-500">
              Are you sure you want to do this?
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
  CheckIcon,
  XIcon,
} from "lucide-vue-next";
import Sidebar from "../../../Components/Sidebar.vue";
import Header from "../../../Components/Header.vue";
import Toast from "../../../Components/Toast.vue";

const props = defineProps({
  orders: Object,
});

const headers = ["Order ID", "Customer", "Date", "Total", "Payment Status", "Method", "Status", ""];

const formatDateTime = (date) => {
  if (!date) return "No activity yet";
  return new Date(date).toLocaleString("en-us", {
    hour: "numeric",
    minute: "numeric",
    hour12: true, // Use 12-hour format
  });
};
const isFilterOpen = ref(false);
const showDeleteModal = ref(false);
const activeActionMenu = ref(null);
const dropdownPosition = ref({ top: 0, left: 0 });
const userToDelete = ref(null);
const errorMessage = ref(null);
const toast = ref(null);
const showAcceptModal = ref(false);
const orderToAccept = ref(null);
const orderToDecline = ref(null);

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

const openActionMenu = (order, event) => {
  if (activeActionMenu.value === order) {
    closeActionMenu();
  } else {
    activeActionMenu.value = order;
    dropdownPosition.value = {
      top: event.target.getBoundingClientRect().bottom + window.scrollY - 70,
      left: event.target.getBoundingClientRect().right - 100 + window.scrollX,
    };
  }
};

const openAcceptModal = (order) => {
  orderToAccept.value = order;
  activeActionMenu.value = null;
  showAcceptModal.value = true;
};

const openDeleteModal = (order) => {
  orderToDecline.value = order;
  activeActionMenu.value = null;
  showDeleteModal.value = true;
};

const closeActionMenu = () => {
  activeActionMenu.value = null;
};

const confirmAccept = () => {
  if (!orderToAccept.value) return;

  router.post(`/orders/${orderToAccept.value.id}/approve`, {}, {
    preserveScroll: true,
    onSuccess: () => {
      showAcceptModal.value = false;
      orderToAccept.value = null;
      activeActionMenu.value = null;
      if (toast.value) {
        toast.value.addToast('Order approved successfully', 'success');
      }
    },
    onError: (errors) => {
      showAcceptModal.value = false;
      if (errors.error && toast.value) {
        toast.value.addToast(errors.error, 'error');
      }
    },
  });
};

const confirmDelete = () => {
  if (!orderToDecline.value) return;

  router.post(`/orders/${orderToDecline.value.id}/decline`, {}, {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false;
      orderToDecline.value = null;
      activeActionMenu.value = null;
      if (toast.value) {
        toast.value.addToast('Order declined successfully', 'success');
      }
    },
    onError: (errors) => {
      showDeleteModal.value = false;
      if (errors.error && toast.value) {
        toast.value.addToast(errors.error, 'error');
      }
    },
  });
};

const closeModal = () => {
  showDeleteModal.value = false;
  showAcceptModal.value = false;
  orderToDecline.value = null;
  orderToAccept.value = null;
  activeActionMenu.value = null;
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
