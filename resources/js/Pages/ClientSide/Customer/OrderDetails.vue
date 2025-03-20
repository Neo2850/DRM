<template>
  <div class="min-h-screen bg-gray-50">
    <NavLink />
    <div class="container mx-auto px-4 py-8">
      <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar -->
        <ProfileSidebar :customer="customer" />

        <!-- Main Content -->
        <div class="flex-1">
          <!-- Header Section -->
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
              <Link
                :href="route('customer.myOrders')"
                class="flex items-center justify-center w-10 h-10 rounded-full bg-white shadow-sm hover:bg-gray-50"
              >
                <ChevronLeftIcon class="h-5 w-5 text-gray-600" />
              </Link>
              <h1 class="text-2xl font-bold text-gray-900">Order Details</h1>
            </div>
            <div
              class="px-4 py-2 rounded-full text-sm font-medium"
              :class="{
                'bg-yellow-50 text-yellow-700 border border-yellow-200': order.status === 'pending',
                'bg-blue-50 text-blue-700 border border-blue-200': order.status === 'processing',
                'bg-purple-50 text-purple-700 border border-purple-200': order.status === 'shipped',
                'bg-green-50 text-green-700 border border-green-200': order.status === 'delivered',
                'bg-red-50 text-red-700 border border-red-200': order.status === 'cancelled'
              }"
            >
              {{ order.status.charAt(0).toUpperCase() + order.status.slice(1) }}
            </div>
          </div>

          <!-- Order Summary Cards -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Order Info -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h2>
              <div class="space-y-3">
                <div>
                  <p class="text-sm text-gray-500">Order ID</p>
                  <p class="text-sm font-medium text-gray-900 uppercase">{{ order.reference_number }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Payment Method</p>
                  <p class="text-sm font-medium text-gray-900 uppercase">{{ order.payment_method }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Order Date</p>
                  <p class="text-sm font-medium text-gray-900">{{ order.order_date }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">E-invoice</p>
                  <div v-if="order.e_invoice.number">
                    <a
                      :href="order.e_invoice.download_url"
                      class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                      target="_blank"
                    >
                      Download E-Invoice
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- Shipping Info -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Shipping Details</h2>
              <div class="space-y-3">
                <div>
                  <p class="text-sm text-gray-500">Recipient Name</p>
                  <p class="text-sm font-medium text-gray-900">{{ order.shipping_info.name }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Phone Number</p>
                  <p class="text-sm font-medium text-gray-900">{{ order.shipping_info.phone }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Delivery Address</p>
                  <p class="text-sm font-medium text-gray-900">{{ order.shipping_info.address }}</p>
                </div>
              </div>
            </div>

            <!-- Payment Summary -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Summary</h2>
              <div class="space-y-3">
                <div class="flex justify-between">
                  <p class="text-sm text-gray-500">Subtotal</p>
                  <p class="text-sm font-medium text-gray-900">₱{{ formatPrice(order.subtotal) }}</p>
                </div>
                <div class="flex justify-between">
                  <p class="text-sm text-gray-500">Shipping Fee</p>
                  <p class="text-sm font-medium text-gray-900">₱{{ formatPrice(order.shipping_fee) }}</p>
                </div>
                <div class="pt-3 border-t">
                  <div class="flex justify-between">
                    <p class="text-sm font-semibold text-gray-900">Total</p>
                    <p class="text-sm font-semibold text-gray-900">₱{{ formatPrice(order.total_amount) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Order Items -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
              <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
            </div>
            <div class="divide-y divide-gray-200">
              <div v-for="item in order.items" :key="item.id" class="p-6">
                <div class="flex items-start gap-4">
                  <img
                    :src="item.image ? '/storage/' + item.image : '/storage/default.jpg'"
                    :alt="item.name"
                    class="w-20 h-20 rounded-lg object-cover bg-gray-100"
                  />
                  <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-medium text-gray-900">{{ item.name }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ item.brand }} | {{ item.category }}</p>
                    <p class="mt-1 text-sm text-gray-500">SKU: {{ item.sku }}</p>
                  </div>
                  <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">₱{{ formatPrice(item.unit_price) }}</p>
                    <p class="mt-1 text-sm text-gray-500">Qty: {{ item.quantity }}</p>
                    <p class="mt-1 text-sm font-medium text-gray-900">Total: ₱{{ formatPrice(item.total) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ChevronLeftIcon, FileText } from 'lucide-vue-next';
import NavLink from '../../../Components/NavLink.vue';
import ProfileSidebar from '../../../Components/ProfileSidebar.vue';

const props = defineProps({
  order: Object,
  customer: Object,
});

const formatPrice = (price) => {
  return Number(price).toLocaleString('en-PH', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
};
</script>
