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
    <div class="container mx-auto px-4 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Components Selection Container - Now spans 2 columns -->
        <div class="lg:col-span-2">
          <div class="main rounded-xl overflow-hidden p-6 mb-8">
            <h2 class="text-xl font-semibold mb-6 text-white">Select PC Components</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div
                v-for="component in components"
                :key="component.type"
                class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow"
              >
                <div class="flex items-start space-x-4">
                  <div class="p-3 bg-gray-100 rounded-lg">
                    <component :is="component.icon" class="h-6 w-6 text-gray-700" />
                  </div>
                  <div class="flex-1">
                    <h3 class="font-medium text-gray-900">{{ component.name }}</h3>
                    <div class="flex justify-between items-center mb-2">
                      <p class="text-sm text-gray-500">
                        {{ getSelectedComponentName(component.type) }}
                      </p>
                      <p
                        v-if="selectedComponents[component.type]"
                        class="text-sm font-semibold text-navy-600"
                      >
                        ₱{{ formatPrice(selectedComponents[component.type].price) }}
                      </p>
                    </div>
                    <div class="flex space-x-2">
                      <Link
                        :href="
                          route('component.selection', { componentType: component.type })
                        "
                        class="inline-block"
                      >
                        <button
                          class="bg-navy-600 text-white px-4 py-1.5 rounded text-sm hover:bg-navy-700 transition-colors"
                        >
                          {{ selectedComponents[component.type] ? "Change" : "Select" }}
                        </button>
                      </Link>
                      <button
                        v-if="selectedComponents[component.type]"
                        @click="clearComponent(component.type)"
                        class="bg-red-600 text-white px-4 py-1.5 rounded text-sm hover:bg-red-700 transition-colors"
                      >
                        Remove
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Build Summary - Now in its own column -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-xl shadow-sm p-6 sticky top-4">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-semibold">Your Build</h3>
              <!-- Add Clear All button -->
              <button
                v-if="selectedComponentsCount > 0"
                @click="clearAllComponents"
                class="text-sm text-red-600 hover:text-red-700 font-medium flex items-center"
              >
                <TrashIcon class="w-4 h-4 mr-1" />
                Clear All
              </button>
            </div>

            <!-- Progress Bar -->
            <div class="mb-6">
              <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Build Progress</span>
                <span
                  >{{ selectedComponentsCount }} of {{ components.length }} components
                  selected</span
                >
              </div>
              <div class="h-2 bg-gray-200 rounded-full">
                <div
                  class="h-2 bg-navy-600 rounded-full transition-all duration-300"
                  :style="{
                    width: `${(selectedComponentsCount / components.length) * 100}%`,
                  }"
                ></div>
              </div>
            </div>

            <!-- Selected Components List -->
            <div class="space-y-4 mb-6">
              <div
                v-for="component in selectedComponents"
                :key="component.type"
                class="flex justify-between text-sm"
              >
                <span class="text-gray-600">{{ component.name }}:</span>
                <span class="text-gray-900 font-medium">{{ component.price }}</span>
              </div>
            </div>

            <!-- Total Price -->
            <div class="border-t pt-4 mb-6">
              <div class="flex justify-between items-center">
                <span class="text-gray-900 font-semibold">Total Price</span>
                <span class="text-2xl font-bold text-navy-600"
                  >${{ totalPrice.toFixed(2) }}</span
                >
              </div>
            </div>

            <!-- Add to Cart Button -->
            <button
              @click="addToCart"
              :disabled="selectedComponentsCount === 0"
              class="w-full bg-navy-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-navy-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center justify-center"
            >
              <span>Add to Cart</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Component Selection Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div
        class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"
      >
        <div class="fixed inset-0 transition-opacity" @click="showModal = false">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
        >
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                  Select {{ currentComponentType }}
                </h3>
                <div class="mt-2 space-y-4">
                  <!-- Add your component selection options here -->
                  <div
                    v-for="option in getComponentOptions(currentComponentType)"
                    :key="option.id"
                    class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 cursor-pointer"
                    @click="selectComponent(currentComponentType, option)"
                  >
                    <div>
                      <h4 class="font-medium text-gray-900">{{ option.name }}</h4>
                      <p class="text-sm text-gray-500">{{ option.specs }}</p>
                    </div>
                    <span class="font-semibold text-navy-600">${{ option.price }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              @click="showModal = false"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-navy-600 text-base font-medium text-white hover:bg-navy-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- For debugging purposes, add this temporarily to debug -->
    <div v-if="false" class="p-4 bg-gray-100">
      <pre>{{ JSON.stringify(debugComponents, null, 2) }}</pre>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import {
  Cpu,
  Box,
  Fan,
  MemoryStick,
  HardDrive,
  Monitor,
  Microchip,
  Keyboard,
  Power,
  Mouse,
  ChevronLeft,
  ChevronRight,
  TrashIcon,
} from "lucide-vue-next";
import NavLink from "../../Components/NavLink.vue";
import { router } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
// Component definitions
const components = [
  { type: "processor", name: "Processor", icon: Cpu },
  { type: "case", name: "Case", icon: Box },
  { type: "motherboard", name: "Motherboard", icon: Cpu },
  { type: "cpucooler", name: "CPU Cooler", icon: Fan },
  { type: "memory", name: "Memory", icon: MemoryStick },
  { type: "fan", name: "Fan", icon: Fan },
  { type: "storage", name: "Storage", icon: HardDrive },
  { type: "monitor", name: "Monitor", icon: Monitor },
  { type: "graphicscard", name: "Graphics Card", icon: Microchip },
  { type: "keyboard", name: "Keyboard", icon: Keyboard },
  { type: "powersupply", name: "Power Supply", icon: Power },
  { type: "mouse", name: "Mouse", icon: Mouse },
];

const props = defineProps({
  products: Array,
  categories: Array,
  brands: Array,
});
// State
const selectedComponents = ref({});
const showModal = ref(false);
const currentComponentType = ref(null);

// Computed
const selectedComponentsCount = computed(() => {
  return Object.keys(selectedComponents.value).length;
});

// Methods

const openComponentSelection = (type) => {
  currentComponentType.value = type;
  showModal.value = true;
};

const selectComponent = (type, component) => {
  selectedComponents.value[type] = component;
  showModal.value = false;
};

const addToCart = async () => {
  try {
    console.log("Selected Components:", selectedComponents.value);

    if (Object.keys(selectedComponents.value).length === 0) {
      alert("No components selected");
      return;
    }

    // Prepare all components data at once
    const cartItems = Object.values(selectedComponents.value).map((component) => {
      let imagePath = component.image;
      if (imagePath && imagePath.startsWith("product_images/")) {
        imagePath = `/storage/${imagePath}`;
      } else if (imagePath && !imagePath.startsWith("/storage/")) {
        imagePath = `/storage/${imagePath}`;
      }

      return {
        product_id: component.id,
        name: component.name,
        price: parseFloat(component.price),
        quantity: 1,
        image: imagePath || "/storage/default.png",
      };
    });

    console.log("Attempting to add items to cart:", cartItems);

    // Send all items in a single request
    router.post(
      route("cart.add-multiple"),
      {
        items: cartItems,
      },
      {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
          console.log("Successfully added all items to cart");
          clearAllComponents();
          router.visit(route("cart.index"));
        },
        onError: (errors) => {
          console.error("Cart addition errors:", errors);
          alert("Failed to add items to cart");
        },
      }
    );
  } catch (error) {
    console.error("Error in addToCart:", error);
    alert("An error occurred while adding items to cart");
  }
};

