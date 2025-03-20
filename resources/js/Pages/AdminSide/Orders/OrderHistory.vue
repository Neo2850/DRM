<template>
  <Head title=" | Order History" />
  <div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <Sidebar></Sidebar>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
      <!-- Header -->
      <Header title="Order History"></Header>

      <!-- Main Content Area -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Order History Header -->
        <div class="flex justify-between items-center mb-6">
          <div class="relative">
            <button
              @click="isFilterOpen = !isFilterOpen"
              class="flex items-center px-4 py-2 border rounded-md bg-white hover:bg-gray-50"
            >
              <FilterIcon class="h-5 w-5 mr-2" />
              Filter by
              <ChevronDownIcon class="ml-2 h-5 w-5" />
            </button>
          </div>
          <div class="relative">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Search"
              class="pl-10 pr-4 py-2 border rounded-lg w-64"
            />
            <SearchIcon class="h-5 w-5 text-gray-400 absolute left-3 top-2.5" />
          </div>
        </div>

        <!-- Orders Grid -->
        <div v-if="!showOrderDetails">
          <!-- No Orders Message -->
          <div v-if="!filteredOrders.length" class="text-center py-12">
            <div class="flex flex-col items-center justify-center">
              <ShoppingBag class="h-12 w-12 text-gray-400 mb-4" />
              <h3 class="text-lg font-medium text-gray-900">No valid orders yet</h3>
              <p class="mt-1 text-sm text-gray-500">Orders that are pending will appear here once validated.</p>
            </div>
          </div>

          <!-- Orders Grid -->
          <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="order in filteredOrders"
              :key="order.id"
              class="bg-white rounded-lg p-6 border border-gray-200"
            >
              <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-semibold uppercase">{{ order.reference_number }}</h3>
                <span
                  :class="[
                    'px-3 py-1 text-sm font-medium rounded-full',
                    order.order_status === 'delivered' ? 'bg-green-500 text-white' :
                    order.order_status === 'shipped' ? 'bg-blue-500 text-white' :
                    order.order_status === 'cancelled' ? 'bg-red-500 text-white' :
                    'bg-yellow-500 text-white'
                  ]"
                >
                  {{ order.order_status.charAt(0).toUpperCase() + order.order_status.slice(1) }}
                </span>
              </div>
              <p class="text-gray-600">{{ order.customer_name}}</p>
              <p class="text-gray-600">Date: {{ new Date(order.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) }}</p>
              <p class="font-semibold text-xl mt-2">Total: ₱{{ order.total_amount }}</p>
              <button
                @click="viewOrderDetails(order)"
                class="w-full mt-4 bg-navy-blue text-white py-2 rounded-lg hover:bg-navy-blue-dark"
              >
                View Details
              </button>


            </div>
          </div>
        </div>

        <!-- Order Details View -->
        <div v-else class="bg-white rounded-lg p-6 border border-gray-200">
          <!-- Back Button and Title -->
          <div class="flex items-center gap-2 mb-6">
            <button
              @click="showOrderDetails = false"
              class="flex items-center text-gray-600 hover:text-gray-900"
            >
              <ChevronLeftIcon class="h-5 w-5" />
              <span>Back to Order History</span>
            </button>
          </div>


          <!-- Order Details Header -->
          <div class="mb-8 ">
            <div class="flex justify-between items-start mb-4">
                <h1 class="text-lg font-semibold uppercase">{{ selectedOrder.reference_number }}</h1>
                <span
                  :class="[
                    'px-3 py-1 text-sm font-medium rounded-full',
                    selectedOrder.order_status === 'delivered' ? 'bg-green-500 text-white' :
                    selectedOrder.order_status === 'shipped' ? 'bg-blue-500 text-white' :
                    selectedOrder.order_status === 'cancelled' ? 'bg-red-500 text-white' :
                    'bg-yellow-500 text-white'
                  ]"
                >
                  {{ selectedOrder.order_status.charAt(0).toUpperCase() + selectedOrder.order_status.slice(1) }}
                </span>
              </div>
            <div class="flex justify-between">
              <div>
                <p class="text-gray-600">Customer Name:</p>
                <p class="font-medium">{{ selectedOrder.customer_name }}</p>
                <p class="text-gray-600 mt-4">Order Date:</p>
                <p class="font-medium">{{ selectedOrder.created_at }}</p>
              </div>
              <div class="text-right">
                <p class="text-gray-600">Total:</p>
                <p class="text-2xl font-semibold">₱{{ selectedOrder.total_amount }}</p>
              </div>
            </div>
          </div>

          <!-- Order Items Table -->
          <div class="mb-8 border border-gray-200 rounded-lg">
            <table class="w-full">
              <thead class="">
                <tr class="border-b">
                  <th class="text-left py-3 pl-10 text-gray-600">PRODUCT</th>
                  <th class="text-left py-3 text-gray-600">SKU</th>
                  <th class="text-left py-3 text-gray-600">QUANTITY</th>
                  <th class="text-left py-3 text-gray-600">PRICE</th>
                  <th class="text-left py-3 text-gray-600">TOTAL</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in selectedOrder.items" :key="item.sku" class="border-b">
                  <td class="py-4 pl-10">{{ item.product_name }}</td>
                  <td class="py-4">{{ item.sku }}</td>
                  <td class="py-4">{{ item.quantity }}</td>
                  <td class="py-4">₱{{ item.unit_amount }}</td>
                  <td class="py-4">₱{{ (item.quantity * item.unit_amount).toFixed(2) }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Shipping and Status Containers -->
          <div class="grid grid-cols-2 gap-6">
            <!-- Shipping Information -->
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
              <h3 class="font-semibold mb-4 flex items-center gap-2">Shipping Information <ShoppingBag class="h-5 w-5" /></h3>
              <div class="space-y-4">
                <div>
                  <p class="text-gray-600 mb-1">Address:</p>
                  <p class="font-medium">{{ selectedOrder.shipping_address }}</p>
                </div>
                <div>
                  <p class="text-gray-600 mb-1">Phone Number:</p>
                  <p class="font-medium">{{ selectedOrder.customer_phone_number }}</p>
                </div>
                <div>
                  <p class="text-gray-600 mb-1">Estimated Delivery:</p>
                  <p class="font-medium">{{ selectedOrder.estimatedDelivery }}</p>
                </div>
              </div>
            </div>

            <!-- Order Status -->
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
              <h3 class="font-semibold mb-4 flex items-center gap-2">Order Status</h3>
              <p class="font-medium flex items-center gap-2">
                <CircleCheckBig v-if="selectedOrder.order_status === 'delivered'" class="h-5 w-5 text-green-500" />
                <Truck v-else-if="selectedOrder.order_status === 'shipped'" class="h-5 w-5 text-blue-500" />
                <X v-else-if="selectedOrder.order_status === 'cancelled'" class="h-5 w-5 text-red-500" />
                <Loader2 v-else-if="selectedOrder.order_status === 'processing'" class="h-5 w-5 text-yellow-500 animate-spin" />
                {{ selectedOrder.order_status === 'completed' ? 'Delivered' : selectedOrder.order_status.charAt(0).toUpperCase() + selectedOrder.order_status.slice(1) }}
              </p>
              <p class="text-gray-600 mt-2">
                Your order has been {{ selectedOrder.order_status === 'completed' ? 'delivered' : selectedOrder.order_status }}
              </p>
            </div>
          </div>
        </div>
        <!-- Pagination Links -->
        <div v-if="filteredOrders.length && !showOrderDetails" class="flex items-center justify-between mt-6">
          <div class="flex items-center">
          </div>
          <div class="flex gap-2">
            <Link
              v-for="link in props.order.links"
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
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import Sidebar from '../../../Components/Sidebar.vue'
import Header from '../../../Components/Header.vue'
import {
  SearchIcon,
  FilterIcon,
  ChevronDownIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  ShoppingBag,
  CircleCheckBig,
  Truck,
  X,
  Loader2
} from 'lucide-vue-next'

const props = defineProps({
  order: Object,
})

const page = usePage()


// State
const searchQuery = ref('')
const filterStatus = ref('all')
const showOrderDetails = ref(false)
const selectedOrder = ref(null)
const showFilterMenu = ref(false)
const showProfileMenu = ref(false)

// Computed
const filteredOrders = computed(() => {
  return props.order.data.filter(order => {
    // Exclude pending orders
    if (order.order_status === 'pending') {
      return false
    }

    const matchesSearch = order.reference_number.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         order.customer_name.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesStatus = filterStatus.value === 'all' || order.order_status === filterStatus.value
    return matchesSearch && matchesStatus
  })
})

// Methods
const viewOrderDetails = (order) => {
  selectedOrder.value = order
  showOrderDetails.value = true
}
</script>

<style>
/* Add any additional styles here if needed */
.bg-navy-blue {
  background-color: #0A1E4C;
}
.bg-navy-blue-dark {
  background-color: #081738;
}
</style>
