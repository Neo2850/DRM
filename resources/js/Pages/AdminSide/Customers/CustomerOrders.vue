<template>
  <div class="min-h-screen bg-gray-50">
    <Sidebar />

    <main class="lg:ml-64 min-h-screen">
      <Header title="Customer Details" />
      <div v-if="$page.props.flash.success" onMounted="$toast.success($page.props.flash.success)">
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Customer Info Card -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <div v-if="customer.first_name && customer.last_name">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <!-- Customer Profile Section -->
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-xl font-semibold text-white">
                                {{ customer.first_name[0] }}{{ customer.last_name[0] }}
                            </span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                {{ customer.first_name }} {{ customer.last_name }}
                            </h2>
                            <div class="flex items-center space-x-2 mt-1">
                                <MailIcon class="h-4 w-4 text-gray-400" />
                                <p class="text-gray-600">{{ customer.email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards - Improved visual design -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <div class="bg-gradient-to-br from-white to-indigo-50 p-6 rounded-xl shadow-sm border border-indigo-100">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-600">Total Orders</div>
                            <PackageIcon class="h-5 w-5 text-indigo-500" />
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mt-2">{{ customer.total_orders }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-white to-green-50 p-6 rounded-xl shadow-sm border border-green-100">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-600">Completed</div>
                            <CheckCircleIcon class="h-5 w-5 text-green-500" />
                        </div>
                        <div class="text-3xl font-bold text-green-600 mt-2">{{ customer.completed_orders }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-white to-red-50 p-6 rounded-xl shadow-sm border border-red-100">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-600">Canceled</div>
                            <XCircleIcon class="h-5 w-5 text-red-500" />
                        </div>
                        <div class="text-3xl font-bold text-red-600 mt-2">{{ customer.cancelled_orders }}</div>
                    </div>
                </div>
            </div>

            <div v-else>
                <p class="text-gray-600">Customer data is not available.</p>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="bg-white rounded-lg shadow">
          <!-- Order Tabs -->
          <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Order filters">
                <button
                    v-for="tab in tabs"
                    :key="tab.key"
                    @click="currentTab = tab.key"
                    :class="[
                        'py-4 px-1 relative font-medium text-sm whitespace-nowrap',
                        currentTab === tab.key
                            ? 'text-indigo-600 border-b-2 border-indigo-600'
                            : 'text-gray-500 hover:text-gray-700'
                    ]"
                >
                    {{ tab.name }}
                    <span
                        :class="[
                            'ml-2 py-0.5 px-2.5 rounded-full text-xs',
                            currentTab === tab.key
                                ? 'bg-indigo-100 text-indigo-600'
                                : 'bg-gray-100 text-gray-600'
                        ]"
                    >
                        {{ getOrderCount(tab.key) }}
                    </span>
                </button>
            </nav>
          </div>
          <!-- Empty State -->
          <div v-if="filteredOrders.length === 0" class="py-12">
            <div class="text-center">
              <ShoppingBagIcon class="mx-auto h-12 w-12 text-gray-400" />
              <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
              <p class="mt-1 text-sm text-gray-500">
                {{ currentTab === 'all' ? 'This customer has not placed any orders yet.' : `No ${currentTab} orders found.` }}
              </p>
            </div>
          </div>

          <!-- Orders List - Improved design -->
          <div class="divide-y divide-gray-200">
            <div v-for="order in filteredOrders"
                 :key="order.id"
                 class="bg-white hover:bg-gray-50 transition-colors duration-150">
              <div class="px-6 py-6">
                <!-- Order Summary -->
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                  <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                      <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                        <ShoppingBagIcon class="h-6 w-6 text-indigo-600" />
                      </div>
                    </div>
                    <div>
                      <h3 class="text-lg font-medium text-gray-900 uppercase">
                        Order #{{ order.reference_number }}
                      </h3>
                      <p class="text-sm text-gray-500">
                        Placed on {{ order.created }}
                      </p>
                    </div>
                  </div>

                  <div class="mt-4 md:mt-0 grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div class="flex flex-col">
                      <span class="text-sm font-medium text-gray-500">Total Amount</span>
                      <span class="mt-1 text-xl font-semibold text-gray-900">₱{{ order.total }}</span>
                    </div>
                    <div class="flex flex-col">
                      <span class="text-sm font-medium text-gray-500">Payment Status</span>
                      <span class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium"
                              :class="{
                                'bg-green-100 text-green-800 uppercase': order.payment === 'paid',
                                'bg-yellow-100 text-yellow-800 uppercase': order.payment === 'pending',
                                'bg-red-100 text-red-800 uppercase': order.payment === 'failed'
                              }">
                          {{ order.payment }}
                        </span>
                      </span>
                    </div>
                    <div class="flex flex-col col-span-2 md:col-span-1">
                      <span class="text-sm font-medium text-gray-500">Order Status</span>
                      <form @submit.prevent="updateStatus(order.id, order.status)" class="mt-1">
                        <select
                          v-model="order.status"
                          @change="updateStatus(order.id, $event.target.value)"
                          class="w-full rounded-md text-sm font-medium px-3 py-1.5 border-0 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150"
                          :class="getStatusClass(order.status)"
                          :disabled="order.status === 'pending' || order.status === 'cancelled'"
                        >
                          <option value="pending" disabled>Pending</option>
                          <option value="processing">Processing</option>
                          <option value="shipped">Shipped</option>
                          <option value="delivered">Delivered</option>
                          <option value="cancelled">Canceled</option>
                          <option value="returned">Returned</option>
                        </select>
                      </form>
                    </div>
                  </div>

                  <button
                    @click="toggleOrder(order.id)"
                    class="mt-4 md:mt-0 ml-4 p-2 rounded-md hover:bg-gray-100 transition-colors duration-150"
                  >
                    <ChevronDownIcon
                      class="h-5 w-5 text-gray-400 transition-transform duration-200"
                      :class="{ 'rotate-180': expandedOrders.includes(order.id) }"
                    />
                  </button>
                </div>
              </div>

              <!-- Order Details -->
              <div
                v-show="expandedOrders.includes(order.id)"
                class="border-t border-gray-100"
              >
                <div class="px-6 py-4">
                  <table class="min-w-full">
                    <thead>
                      <tr class="border-b border-gray-200">
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">QTY</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-3 py-3"></th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                      <tr v-for="(item, index) in order.items"
                          :key="index"
                          class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-3 py-4 text-sm text-gray-900 border-b">{{ item.id }}</td>
                        <td class="px-3 py-4 text-sm text-gray-500 border-b">{{ item.sku }}</td>
                        <td class="py-4 text-sm font-medium text-gray-900 border-b">{{ item.name }}</td>
                        <td class="px-3 py-4 text-sm text-gray-900 border-b">₱{{ item.price }}</td>
                        <td class="px-3 py-4 text-sm text-gray-900 border-b">x{{ item.quantity }}</td>
                        <td class="px-3 py-4 text-sm font-medium text-gray-900 border-b">₱{{ item.total }}</td>

                      </tr>
                    </tbody>
                    <tfoot class="bg-gray-50">
                      <tr class="border-t border-gray-200">
                        <td colspan="5" class="px-3 py-3 text-right text-sm font-medium text-gray-500 border-b">Subtotal</td>
                        <td class="px-3 py-3 text-sm font-medium text-gray-900 border-b">₱{{ order.subtotal }}</td>

                      </tr>
                      <tr>
                        <td colspan="5" class="px-3 py-3 text-right text-sm font-medium text-gray-500 border-b">Shipping</td>
                        <td class="px-3 py-3 text-sm font-medium text-gray-900 border-b">₱{{ order.shipping }}</td>

                      </tr>
                      <tr>
                        <td colspan="5" class="px-3 py-3 text-right text-sm font-bold text-gray-900">Total</td>
                        <td class="px-3 py-3 text-sm font-bold text-gray-900">₱{{ order.total }}</td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { ChevronDownIcon, MoreVerticalIcon, ShoppingBagIcon, MailIcon } from 'lucide-vue-next';
