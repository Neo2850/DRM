<template>
  <div class="min-h-screen bg-gray-50">
    <NavLink />

    <!-- Hero Section -->
    <div class="relative bg-gray-50 text-white">
      <div class="container mx-auto px-4 py-8">
        <!-- Carousel Container -->
        <div class="relative bg-[#1C1C1C] rounded-xl overflow-hidden">
          <!-- Slides -->
          <div class="overflow-hidden relative">
            <div
              class="flex transition-transform duration-500 ease-out"
              :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
            >
              <div
                v-for="(slide, index) in slides"
                :key="index"
                class="w-full flex-shrink-0"
              >
                <div class="flex flex-col md:flex-row items-center justify-between p-8">
                  <div class="text-center md:text-left mb-8 md:mb-0 md:w-1/2">
                    <div
                      class="inline-block bg-red-600 text-white text-sm px-3 py-1 rounded-full mb-4"
                    >
                      15% OFF
                    </div>
                    <h1
                      class="text-2xl md:text-4xl font-bold mb-4 leading-tight text-[#FFD700]"
                    >
                      {{ slide.title }}
                    </h1>
                    <p class="text-gray-300 mb-6">{{ slide.description }}</p>
                    <button
                      class="bg-[#FFD700] text-gray-900 px-8 py-3 rounded-full font-semibold hover:bg-[#F0C800] transition-colors"
                    >
                      BUILD NOW!
                    </button>
                  </div>
                  <div class="md:w-1/2">
                    <img
                      :src="slide.image"
                      :alt="slide.title"
                      class="w-full max-w-lg mx-auto transform hover:scale-105 transition-transform duration-300"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Dots Navigation -->
          <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
            <button
              v-for="(_, index) in slides"
              :key="index"
              @click="currentSlide = index"
              class="w-2 h-2 rounded-full transition-colors"
              :class="
                currentSlide === index ? 'bg-[#FFD700]' : 'bg-white/50 hover:bg-white/75'
              "
            ></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-3">
      <h1 class="text-2xl font-semibold mb-3">
        Select
        {{ props.componentType.charAt(0).toUpperCase() + props.componentType.slice(1) }}
      </h1>
      <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <!-- Search -->
        <div class="w-full md:w-auto mb-4 md:mb-0">
          <div class="relative">
            <input
              type="text"
              v-model="search"
              placeholder="Search products..."
              class="w-full md:w-80 pl-10 pr-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500 bg-white shadow-sm"
            />
            <SearchIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
          </div>
        </div>

        <!-- Sort -->
        <div class="w-full md:w-auto">
          <select
            v-model="sortBy"
            class="w-full md:w-48 pl-4 pr-8 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500 bg-white shadow-sm appearance-none cursor-pointer"
          >
            <option value="price-asc">Price: Low to High</option>
            <option value="price-desc">Price: High to Low</option>
          </select>
        </div>
      </div>

      <!-- Component Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div
          v-for="product in props.products"
          :key="product.id"
          class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 relative"
        >
          <!-- Product Header -->
          <div class="p-4 border-b flex justify-between items-start">
            <span
              :class="[
                'px-2 py-1 text-xs rounded-full',
                product.stock > 0
                  ? 'bg-green-100 text-green-800'
                  : 'bg-red-100 text-red-800',
              ]"
            >
              {{ product.stock > 0 ? "In Stock" : "Out of Stock" }}
            </span>
            <span class="text-sm font-semibold uppercase text-gray-600">{{
              product.brand
            }}</span>
          </div>

          <!-- Product Image -->
          <div class="p-4">
            <img
              :src="product.image ? `/storage/${product.image}` : '/storage/default.png'"
              :alt="product.name"
              class="w-full h-48 object-contain mb-4"
            />
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ product.name }}</h3>
          </div>

          <!-- Specifications -->
          <div class="p-4 bg-gray-50 border-b relative">
            <button
              @click="toggleSpecs(product.id)"
              class="w-full flex justify-between items-center text-sm font-semibold text-gray-700 mb-2"
            >
              <span>Specifications</span>
              <ChevronDown
                :class="[
                  'w-5 h-5 transition-transform duration-200',
                  expandedSpecs.includes(product.id) ? 'transform rotate-180' : '',
                ]"
              />
            </button>

            <div
              v-show="expandedSpecs.includes(product.id)"
              class="absolute left-0 w-full bg-gray-50 shadow-lg z-10 border-x border-b rounded-b-lg"
            >
              <div class="p-4 space-y-2">
                <div
                  v-for="(spec, index) in product.specifications"
                  :key="index"
                  class="flex justify-between text-sm"
                >
                  <span class="text-gray-600">{{ spec.name }}:</span>
                  <span class="font-medium text-gray-900">{{ spec.value }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Price and Action -->
          <div class="p-4 flex items-center justify-between">
            <div class="text-lg font-bold text-navy-600">
              ₱{{ formatPrice(product.price) }}
            </div>
            <button
              @click="selectProduct(product)"
              :disabled="product.stock <= 0"
              :class="[
                'px-4 py-2 rounded-lg transition-colors',
                isSelected(product.id)
                  ? 'bg-green-600 hover:bg-green-700 text-white'
                  : 'bg-navy-600 hover:bg-navy-700 text-white',
                product.stock <= 0 ? 'opacity-50 cursor-not-allowed' : '',
              ]"
            >
              {{ isSelected(product.id) ? "Selected" : "Select" }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { SearchIcon, ChevronDown } from "lucide-vue-next";
import NavLink from "../../Components/NavLink.vue";

// Mobile menu state

const props = defineProps({
  products: Array,
  componentType: String,
});

// Search and sort
const search = ref("");
const sortBy = ref("price-asc");

// Computed
const filteredProducts = computed(() => {
  let result = [...props.products];

  // Apply search filter
  if (search.value) {
    const searchLower = search.value.toLowerCase();
    result = result.filter(
      (product) =>
        product.name.toLowerCase().includes(searchLower) ||
        product.brand.toLowerCase().includes(searchLower)
    );
  }

  // Apply sorting
  result.sort((a, b) => {
    switch (sortBy.value) {
      case "price-desc":
        return b.price - a.price;
      case "price-asc":
        return a.price - b.price;
      case "name":
        return a.name.localeCompare(b.name);
      default:
        return 0;
    }
  });

  return result;
});

// Methods
const formatPrice = (price) => {
  return price.toLocaleString("en-PH");
};

const selectProduct = (product) => {
  // Get existing selections
  const existingSelections = JSON.parse(
    localStorage.getItem("selected_components") || "[]"
  );

  // Find if there's an existing selection for this component type
  const index = existingSelections.findIndex((item) => item.type === props.componentType);

  if (index !== -1) {
    // Update existing selection
    existingSelections[index] = {
      type: props.componentType,
      product: product,
    };
  } else {
    // Add new selection
    existingSelections.push({
      type: props.componentType,
      product: product,
    });
  }

  // Save to localStorage
  localStorage.setItem("selected_components", JSON.stringify(existingSelections));

  // Trigger storage event for other tabs/windows
  window.dispatchEvent(new Event("storage"));

  // Redirect back to PC Builder
  router.visit(route("pc.builder"));
};

// Add carousel data and functions
const slides = [
  {
    title: "10.10 BIG SALE GET 15% OFF",
    description: "Build your dream gaming PC with our exclusive deals and discounts!",
    image: "/storage/try.png",
  },
  {
    title: "MEGA PC DEALS",
    description: "Premium components at unbeatable prices",
    image: "/storage/try.png",
  },
  {
    title: "LIMITED TIME OFFER",
    description: "Don't miss out on our biggest sale of the year",
    image: "/storage/try.png",
  },
];

const currentSlide = ref(0);
let autoplayInterval;

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % slides.length;
};

