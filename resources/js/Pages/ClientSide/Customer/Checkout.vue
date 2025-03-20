<template>
  <div class="min-h-screen bg-gray-50">
    <NavLink />

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Checkout Process</h1>
        <Link
          href="/cart"
          class="flex items-center text-sm text-navy-600 hover:text-navy-700"
        >
          <ChevronLeftIcon class="h-4 w-4 mr-1" />
          BACK TO CART
        </Link>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Delivery Information -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-start mb-4">
              <!-- Left Section -->
              <div class="flex items-start space-x-2">
                <MapPinIcon class="h-5 w-5 text-gray-500 mt-0.5" />
                <div>
                  <h2 class="font-semibold text-gray-900">Delivery Information</h2>
                  <div class="mt-2 text-sm text-gray-600">
                    <p class="font-semibold text-gray-900">
                      {{ customer.first_name }} {{ customer.last_name }}
                    </p>
                    <p class="text-gray-900">
                      {{ customer.phone }}
                    </p>
                    <p>
                      {{ customer.email }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Right Section (Addresses aligned to flex-end) -->

              <div class="text-right text-sm">
                <button
                  @click="showEditModal = true"
                  class="mt-2 mb-4 px-4 py-1 text-sm bg-navy-900 text-white rounded hover:bg-navy-800"
                >
                  Edit
                </button>
                <p class="text-gray-900">
                  {{ customer.address.complete_address }}
                </p>
                <p class="text-gray-900">
                  {{ customer.address.province }}, {{ customer.address.city }},
                  {{ customer.address.zip_code }}
                </p>
              </div>
            </div>
          </div>

          <!-- Delivery Method -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-start space-x-2 mb-4">
              <TruckIcon class="h-5 w-5 text-gray-500 mt-0.5" />
              <h2 class="font-semibold text-gray-900">Delivery Method</h2>
            </div>

            <div class="space-y-4">
              <label class="flex items-center space-x-3">
                <input
                  type="radio"
                  v-model="deliveryMethod"
                  value="delivery"
                  class="form-radio text-navy-600 focus:ring-navy-500"
                />
                <span>Lalamove Delivery</span>
              </label>

              <label class="flex items-center space-x-3">
                <input
                  type="radio"
                  v-model="deliveryMethod"
                  value="pickup"
                  class="form-radio text-navy-600 focus:ring-navy-500"
                />
                <span>In-Store Pickup</span>
              </label>

              <!-- Pickup Details -->
              <div v-if="deliveryMethod === 'pickup'" class="mt-4 space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Pickup Date
                  </label>
                  <div class="relative">
                    <input
                      type="date"
                      v-model="pickupDate"
                      class="w-full pl-3 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                    />
                    <CalendarIcon
                      class="absolute right-3 top-2.5 h-5 w-5 text-gray-400"
                    />
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Pickup Time
                  </label>
                  <div class="relative">
                    <input
                      type="time"
                      v-model="pickupTime"
                      class="w-full pl-3 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                    />
                    <ClockIcon class="absolute right-3 top-2.5 h-5 w-5 text-gray-400" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-start space-x-2 mb-4">
              <CreditCardIcon class="h-5 w-5 text-gray-500 mt-0.5" />
              <h2 class="font-semibold text-gray-900">Payment Method</h2>
            </div>

            <div class="space-y-4">
              <label class="flex items-center space-x-3">
                <input
                  type="radio"
                  v-model="paymentMethod"
                  value="gcash"
                  class="form-radio text-navy-600 focus:ring-navy-500"
                />
                <span>PayNow (PayMongo)</span>
              </label>

              <label
                v-if="deliveryMethod === 'pickup'"
                class="flex items-center space-x-3"
              >
                <input
                  type="radio"
                  v-model="paymentMethod"
                  value="cash"
                  class="form-radio text-navy-600 focus:ring-navy-500"
                />
                <span>Pay at Pickup</span>
              </label>
            </div>
          </div>

          <!-- Notes -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <label class="block text-sm font-medium text-gray-700 mb-2"> Notes </label>
            <textarea
              v-model="notes"
              rows="3"
              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
              placeholder="Add any special instructions..."
            ></textarea>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
            <h2 class="font-semibold text-gray-900 mb-4">Order Summary</h2>

            <div class="space-y-4">
              <div v-for="item in items" :key="item.id" class="flex space-x-4">
                <img
                  :src="getImageUrl(item.image)"
                  :alt="item.name"
                  class="w-16 h-16 object-cover rounded-lg"
                />
                <div class="flex-1">
                  <h3 class="text-sm font-medium text-gray-900">{{ item.name }}</h3>
                  <p class="text-sm text-gray-500">Qty: {{ item.quantity }}</p>
                  <p class="text-sm font-medium text-navy-600">₱{{ item.price }}</p>
                </div>
              </div>
            </div>

            <div class="border-t mt-6 pt-4 space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-medium">₱{{ summary.subtotal }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Delivery Fee</span>
                <span class="font-medium">₱{{ summary.shipping }}</span>
              </div>
              <div class="flex justify-between text-base font-semibold pt-2">
                <span>Total Amount</span>
                <span class="text-navy-600">₱{{ summary.total }}</span>
              </div>
            </div>

            <div class="mt-6 space-y-4">
              <label class="flex items-center space-x-2 text-sm">
                <input
                  type="checkbox"
                  v-model="agreeToTerms"
                  class="form-checkbox text-navy-600 focus:ring-navy-500"
                />
                <span class="text-gray-600">I agree to the Terms and Conditions</span>
              </label>

              <button
                @click="completePurchase"
                :disabled="!agreeToTerms"
                class="w-full py-3 bg-navy-900 text-white rounded-lg font-medium hover:bg-navy-800 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                COMPLETE PURCHASE
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Address Modal -->
    <TransitionRoot appear :show="showEditModal" as="template">
      <Dialog as="div" @close="showEditModal = false" class="relative z-50">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                class="w-full max-w-md transform overflow-hidden rounded-lg bg-white p-6 text-left align-middle shadow-xl transition-all"
              >
                <DialogTitle as="h3" class="text-lg font-medium text-gray-900 mb-4">
                  Edit Address
                </DialogTitle>

                <form @submit.prevent="updateAddress" class="space-y-4">
                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">
                        First Name
                      </label>
                      <input
                        v-model="customer.first_name"
                        type="text"
                        required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                      />
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">
                        Last Name
                      </label>
                      <input
                        v-model="customer.last_name"
                        type="text"
                        required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                      />
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Province
                    </label>
                    <select
                      v-model="customer.address.province"
                      required
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                    >
                      <option value="metro-manila">Metro Manila</option>
                      <!-- Add more provinces -->
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      City
                    </label>
                    <select
                      v-model="customer.address.city"
                      required
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                    >
                      <option value="caloocan">Caloocan City</option>
                      <!-- Add more cities -->
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Zip Code
                    </label>
                    <input
                      v-model="customer.address.zip_code"
                      type="text"
                      required
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Complete Address
                    </label>
                    <textarea
                      v-model="customer.address.complete_address"
                      rows="3"
                      required
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                    ></textarea>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Phone Number
                    </label>
                    <input
                      v-model="customer.phone"
                      type="tel"
                      required
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                    />
                  </div>

                  <div class="flex justify-end space-x-3 mt-6">
                    <button
                      type="button"
                      @click="showEditModal = false"
                      class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-50"
                    >
                      Cancel
                    </button>
                    <button
                      type="submit"
                      class="px-4 py-2 bg-navy-900 text-white rounded-lg hover:bg-navy-800"
                    >
                      Update
                    </button>
                  </div>
                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
    <!-- Payment Modal -->
    <div
      v-if="showPaymentModal"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
      <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Confirm Payment</h2>
        <p class="text-gray-700 mb-4">
          To proceed with your order, please confirm the payment.
        </p>

        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <span class="text-gray-600">Subtotal:</span>
            <span class="font-medium">₱{{ summary.subtotal }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-gray-600">Delivery Fee:</span>
            <span class="font-medium">₱{{ summary.shipping }}</span>
          </div>
          <div class="flex items-center justify-between text-lg font-semibold">
            <span>Total Amount:</span>
            <span class="text-navy-600">₱{{ summary.total }}</span>
          </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
          <button
            @click="showPaymentModal = false"
            class="px-4 py-2 bg-gray-200 rounded-lg text-gray-600 hover:bg-gray-300"
          >
            Cancel
          </button>
          <button
            @click="proceedToPayment"
            :disabled="isLoading"
            class="w-full bg-navy-600 text-white py-3 rounded-lg font-medium hover:bg-navy-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="isLoading">Processing...</span>
            <span v-else>Proceed to Payment</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionRoot,
  TransitionChild,
} from "@headlessui/vue";
import {
  ShoppingCartIcon,
  UserIcon,
  MapPinIcon,
  TruckIcon,
  CreditCardIcon,
  CalendarIcon,
  ClockIcon,
  ChevronLeftIcon,
} from "lucide-vue-next";
import NavLink from "../../../Components/NavLink.vue";

const props = defineProps({
  items: {
    type: Object,
    required: true,
  },
  customer: {
    type: Object,
    required: true,
  },
  summary: {
    type: Object,
    required: true,
  },
});

// Form state
const showEditModal = ref(false);

const getImageUrl = (imagePath) => {
  if (!imagePath) return "/storage/default.jpg";
  return imagePath.startsWith("http") ? imagePath : `/storage/${imagePath}`;
};

// Delivery and payment methods
const deliveryMethod = ref("delivery");
const paymentMethod = ref("gcash");
const pickupDate = ref("");
const pickupTime = ref("");
const notes = ref("");
const agreeToTerms = ref(false);
const showPaymentModal = ref(false);
const isLoading = ref(false);
// Cart items

// Computed values
const subtotal = computed(() => {
  return cartItems.value.reduce((total, item) => total + item.price * item.quantity, 0);
});

const deliveryFee = computed(() => {
  return deliveryMethod.value === "delivery" ? 14.0 : 0;
});

const total = computed(() => {
  return subtotal.value + deliveryFee.value;
});

// Methods

const updateAddress = () => {
  // Implement address update logic
  showEditModal.value = false;
};

const completePurchase = () => {
  if (!agreeToTerms.value) return;
  showPaymentModal.value = true;
};

// Proceed with the payment and order creation
const proceedToPayment = () => {
  if (paymentMethod.value === 'gcash') {
    isLoading.value = true;

    // Create the shipping address string using props.customer
    const shippingAddress = props.customer.address ?
      `${props.customer.address.complete_address}, ${props.customer.address.city}, ${props.customer.address.province}, ${props.customer.address.zip_code}` : '';

    // Make a GET request to our backend to create the payment source
    router.get(route('customer.payment'), {
      notes: notes.value,  // Add notes from the form
      shipping_address: shippingAddress  // Add formatted shipping address
    }, {
      preserveState: true,
      onSuccess: () => {
        isLoading.value = false;
        showPaymentModal.value = false;
      },
      onError: () => {
        isLoading.value = false;
        alert('Failed to initialize payment. Please try again.');
      }
    });
  }
};

watch(deliveryMethod, (newValue) => {
  router.get(route('customer.checkout'), {
    delivery_method: newValue
  }, {
    preserveState: true,
    preserveScroll: true
  });
});
</script>

<style scoped>
.bg-navy-800 {
  background-color: #0d1147;
}

.bg-navy-900 {
  background-color: #070b2e;
}

.text-navy-600 {
  color: #1a237e;
}

.hover\:bg-navy-800:hover {
  background-color: #0d1147;
}

.focus\:ring-navy-500:focus {
  --tw-ring-color: #1e2b8f;
}
</style>