// Carousel Data
const slides = [
  {
    title: "10.10 BIG SALE GET 15% OFF",
    description: "Build your dream gaming PC with our exclusive deals and discounts!",
    image: "storage/try.png", // Replace with your actual image path
  },
  {
    title: "MEGA PC DEALS",
    description: "Premium components at unbeatable prices",
    image: "storage/try.png", // Replace with your actual image path
  },
  {
    title: "LIMITED TIME OFFER",
    description: "Don't miss out on our biggest sale of the year",
    image: "storage/try.png", // Replace with your actual image path
  },
];

const currentSlide = ref(0);
let autoplayInterval;

// Carousel Controls
const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % slides.length;
};

const prevSlide = () => {
  currentSlide.value = (currentSlide.value - 1 + slides.length) % slides.length;
};

// Autoplay
const startAutoplay = () => {
  autoplayInterval = setInterval(() => {
    nextSlide();
  }, 5000); // Change slide every 5 seconds
};

const stopAutoplay = () => {
  if (autoplayInterval) {
    clearInterval(autoplayInterval);
  }
};

// Lifecycle hooks
onMounted(() => {
  loadSelectedComponents();
  startAutoplay();

  // Listen for storage changes
  window.addEventListener("storage", () => {
    loadSelectedComponents();
  });
});

onBeforeUnmount(() => {
  stopAutoplay();
});

// Add method to clear component selection
const clearComponent = (type) => {
  const stored = JSON.parse(localStorage.getItem("selected_components") || "[]");
  const updatedComponents = stored.filter((item) => item.type !== type);
  localStorage.setItem("selected_components", JSON.stringify(updatedComponents));
  delete selectedComponents.value[type];
};

// Update the loadSelectedComponents function
const loadSelectedComponents = () => {
  const stored = localStorage.getItem("selected_components");
  if (stored) {
    try {
      const parsedComponents = JSON.parse(stored);
      parsedComponents.forEach((item) => {
        selectedComponents.value[item.type] = item.product;
      });
    } catch (error) {
      console.error("Error loading components:", error);
    }
  }
};

// Update getSelectedComponentName function
const getSelectedComponentName = (type) => {
  const component = selectedComponents.value[type];
  return component ? component.name : "No component selected";
};

// Update getSelectedComponentPrice function
const getSelectedComponentPrice = (type) => {
  const component = selectedComponents.value[type];
  if (!component?.price) return null;
  // Remove the "₱" symbol and any commas from the price string
  const priceValue = component.price.toString().replace(/[₱,]/g, "");
  return formatPrice(parseFloat(priceValue));
};

// Update formatPrice function
const formatPrice = (price) => {
  if (!price) return "0.00";
  return parseFloat(price).toLocaleString("en-PH", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
};

// Update totalPrice computed
const totalPrice = computed(() => {
  return Object.values(selectedComponents.value).reduce((total, component) => {
    if (!component?.price) return total;
    const priceValue = parseFloat(component.price.toString().replace(/[₱,]/g, ""));
    return total + priceValue;
  }, 0);
});

// Add a debug method (temporary)
const debugComponents = computed(() => {
  console.log("Current selectedComponents:", selectedComponents.value);
  return selectedComponents.value;
});

// Add clearAllComponents function
const clearAllComponents = () => {
  // Clear localStorage
  localStorage.setItem("selected_components", "[]");
  // Clear reactive state
  selectedComponents.value = {};

  // Optional: Show confirmation toast/alert
  // You can add a toast notification here if you want
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

/* Carousel transitions */
.transition-transform {
  transition-property: transform;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Optional: Add fade effect for slide content */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
