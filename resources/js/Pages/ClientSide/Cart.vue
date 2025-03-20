<template>
  <div class="min-h-screen bg-gray-50">
    <NavLink />
    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-2xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

      <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <!-- Cart Items Section -->
        <div class="lg:col-span-8">
          <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <!-- Empty Cart State -->
            <div v-if="!cartItemsArray.length" class="p-8 text-center">
              <div class="w-16 h-16 mx-auto mb-4">
                <ShoppingCart class="w-full h-full text-gray-400" />
              </div>
              <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
              <p class="text-gray-500 mb-4">
                Looks like you haven't added any items yet.
              </p>
              <Link
                href="/product-list"
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-navy-600 hover:bg-navy-700"
              >
                Continue Shopping
              </Link>
            </div>

            <!-- Cart Items -->
            <template v-else>
              <div
                v-for="item in cartItemsArray"
                :key="item[0]"
                class="p-6 flex items-center border-b last:border-0"
              >
                <!-- Add Selection Checkbox -->
                <div class="flex-shrink-0 mr-4">
                  <input
                    type="checkbox"
                    :checked="item[5]"
                    @change="updateSelection(item)"
                    class="w-5 h-5 rounded border-gray-300 text-navy-600 focus:ring-navy-500 cursor-pointer"
                  />
                </div>

                <!-- Product Image & Details -->
                <div class="flex-shrink-0 mr-6">
                  <img
                    :src="getImageUrl(item[4])"
                    :alt="item[1]"
                    class="w-24 h-24 object-cover rounded-lg"
                    @error="(e) => (e.target.src = '/storage/default.jpg')"
                  />
                </div>

                <div class="flex-grow">
                  <div class="flex justify-between mb-2">
                    <h3 class="text-lg font-medium text-gray-900">{{ item[1] }}</h3>
                    <p class="text-lg font-medium text-gray-900">
                      ₱{{ formatPrice(item[2] * item[3]) }}
                    </p>
                  </div>

                  <div class="flex items-center justify-between">
                    <!-- Quantity Controls -->
                    <div class="flex items-center space-x-3">
                      <button
                        @click="decrementQuantity(item)"
                        class="w-8 h-8 rounded-full flex items-center justify-center border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors"
                        :disabled="item[3] <= 1"
                      >
                        <Minus class="w-4 h-4" />
                      </button>

                      <span class="w-12 text-center">{{ item[3] }}</span>

                      <button
                        @click="incrementQuantity(item)"
                        class="w-8 h-8 rounded-full flex items-center justify-center border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors"
                      >
                        <Plus class="w-4 h-4" />
                      </button>
                    </div>

                    <!-- Remove Button -->
                    <button
                      @click="removeItem(item)"
                      class="text-sm text-red-600 hover:text-red-700 flex items-center"
                    >
                      <Trash2 class="w-4 h-4 mr-1" />
                      Remove
                    </button>
                  </div>
                </div>
              </div>

              <!-- Add Select All Option -->
              <div class="p-4 border-t">
                <label class="flex items-center space-x-2 cursor-pointer">
                  <input
                    type="checkbox"
                    :checked="selectAll"
                    @change="toggleSelectAll"
                    class="w-5 h-5 rounded border-gray-300 text-navy-600 focus:ring-navy-500"
                  />
                  <span class="text-sm text-gray-600">Select All Items</span>
                </label>
              </div>
            </template>
          </div>
        </div>

        <!-- Order Summary Section -->
        <div class="lg:col-span-4">
          <div class="bg-white rounded-xl shadow-sm p-6 sticky top-4">
            <h2 class="text-lg font-medium text-gray-900 mb-6">Order Summary</h2>

            <div class="space-y-4">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal</span>
                <span class="text-gray-900 font-medium"
                  >₱{{ formatPrice(subtotal) }}</span
                >
              </div>

              <div class="flex justify-between text-sm">
              </div>

              <div class="border-t pt-4">
                <div class="flex justify-between">
                  <span class="text-base font-medium text-gray-900">Total</span>
                  <span class="text-xl font-bold text-navy-600"
                    >₱{{ formatPrice(total) }}</span
                  >
                </div>
              </div>

              <button
                @click="checkout"
                class="w-full bg-navy-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-navy-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500 transition-colors"
                :disabled="!cartItemsArray.some((item) => item[5])"
              >
                Proceed to Checkout ({{ cartItemsArray.filter((item) => item[5]).length }}
                items)
              </button>

              <Link
                href="/products"
                class="block w-full text-center text-sm text-gray-600 hover:text-gray-900"
              >
                Continue Shopping
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { ShoppingCart, Plus, Minus, Trash2 } from "lucide-vue-next";
import debounce from "lodash/debounce";

import NavLink from "../../Components/NavLink.vue";
// Navigation

