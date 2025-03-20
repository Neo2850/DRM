<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <NavLink />

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
      <!-- Product Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div class="space-y-3">
          <!-- Main Image Carousel -->
          <div
            class="relative aspect-[4/3] bg-white rounded-lg overflow-hidden max-w-lg mx-auto border border-gray-200"
          >
            <!-- Previous Button -->
            <button
              @click="previousImage"
              class="absolute left-2 top-1/2 transform -translate-y-1/2 button-primary rounded-full p-1.5 shadow-lg z-10 transition-colors"
            >
              <ChevronLeftIcon class="h-5 w-5" />
            </button>

            <!-- Images -->
            <div class="h-full flex items-center justify-center">
              <transition-group tag="div" class="h-full w-full" :name="slideDirection">
                <img
                  v-for="(image, index) in product.product_images"
                  :key="image"
                  v-show="currentImageIndex === index"
                  :src="'/storage/' + image"
                  :alt="product.name"
                  class="object-contain w-full h-full p-4"
                />
              </transition-group>
            </div>

            <!-- Next Button -->
            <button
              @click="nextImage"
              class="absolute right-2 top-1/2 transform -translate-y-1/2 button-primary rounded-full p-1.5 shadow-lg z-10 transition-colors"
            >
              <ChevronRightIcon class="h-5 w-5" />
            </button>
          </div>

          <!-- Thumbnail Gallery -->
          <div
            class="max-w-lg mx-auto grid gap-0"
            :class="{
              'grid-cols-2': product.product_images.length <= 2,
              'grid-cols-3': product.product_images.length === 3,
              'grid-cols-4': product.product_images.length === 4,
              'grid-cols-5': product.product_images.length === 5,
              'grid-cols-6': product.product_images.length >= 6,
            }"
          >
            <button
              v-for="(image, index) in product.product_images"
              :key="index"
              @click="currentImageIndex = index"
              class="aspect-[4/3] bg-white overflow-hidden border"
              :class="currentImageIndex === index ? 'border-navy-600' : 'border-gray-200'"
            >
              <div class="w-full h-full flex items-center justify-center p-2">
                <img
                  :src="'/storage/' + image"
                  :alt="`${product.name} thumbnail ${index + 1}`"
                  class="max-w-full max-h-full object-contain"
                />
              </div>
            </button>
          </div>
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
          <h1 class="text-3xl font-bold text-gray-900">{{ product.name }}</h1>

          <!-- Rating -->
          <div class="flex items-center space-x-2">
            <div class="flex">
              <StarIcon
                v-for="i in 5"
                :key="i"
                :class="i <= product.rating ? 'text-yellow-400' : 'text-gray-300'"
                class="h-5 w-5"
              />
            </div>
            <span class="text-sm text-gray-600"
              >{{ product.reviewCount }} customer reviews</span
            >
          </div>

          <!-- Description and Quick Specs -->
          <div class="space-y-4">
            <!-- Description -->
            <p class="text-gray-600">{{ product.description }}</p>

            <!-- Quick Specifications -->
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900 mb-3">
                Quick Specifications
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                  <!-- Left Column -->
                  <div class="flex items-start">
                    <span class="text-sm font-semibold text-gray-500 w-40"
                      >Category:</span
                    >
                    <span class="text-sm font-medium text-gray-900">{{
                      product.category.name
                    }}</span>
                  </div>
                  <div class="flex items-start">
                    <span class="text-sm font-semibold text-gray-500 w-40">Brand:</span>
                    <span class="text-sm font-medium text-gray-900">{{
                      product.brand.name
                    }}</span>
                  </div>
                  <div class="flex items-start">
                    <span class="text-sm font-semibold text-gray-500 w-40">Stock:</span>
                    <span class="text-sm font-medium text-gray-900"
                      >{{ product.stock }} units
                    </span>
                  </div>
                  <div class="flex items-start">
                    <span class="text-sm font-semibold text-gray-500 w-40"
                      >Warranty:</span
                    >
                    <span class="text-sm font-medium text-gray-900">{{
                      product.warranty
                    }}</span>
                  </div>
                </div>
                <div class="space-y-2">
                  <!-- Right Column -->
                  <div class="flex items-start">
                    <span class="text-sm font-semibold text-gray-500 w-40"
                      >{{ product.specifications[0].name }}:</span
                    >
                    <span class="text-sm font-medium text-gray-900">{{
                      product.specifications[0].value
                    }}</span>
                  </div>
                  <div class="flex items-start">
                    <span class="text-sm font-semibold text-gray-500 w-40"
                      >{{ product.specifications[1].name }}:</span
                    >
                    <span class="text-sm font-medium text-gray-900">{{
                      product.specifications[1].value
                    }}</span>
                  </div>
                  <div class="flex items-start">
                    <span class="text-sm font-semibold text-gray-500 w-40"
                      >{{ product.specifications[2].name }}:</span
                    >
                    <span class="text-sm font-medium text-gray-900">{{
                      product.specifications[2].value
                    }}</span>
                  </div>
                  <div class="flex items-start">
                    <span class="text-sm font-semibold text-gray-500 w-40"
                      >{{ product.specifications[3].name }}:</span
                    >
                    <span class="text-sm font-medium text-gray-900">{{
                      product.specifications[3].value
                    }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Price and Add to Cart -->
          <div class="space-y-4">
            <!-- Price and Quantity -->
            <div class="flex items-center justify-between">
              <span class="text-3xl font-bold text-gray-900">₱{{ product.price }}</span>

              <!-- Quantity Selector -->
              <div class="flex items-center border rounded-lg bg-white shadow-sm">
                <button
                  @click="quantity > 1 && quantity--"
                  class="px-4 py-2 text-gray-600 hover:text-navy-600 transition-colors"
                >
                  -
                </button>
                <input
                  type="number"
                  v-model="quantity"
                  class="w-16 text-center border-x py-2 focus:outline-none focus:ring-1 focus:ring-navy-500"
                />
                <button
                  @click="quantity++"
                  class="px-4 py-2 text-gray-600 hover:text-navy-600 transition-colors"
                >
                  +
                </button>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
              <!-- Wishlist Button -->
              <button
                class="flex-none w-20 h-12 flex items-center justify-center rounded-lg border border-gray-300 hover:bg-gray-200 transition-colors"
              >
                <HeartIcon class="h-5 w-5 text-gray-600 hover:text-red-500" />
              </button>

              <!-- Compare Button -->
              <button
                @click="toggleProductForComparison(product)"
                class="flex-none w-20 h-12 flex items-center justify-center rounded-lg border border-gray-300 hover:bg-gray-200 transition-colors"
                :class="{ 'bg-navy-100 border-navy-600': isSelectedForComparison(product.id) }"
              >
                <ArrowUpDown
                  class="h-5 w-5"
                  :class="isSelectedForComparison(product.id) ? 'text-navy-600' : 'text-gray-600'"
                />
              </button>

              <!-- Add to Cart Button -->
              <button
                @click="addToCart"
                class="flex-1 text-white h-12 rounded-lg hover:bg-navy-700 transition-colors duration-200 flex items-center justify-center space-x-2 button-primary"
              >
                <ShoppingCartIcon class="h-5 w-5" />
                <span>ADD TO CART</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Reviews Section -->
      <div class="mt-16">
        <!-- Tabs -->
        <div class="border-b border-gray-200">
          <nav class="flex space-x-8">
            <button
              @click="activeTab = 'overview'"
              :class="
                activeTab === 'overview'
                  ? 'border-navy-600 text-navy-600'
                  : 'border-transparent text-gray-500'
              "
              class="py-4 px-1 border-b-2 font-medium text-sm"
            >
              Overview
            </button>
            <button
              @click="activeTab = 'reviews'"
              :class="
                activeTab === 'reviews'
                  ? 'border-navy-600 text-navy-600'
                  : 'border-transparent text-gray-500'
              "
              class="py-4 px-1 border-b-2 font-medium text-sm"
            >
              Reviews({{ product.reviewCount }})
            </button>
          </nav>
        </div>

        <!-- Tab Content -->
        <div class="py-8">
          <!-- Overview Tab -->
          <div v-if="activeTab === 'overview'" class="space-y-8">
            <div>
              <h2 class="text-xl font-semibold mb-4">{{ product.name }}</h2>
              <h3 class="text-lg font-medium mb-3">Product Description</h3>
              <p class="text-gray-600">{{ product.description }}</p>
              <button
                v-if="isCollapsed"
                @click="isCollapsed = !isCollapsed"
                class="text-navy-600 font-medium mt-4 hover:text-navy-700 transition-colors"
              >
                VIEW MORE
              </button>
            </div>

            <!-- Specifications section that shows/hides based on isCollapsed -->
            <div v-show="!isCollapsed">
              <h3 class="text-lg font-medium mb-3">Product Specifications</h3>
              <ul class="space-y-2 text-gray-600">
                <li v-for="(spec, index) in product.specifications" :key="spec.name">
                  {{ spec.name }} : {{ spec.value }}
                </li>
              </ul>
              <button
                @click="isCollapsed = !isCollapsed"
                class="text-navy-600 font-medium mt-4 hover:text-navy-700 transition-colors"
              >
                VIEW LESS
              </button>
            </div>
          </div>

          <!-- Reviews Tab -->
          <div v-if="activeTab === 'reviews'" class="space-y-8">
            <!-- Rating Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div>
                <div class="flex items-center space-x-2">
                  <div class="flex">
                    <StarIcon
                      v-for="i in 5"
                      :key="i"
                      :class="i <= product.rating ? 'text-yellow-400' : 'text-gray-300'"
                      class="h-6 w-6"
                    />
                  </div>
                  <span class="text-2xl font-bold">{{ product.rating.toFixed(1) }}</span>
                </div>
                <p class="text-sm text-gray-500 mt-1">
                  Based on {{ product.reviewCount }} reviews
                </p>
              </div>

              <!-- Rating Distribution -->
              <div class="space-y-2">
                <div v-for="i in 5" :key="i" class="flex items-center">
                  <span class="text-sm text-gray-600 w-8">{{ 6 - i }}★</span>
                  <div class="flex-1 h-2 bg-gray-200 rounded-full mx-2">
                    <div
                      class="h-2 bg-yellow-400 rounded-full"
                      :style="`width: ${getRatingPercentage(6 - i)}%`"
                    ></div>
                  </div>
                  <span class="text-sm text-gray-600 w-8">{{
                    getRatingCount(6 - i)
                  }}</span>
                </div>
              </div>
            </div>

            <!-- Review List -->
            <div class="space-y-8">
              <div
                v-for="review in product.reviews"
                :key="review.id"
                class="border-b pb-8"
              >
                <div class="flex items-center space-x-4">
                  <div class="flex">
                    <StarIcon
                      v-for="i in 5"
                      :key="i"
                      :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-300'"
                      class="h-5 w-5"
                    />
                  </div>
                  <div class="flex items-center space-x-2">
                    <span class="font-medium">{{ review.author }}</span>
                    <span class="text-gray-500">{{ review.date }}</span>
                  </div>
                </div>
                <p class="mt-4 text-gray-600">{{ review.content }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Similar Products -->
      <div class="mt-16">
        <h2 class="text-2xl font-bold mb-8">Similar Items</h2>
        <div class="relative overflow-hidden">
          <!-- Carousel Wrapper -->
          <div
            class="flex transition-transform duration-300"
            :style="{ transform: `translateX(-${currentSlide * (100 / visibleCards)}%)` }"
            ref="productCarousel"
          >
            <div
              v-for="product in similarProducts"
              :key="product.id"
              class="flex-none px-2"
              :class="{
                'w-1/2': visibleCards === 2,
                'w-1/3': visibleCards === 3,
                'w-1/4': visibleCards === 4,
                'w-1/5': visibleCards === 5,
              }"
            >
              <Link
                :href="route('product.view', { slug: product.slug })"
                class=" block bg-white rounded-lg p-2 sm:p-3 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-200 hover:border-blue-500"
              >
                <!-- Brand Name -->
                <span
                  class="text-end text-xs sm:text-sm font-semibold text-black uppercase mb-1 sm:mb-2 block"
                >
                  {{ product.brand }}
                </span>

                <!-- Product Image -->
                <div
                  class="flex justify-center items-center mb-2 sm:mb-3 aspect-w-1 aspect-h-1"
                >
                  <img
                    :src="
                      product.image ? '/storage/' + product.image : 'storage/default.jpg'
                    "
                    :alt="product.name"
                    class="w-full h-28 sm:h-32 md:h-36 lg:h-40 object-contain rounded-lg"
                  />
                </div>

                <!-- Product Name -->
                <h3
                  class="primary-color text-sm sm:text-base font-medium mb-1 sm:mb-2 truncate whitespace-nowrap overflow-hidden"
                >
                  {{ product.name }}
                </h3>

                <div class="flex items-center justify-between">
                  <!-- Product Details -->
                  <div>
                    <div class="flex items-center mb-1">
                      <StarIcon
                        v-for="n in 5"
                        :key="n"
                        class="h-3 w-3 sm:h-4 sm:w-4"
                        :class="
                          n <= product.rating
                            ? 'text-yellow-400 fill-current'
                            : 'text-gray-400'
                        "
                      />
                      <span class="text-gray-400 text-xs sm:text-sm ml-1 sm:ml-2">
                        ({{ product.reviewCount }})
                      </span>
                    </div>
                    <span class="primary-color font-bold text-sm sm:text-base lg:text-lg">
                      ₱{{ product.price }}
                    </span>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex space-x-1 sm:space-x-2">
                    <button
                      @click.prevent="toggleWishlist(product.id)"
                      class="p-1.5 sm:p-1.5 primary-color bg-[#e2e8f0] rounded-lg"
                    >
                      <HeartIcon class="h-4 w-4 sm:h-5 sm:w-5 hover:text-red-500" />
                    </button>
                    <button
                      @click.prevent="toggleProductForComparison(product)"
                      class="p-1.5 sm:p-1.5 primary-color bg-[#e2e8f0] rounded-lg"
                      :class="{ 'bg-navy-100 border-navy-600': isSelectedForComparison(product.id) }"
                    >
                      <ArrowUpDown
                        class="h-4 w-4 sm:h-5 sm:w-5"
                        :class="isSelectedForComparison(product.id) ? 'text-navy-600' : 'text-gray-600'"
                      />
                    </button>
                    <button
                      @click.prevent="addSimilarToCart(product)"
                      class="p-1.5 sm:p-1.5 button-primary rounded-lg"
                    >
                      <ShoppingCartIcon class="h-4 w-4 sm:h-5 sm:w-5" />
                    </button>
                  </div>
                </div>
              </Link>
            </div>
          </div>

          <!-- Navigation Buttons -->
          <button
            @click="previousSlide"
            class="absolute left-0 top-1/2 -translate-y-1/2 bg-navy-800 p-2 rounded-full hover:bg-navy-700 transition sm:p-1 lg:p-2"
          >
            <ChevronLeftIcon class="h-6 w-6 text-white" />
          </button>
          <button
            @click="nextSlide"
            class="absolute right-0 top-1/2 -translate-y-1/2 bg-navy-800 p-2 rounded-full hover:bg-navy-700 transition sm:p-1 lg:p-2"
          >
            <ChevronRightIcon class="h-6 w-6 text-white" />
          </button>
        </div>
      </div>
    </main>
    <Footer />
    <AddToCartModal
      :is-open="showSuccessModal"
      :product="addedProduct"
      @close="showSuccessModal = false"
    />
    <div
      v-if="showCompareReminder"
      class="fixed top-4 right-4 bg-white p-4 rounded-lg shadow-lg z-50 border border-navy-600 max-w-sm"
    >
      <div class="flex items-start">
        <div class="flex-1">
          <h3 class="text-sm font-medium text-gray-900">Select a product to compare</h3>
          <p class="mt-1 text-sm text-gray-500">
            Click the compare icon on any similar product to compare it with {{ product.name }}
          </p>
        </div>
        <button
          @click="showCompareReminder = false"
          class="ml-4 text-gray-400 hover:text-gray-500"
        >
          <XIcon class="h-5 w-5" />
        </button>
      </div>
    </div>
    <div
      v-if="selectedProducts.length > 0"
      class="fixed bottom-4 right-4 z-50"
    >
      <div class="bg-white rounded-lg shadow-lg p-4 border border-navy-600">
        <div class="mb-3">
          <h3 class="text-sm font-medium text-gray-900">Selected for comparison ({{ selectedProducts.length }}/2)</h3>
          <div class="mt-2 space-y-2">
            <div v-for="prod in selectedProducts" :key="prod.id" class="flex items-center space-x-2">
              <span class="text-sm text-gray-600">{{ prod.name }}</span>
              <button @click="toggleProductForComparison(prod)" class="text-gray-400 hover:text-red-500">
                <XIcon class="h-4 w-4" />
              </button>
            </div>
          </div>
        </div>
        <div class="flex justify-between">
          <button
            @click="clearSelectedProducts"
            class="text-sm text-gray-600 hover:text-gray-900"
          >
            Clear all
          </button>
          <button
            v-if="selectedProducts.length === 2"
            @click="compareSelectedProducts"
            class="bg-navy-600 text-white px-4 py-2 rounded-lg hover:bg-navy-700"
          >
            Compare
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { Link, router } from "@inertiajs/vue3";
import {
  SearchIcon,
  ShoppingCartIcon,
  StarIcon,
  HeartIcon,
  ArrowUpDown,
  ChevronLeftIcon,
  ChevronRightIcon,
  XIcon,
} from "lucide-vue-next";
import NavLink from "../../Components/NavLink.vue";
import Footer from "../../Components/Footer.vue";
import AddToCartModal from "../../Components/AddToCartModal.vue";
const props = defineProps({
  product: Object,
  similarProducts: Object,
});

// Image carousel state
const currentImageIndex = ref(0);
const slideDirection = ref("slide-right");

// Image carousel methods
const nextImage = () => {
  slideDirection.value = "slide-right";
  if (currentImageIndex.value < props.product.product_images.length - 1) {
    currentImageIndex.value++;
  } else {
    // Loop back to first image
    currentImageIndex.value = 0;
  }
};

const previousImage = () => {
  slideDirection.value = "slide-left";
  if (currentImageIndex.value > 0) {
    currentImageIndex.value--;
  } else {
    // Loop to last image
    currentImageIndex.value = props.product.product_images.length - 1;
  }
};

// Reviews tab
const activeTab = ref("overview");

// Quantity selector
const quantity = ref(1);

// Similar products carousel
const currentSlide = ref(0);
const visibleCards = ref(5);

// Update visible cards based on screen size
const updateVisibleCards = () => {
  if (window.innerWidth < 840) {
    visibleCards.value = 2;
  } else if (window.innerWidth < 1024) {
    visibleCards.value = 3;
  } else if (window.innerWidth < 1280) {
    visibleCards.value = 4;
  } else {
    visibleCards.value = 5;
  }
};

// Scroll functionality for Similar Products Section
const previousSlide = () => {
  if (currentSlide.value > 0) {
    currentSlide.value--;
  }
};

const nextSlide = () => {
  if (currentSlide.value < Math.ceil(similarProducts.length / visibleCards.value) - 1) {
    currentSlide.value++;
  }
};

// Add event listener for window resize
onMounted(() => {
  updateVisibleCards();
  window.addEventListener("resize", updateVisibleCards);
});

onBeforeUnmount(() => {
  window.removeEventListener("resize", updateVisibleCards);
});

// Rating helpers
const getRatingCount = (rating) => {
  return product.reviews.filter((review) => review.rating === rating).length;
};

const getRatingPercentage = (rating) => {
  const count = getRatingCount(rating);
  return (count / product.reviews.length) * 100;
};

const toggleWishlist = (productId) => {
  // Implement wishlist toggle functionality
  console.log("Toggle wishlist for product:", productId);
};

const showSuccessModal = ref(false);
const addedProduct = ref(null);

const addToCart = () => {
  router.post(
    route("cart.add"),
    {
      product_id: props.product.id,
      name: props.product.name,
      price: props.product.price,
      quantity: quantity.value,
      image:
        props.product.product_images && props.product.product_images.length > 0
          ? "/storage/" + props.product.product_images[0]
          : "/storage/default.jpg",
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        addedProduct.value = {
          name: props.product.name,
          price: props.product.price,
          quantity: quantity.value,
          image:
            props.product.product_images && props.product.product_images.length > 0
              ? "/storage/" + props.product.product_images[0]
              : "/storage/default.jpg",
        };
        showSuccessModal.value = true;
      },
    }
  );
};