const startAutoplay = () => {
  autoplayInterval = setInterval(() => {
    nextSlide();
  }, 5000);
};

const stopAutoplay = () => {
  if (autoplayInterval) {
    clearInterval(autoplayInterval);
  }
};

const isSelected = (productId) => {
  const selections = JSON.parse(localStorage.getItem("selected_components") || "[]");
  const currentSelection = selections.find((item) => item.type === props.componentType);

  if (!currentSelection) return false;
  return currentSelection.product.id === productId;
};

// Add this for tracking expanded specification panels
const expandedSpecs = ref([]);

// Add this function to toggle specifications visibility
const toggleSpecs = (productId) => {
  const index = expandedSpecs.value.indexOf(productId);
  if (index === -1) {
    expandedSpecs.value.push(productId);
  } else {
    expandedSpecs.value.splice(index, 1);
  }
};

const getAllSelectedComponents = () => {
  return JSON.parse(localStorage.getItem("selected_components") || "[]");
};

onMounted(() => {
  startAutoplay();
});

onBeforeUnmount(() => {
  stopAutoplay();
});
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

.focus\:ring-navy-500:focus {
  --tw-ring-color: #1e2b8f;
}

/* Carousel transitions */
.transition-transform {
  transition-property: transform;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Add these transitions */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

.transform {
  transform: translateZ(0);
}

.rotate-180 {
  transform: rotate(180deg);
}
</style>
