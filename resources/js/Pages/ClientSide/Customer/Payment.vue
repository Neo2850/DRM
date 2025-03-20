<template>
    <div class="min-h-screen bg-gray-50">
      <NavLink />

      <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
          <!-- Order Summary -->
          <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Order Summary</h2>
            <div class="border-t border-b py-4">
              <div class="text-sm text-gray-600">
                <p>Order Reference: <span class="font-bold uppercase">{{ order.reference_number }}</span></p>
                <p>Total Amount: <span class="font-bold">₱{{ order.total_amount }}</span></p>
              </div>
            </div>
          </div>

          <!-- Payment Instructions -->
          <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Payment Instructions</h3>
            <p class="text-gray-600 mb-4">
              Click the button below to proceed with your GCash payment.
            </p>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-between items-center">
            <Link
              href="/cart"
              class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800"
            >
              Close
            </Link>

            <button
              @click="openPaymentWindow"
              class="px-6 py-3 bg-navy-900 text-white rounded-lg font-medium hover:bg-navy-800 focus:outline-none focus:ring-2 focus:ring-navy-500 focus:ring-offset-2"
            >
              Proceed to GCash Payment
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { Link } from '@inertiajs/vue3';
  import NavLink from '../../../Components/NavLink.vue';

  const props = defineProps({
    checkoutUrl: {
      type: String,
      required: true
    },
    order: {
      type: Object,
      required: true
    }
  });

  const openPaymentWindow = () => {
    window.open(props.checkoutUrl, '_blank');
  };
  </script>

  <style scoped>
  .bg-navy-800 {
    background-color: #0d1147;
  }

  .bg-navy-900 {
    background-color: #070b2e;
  }

  .hover\:bg-navy-800:hover {
    background-color: #0d1147;
  }

  .focus\:ring-navy-500:focus {
    --tw-ring-color: #1e2b8f;
  }
  </style>