// Add this with your other refs
const isCollapsed = ref(true);

// Add this new function for similar items
const addSimilarToCart = (product) => {
  router.post(
    route("cart.add"),
    {
      product_id: product.id,
      name: product.name,
      price: product.price,
      quantity: 1,
      image: product.image ? "/storage/" + product.image : "/storage/default.jpg",
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        addedProduct.value = {
          name: product.name,
          price: product.price,
          quantity: 1,
          image: product.image ? "/storage/" + product.image : "/storage/default.jpg",
        };
        showSuccessModal.value = true;
      },
    }
  );
};

// Add this with your other functions
const redirectToCompare = (product) => {
  router.get(route('compare.products'), {
    category_id: props.product.category_id,
    product1: props.product.id,
    product2: product.id
  });
};

const showCompareReminder = ref(false);
const selectedProducts = ref([]);

const toggleProductForComparison = (product) => {
  const index = selectedProducts.value.findIndex(p => p.id === product.id);

  if (index !== -1) {
    // Remove if already selected
    selectedProducts.value.splice(index, 1);
  } else if (selectedProducts.value.length < 2) {
    // Add if less than 2 products are selected
    selectedProducts.value.push(product);
  } else {
    // Replace the first product if already have 2
    selectedProducts.value.shift();
    selectedProducts.value.push(product);
  }
};

