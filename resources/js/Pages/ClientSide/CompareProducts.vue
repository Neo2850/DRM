

<template>
    <div class="min-h-screen bg-gray-50">
      <NavLink />
      <AddToCartModal
  :is-open="showSuccessModal"
  :product="addedProduct"
  @close="showSuccessModal = false"
/>
      <!-- Main Content -->
      <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Compare Products</h1>

        <!-- Filter Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Category
            </label>
            <select
              v-model="selectedCategory"
              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
            >
              <option value="">Select a category</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>
        </div>

        <!-- Product Selection -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
          <!-- Product 1 -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Product 1
            </label>
            <select
              v-model="selectedProduct1"
              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
            >
              <option value="">Select a product</option>
              <option v-for="product in filteredProducts" :key="product.id" :value="product">
                {{ product.name }}
              </option>
            </select>

            <!-- Product 1 Details -->
            <div v-if="selectedProduct1" class="mt-4 bg-white p-4 rounded-lg shadow-sm">
              <img
                :src="selectedProduct1.image ? '/storage/' + selectedProduct1.image : '/storage/default.jpg'"
                :alt="selectedProduct1.name"
                class="w-full h-48 object-contain mb-4"
              />
              <h3 class="font-medium text-lg mb-2">{{ selectedProduct1.name }}</h3>
              <p class="text-gray-600">Brand: {{ selectedProduct1.brand }}</p>
              <p class="text-lg font-bold text-navy-600 mt-2">₱{{ formatPrice(selectedProduct1.price) }}</p>
              <button
                @click="addToCart(selectedProduct1)"
                class="mt-4 w-full bg-navy-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-navy-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500 transition-colors"
              >
                Add to Cart
              </button>
            </div>
          </div>

          <!-- Product 2 -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Product 2
            </label>
            <select
              v-model="selectedProduct2"
              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
            >
              <option value="">Select a product</option>
              <option v-for="product in filteredProducts" :key="product.id" :value="product">
                {{ product.name }}
              </option>
            </select>

            <!-- Product 2 Details -->
            <div v-if="selectedProduct2" class="mt-4 bg-white p-4 rounded-lg shadow-sm">
              <img
                :src="selectedProduct2.image ? '/storage/' + selectedProduct2.image : '/storage/default.jpg'"
                :alt="selectedProduct2.name"
                class="w-full h-48 object-contain mb-4"
              />
              <h3 class="font-medium text-lg mb-2">{{ selectedProduct2.name }}</h3>
              <p class="text-gray-600">Brand: {{ selectedProduct2.brand }}</p>
              <p class="text-lg font-bold text-navy-600 mt-2">₱{{ formatPrice(selectedProduct2.price) }}</p>
              <button
                @click="addToCart(selectedProduct2)"
                class="mt-4 w-full bg-navy-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-navy-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500 transition-colors"
              >
                Add to Cart
              </button>
            </div>
          </div>
        </div>

        <!-- Specifications Comparison -->
        <div v-if="selectedProduct1 && selectedProduct2" class="bg-white rounded-lg shadow-sm overflow-hidden">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-semibold text-gray-900">Specifications Comparison</h2>
              <div class="flex items-center space-x-2">
                <input
                  type="checkbox"
                  id="highlightDiff"
                  v-model="highlightDifferences"
                  class="rounded text-navy-600 focus:ring-navy-500"
                />
                <label for="highlightDiff" class="text-sm text-gray-600">
                  Highlight Differences
                </label>
              </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-4">
              <div class="font-medium text-gray-700">Specification</div>
              <div class="font-medium text-gray-700">{{ selectedProduct1.name }}</div>
              <div class="font-medium text-gray-700">{{ selectedProduct2.name }}</div>
            </div>

            <div class="space-y-4">
              <div
                v-for="(spec, key) in mergedSpecifications"
                :key="key"
                class="grid grid-cols-3 gap-4 py-3 border-b"
                :class="{
                  'bg-yellow-50': highlightDifferences && isDifferent(key)
                }"
              >
                <div class="font-medium text-gray-700">{{ key }}</div>
                <div
                  class="text-gray-900"
                  :class="{
                    'font-semibold text-navy-600': highlightDifferences && isDifferent(key)
                  }"
                >
                  {{ selectedProduct1.specifications[key] || '-' }}
                </div>
                <div
                  class="text-gray-900"
                  :class="{
                    'font-semibold text-navy-600': highlightDifferences && isDifferent(key)
                  }"
                >
                  {{ selectedProduct2.specifications[key] || '-' }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, computed, watch } from 'vue'
  import { router, Link } from '@inertiajs/vue3'
  import { XIcon } from 'lucide-vue-next'
  import NavLink from '../../Components/NavLink.vue';
  import AddToCartModal from '@/Components/AddToCartModal.vue';
  import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
  } from '@headlessui/vue';

  const props = defineProps({
    products: Array,
    categories: Array,
    brands: Array,
    preSelectedProduct1: Object,
    preSelectedProduct2: Object,
    selectedCategory: [String, Number],
  });

  const selectedCategory = ref(props.selectedCategory || '');
  const selectedProduct1 = ref(props.preSelectedProduct1 || null);
  const selectedProduct2 = ref(props.preSelectedProduct2 || null);

  const highlightDifferences = ref(false);

  const isDifferent = (key) => {
    const value1 = selectedProduct1.value.specifications[key];
    const value2 = selectedProduct2.value.specifications[key];
    return value1 !== value2;
  };

  // Filter products based on selected category
  const filteredProducts = computed(() => {
    if (!selectedCategory.value) return [];
    return props.products.filter(product =>
      product.category_id === parseInt(selectedCategory.value)
    );
  });

  // Merge specifications from both products for comparison
  const mergedSpecifications = computed(() => {
    if (!selectedProduct1.value || !selectedProduct2.value) return {};

    const specs1 = selectedProduct1.value.specifications || {};
    const specs2 = selectedProduct2.value.specifications || {};

    return Object.keys({ ...specs1, ...specs2 }).reduce((acc, key) => {
      acc[key] = null; // Just need the keys for rendering
      return acc;
    }, {});
  });

  const formatPrice = (price) => {
    return price.toLocaleString('en-PH', { minimumFractionDigits: 2 })
  }

  const showSuccessModal = ref(false);
  const addedProduct = ref(null);

  const addToCart = (product) => {
    router.post(
      route('cart.add'),
      {
        product_id: product.id,
        quantity: 1,
        price: product.price,
        name: product.name,
        image: product.image ? '/storage/' + product.image : '/storage/default.jpg'
      },
      {
        preserveScroll: true,
        onSuccess: () => {
          addedProduct.value = {
            name: product.name,
            price: product.price,
            image: product.image
          };
          showSuccessModal.value = true;
        },
      }
    );
  }
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

  .bg-yellow-50 {
    background-color: #fefce8;
  }

  /* Smooth transition for highlighting */
  .grid {
    transition: background-color 0.2s ease;
  }

  .bg-navy-100 {
    background-color: #e8eaf6;
  }

  .text-navy-900 {
    color: #0a0c1b;
  }

  .hover\:bg-navy-200:hover {
    background-color: #c5cae9;
  }
  </style>