const props = defineProps({
  cart: {
    type: Object,
    default: () => ({}),
  },
});

// Local state management
const cartItemsArray = computed(() => {
  return Object.values(props.cart || {});
});

const selectAll = ref(false);
const pendingUpdates = ref(new Set());

// Debounced server update function
const updateServer = debounce((updates) => {
  if (updates.type === "quantity") {
    router.post(
      route("cart.updateQuantity"),
      {
        product_id: updates.productId,
        quantity: updates.value,
      },
      {
        preserveScroll: true,
        preserveState: true,
        progress: false,
      }
    );
  } else if (updates.type === "selection") {
    router.post(
      route("cart.updateSelection"),
      {
        product_id: updates.productId,
        selected: updates.value,
      },
      {
        preserveScroll: true,
        preserveState: true,
        progress: false,
      }
    );
  }
}, 500);

// Optimistic updates with debounced server sync
const incrementQuantity = (item) => {
  item[3]++;
  updateServer({ type: "quantity", productId: item[0], value: item[3] });
};

const decrementQuantity = (item) => {
  if (item[3] > 1) {
    item[3]--;
    updateServer({ type: "quantity", productId: item[0], value: item[3] });
  }
};

const updateSelection = (item) => {
  const newValue = !item[5];
  // Update the local state first (optimistic update)
  item[5] = newValue;

  // Send update to server
  router.post(
    route("cart.updateSelection"),
    {
      product_id: item[0],
      selected: newValue,
    },
    {
      preserveScroll: true,
      preserveState: true,
      progress: false,
    }
  );
};

const toggleSelectAll = () => {
  const newValue = !selectAll.value;
  // Update local state first
  cartItemsArray.value.forEach(item => {
    item[5] = newValue;
  });

  // Send update to server
  router.post(
    route("cart.updateAllSelections"),
    {
      selected: newValue,
    },
    {
      preserveScroll: true,
      preserveState: true,
      progress: false,
    }
  );
};

// Watch for changes in cart items to update selectAll state
watch(
  cartItemsArray,
  (items) => {
    selectAll.value = items.length > 0 && items.every(item => item[5]);
  },
  { immediate: true, deep: true }
);

const removeItem = (item) => {
  // Optimistic update
  const index = cartItemsArray.value.findIndex((i) => i[0] === item[0]);
  if (index !== -1) {
    cartItemsArray.value.splice(index, 1);
  }

  router.post(
    route("cart.remove"),
    {
      product_id: item[0],
    },
    {
      preserveScroll: true,
      preserveState: true,
      progress: false,
    }
  );
};

// Computed values with memoization
const subtotal = computed(() => {
  return cartItemsArray.value.reduce((total, item) => {
    return total + (item[5] ? item[2] * item[3] : 0);
  }, 0);
});

const shippingFee = computed(() => {
  return subtotal.value > 0;
});

const total = computed(() => {
  return subtotal.value + shippingFee.value;
});

// Methods
const formatPrice = (price) => {
  return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

const checkout = () => {
  // Check if there are any selected items
  if (!cartItemsArray.value.some(item => item[5])) {
    alert('Please select at least one item to checkout');
    return;
  }

  router.get(route('customer.checkout'));
};

const getImageUrl = (imagePath) => {
  if (!imagePath) return "/storage/default.jpg";

  // If it's already a full URL, return as is
  if (imagePath.startsWith("http")) {
    return imagePath;
  }

  // Remove any duplicate '/storage/' prefixes
  const cleanPath = imagePath.replace(/^\/storage\/+/, "");

  // Handle different path formats
  if (cleanPath.startsWith("product_images/")) {
    return `/storage/${cleanPath}`;
  }

  // For paths that might already include storage
  if (cleanPath.includes("storage/")) {
    return `/${cleanPath}`;
  }

  // Default case: prepend /storage/
  return `/storage/${cleanPath}`;
};
</script>

<style scoped>
.bg-navy-600 {
  background-color: #1a237e;
}

.bg-navy-700 {
  background-color: #151b60;
}

.text-navy-600 {
  color: #1a237e;
}

.hover\:bg-navy-700:hover {
  background-color: #151b60;
}

.focus\:ring-navy-500:focus {
  --tw-ring-color: #1e2b8f;
}

/* Updated minimalist checkbox styles */
input[type="checkbox"] {
  appearance: none;
  -webkit-appearance: none;
  width: 1rem;
  height: 1rem;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  cursor: pointer;
  position: relative;
  transition: all 0.2s ease;
  background-color: white;
}

input[type="checkbox"]:checked {
  background-color: #1a237e;
  border-color: #1a237e;
}

input[type="checkbox"]:checked::after {
  content: "";
  position: absolute;
  left: 4px;
  top: 1px;
  width: 6px;
  height: 10px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

input[type="checkbox"]:hover {
  border-color: #1a237e;
}
</style>