const isSelectedForComparison = (productId) => {
  return selectedProducts.value.some(p => p.id === productId);
};

const clearSelectedProducts = () => {
  selectedProducts.value = [];
};

const compareSelectedProducts = () => {
  if (selectedProducts.value.length === 2) {
    router.get(route('compare.products'), {
      category_id: props.product.category_id,
      product1: selectedProducts.value[0].id,
      product2: selectedProducts.value[1].id
    });
  }
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

.hover\:text-navy-600:hover {
  color: #1a237e;
}

.border-navy-600 {
  border-color: #1a237e;
}

.focus\:ring-navy-500:focus {
  --tw-ring-color: #1e2b8f;
}

/* Hide number input spinners */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
/* input[type="number"] {
  -moz-appearance: textfield;
} */

.primary-text {
  color: #1a237e;
}

.main {
  background-color: #f8fafc;
}

.bg-navy-900 {
  background-color: #1a237e;
}

.bg-navy-900:hover {
  background-color: #151b60;
}

/* Slide transitions */
.slide-left-enter-active,
.slide-left-leave-active,
.slide-right-enter-active,
.slide-right-leave-active {
  transition: all 0.3s ease-in-out;
}

.slide-left-enter-from {
  transform: translateX(100%);
}

.slide-left-leave-to {
  transform: translateX(-100%);
}

.slide-right-enter-from {
  transform: translateX(-100%);
}

.slide-right-leave-to {
  transform: translateX(100%);
}

.slide-left-enter-to,
.slide-left-leave-from,
.slide-right-enter-to,
.slide-right-leave-from {
  transform: translateX(0);
}
/* Add these styles if you want a pulse animation */
@keyframes pulse {
  0% {
    box-shadow: 0 0 0 0 rgba(26, 35, 126, 0.7);
  }
  70% {
    box-shadow: 0 0 0 10px rgba(26, 35, 126, 0);
  }
  100% {
    box-shadow: 0 0 0 0 rgba(26, 35, 126, 0);
  }
}

.group:hover button {
  animation: pulse 2s infinite;
}

.group:hover .absolute {
  display: block;
}

.transition-opacity {
  transition: opacity 0.2s ease-in-out;
}

.bg-navy-100 {
  background-color: #e8eaf6;
}
</style>