import Sidebar from '../../../Components/Sidebar.vue';
import Header from '../../../Components/Header.vue';
import { router } from '@inertiajs/vue3';
import Toast from '../../../Components/Toast.vue';

const props = defineProps({
  customer: {
    type: Object,
    required: false,
    default: () => ({}), // Default to an empty object
  },
  orders: {
    type: Array,
    required: false,
    default: () => [], // Default to an empty array
  },
});

const currentTab = ref('all');
const expandedOrders = ref([]);

const tabs = [
  { key: 'all', name: 'All Orders' },
  { key: 'delivered', name: 'Completed' },
  { key: 'cancelled', name: 'Canceled' }
];

const filteredOrders = computed(() => {
  if (currentTab.value === 'all') return props.orders;
  return props.orders.filter(order =>
    order.status?.toLowerCase() === currentTab.value
  );
});

const toggleOrder = (orderId) => {
  const index = expandedOrders.value.indexOf(orderId);
  if (index === -1) {
    expandedOrders.value.push(orderId);
  } else {
    expandedOrders.value.splice(index, 1);
  }
};

const getStatusClass = (status) => {
  const statusClasses = {
    'pending': 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200',
    'processing': 'bg-blue-100 text-blue-800 hover:bg-blue-200',
    'shipped': 'bg-indigo-100 text-indigo-800 hover:bg-indigo-200',
    'delivered': 'bg-green-100 text-green-800 hover:bg-green-200',
    'cancelled': 'bg-red-100 text-red-800 hover:bg-red-200',
    'returned': 'bg-gray-100 text-gray-800 hover:bg-gray-200'
  };
  return statusClasses[status?.toLowerCase()] || 'bg-gray-100 text-gray-800 hover:bg-gray-200';
};

const updateStatus = (orderId, newStatus) => {
  router.put(route('orders.update-status', orderId), {
    status: newStatus
  }, {
    preserveScroll: true,
    preserveState: true
  });
};

const getOrderCount = (tabKey) => {
  if (tabKey === 'all') {
    return props.orders.length;
  }

  const statusMap = {
    'delivered': 'delivered',
    'cancelled': 'cancelled'
  };

  return props.orders.filter(order =>
    order.status?.toLowerCase() === statusMap[tabKey]
  ).length;
};
</script>
