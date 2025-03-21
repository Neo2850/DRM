<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { Link, router } from "@inertiajs/vue3";
import {
  ShoppingCartIcon,
  StarIcon,
  HeartIcon,
  PackageIcon,
  TruckIcon,
  HeadphonesIcon,
  ShieldIcon,
  ChevronLeft,
  ChevronRight,
  MessageCircle,
} from "lucide-vue-next";
import NavLink from "../../Components/NavLink.vue";
import { route } from "../../../../vendor/tightenco/ziggy/src/js";
import Footer from "../../Components/Footer.vue";
import AddToCartModal from "../../Components/AddToCartModal.vue";
import PowerDrill from "@/images/powerDrill.png";
import RoofSheets from "@/images/roofSheets.png";
import HydraulicHinges from "@/images/hydraulicHinges.png";

const props = defineProps({
  exploreProducts: Object,
  latestProducts: Object,
  products: Object,
  categories: Object,
});

const currentSlide = ref(0);
const visibleCards = ref(5);

// State for Categories Section
const currentCategorySlide = ref(0);
const visibleCategoryCards = ref(5);

// Update visible cards based on screen size
const updateVisibleCards = () => {
  if (window.innerWidth < 840) {
    visibleCards.value = 2;
    visibleCategoryCards.value = 2;
  } else if (window.innerWidth < 1024) {
    visibleCards.value = 3;
    visibleCategoryCards.value = 3;
  } else if (window.innerWidth < 1280) {
    visibleCards.value = 4;
    visibleCategoryCards.value = 4;
  } else {
    visibleCards.value = 5;
    visibleCategoryCards.value = 5;
  }
};

// Scroll functionality for Latest Products Section
const prevSlide = () => {
  currentSlide.value = Math.max(currentSlide.value - 1, 0);
};
const nextSlide = () => {
  currentSlide.value = Math.min(
    currentSlide.value + 1,
    props.latestProducts.length - visibleCards.value
  );
};

// Scroll functionality for Categories Section
const prevCategorySlide = () => {
  currentCategorySlide.value = Math.max(currentCategorySlide.value - 1, 0);
};
const nextCategorySlide = () => {
  currentCategorySlide.value = Math.min(
    currentCategorySlide.value + 1,
    props.categories.length - visibleCategoryCards.value
  );
};

// Add event listener for window resize
onMounted(() => {
  updateVisibleCards();
  window.addEventListener("resize", updateVisibleCards);
});

onBeforeUnmount(() => {
  window.removeEventListener("resize", updateVisibleCards);
});
// Sample categories (replace with your dynamic data)

// Reviews Data
const reviews = [
  {
    id: 1,
    name: "Martin Goutry",
    title: "Back-end developer at MyOnline",
    content:
      "Dico is finally addressing a long-time problem we had when building UIs. It's ease of use and workflow seems really intuitive. Promising!",
    date: "Dico user, 2023.03.02",
    avatar: "",
  },
  {
    id: 2,
    name: "Theo Champion",
    title: "Back-end developer at MyOnline",
    content:
      "Dico is finally addressing a long-time problem we had when building UIs. It's ease of use and workflow seems really intuitive. Promising!",
    date: "Dico user, 2023.03.02",
    avatar: "",
  },
  {
    id: 3,
    name: "Agnes Remi",
    title: "Back-end developer at MyOnline",
    content:
      "Dico is finally addressing a long-time problem we had when building UIs. It's ease of use and workflow seems really intuitive. Promising!",
    date: "Dico user, 2023.03.02",
    avatar: "",
  },
];

const showSuccessModal = ref(false);
const addedProduct = ref(null);

const addToCart = (product) => {
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

// Add these new refs
const showMessengerTooltip = ref(false);
const unreadMessages = ref(0); // You can set this based on your backend data

// Add this new method
const openMessenger = () => {
  window.open("https://www.facebook.com/", "_blank");
};
</script>

<template>
  <div class="min-h-screen bg-gray-200">
    <!-- Header/Navigation -->
    <NavLink />
    <!-- Hero Section -->
    <section class="py-16 bg-gray-50">
      <div class="container rounded-lg bg-gray-50 mt-1 mx-auto px-4 py-8">
        <div class="grid md:grid-cols-2 gap-8 items-center">
          <!-- Text Section -->
          <div>
            <h1 class="text-4xl md:text-5xl font-bold text-navy-900 mb-4 leading-tight">
              Reliable Roofing, Elegant Glass, and Durable Ironworks – Crafted for You!
            </h1>
            <p class="text-gray-600 mb-6 leading-relaxed">
              High-quality materials, expert craftsmanship, and hassle-free ordering.<br />
              Get a free quote today!
            </p>
          </div>

          <!-- Images Section -->
          <div class="grid md:grid-cols-2 gap-3">
            <div
              class="bg-white rounded-lg p-4 shadow-lg flex justify-center items-center"
            >
              <img
                :src="PowerDrill"
                alt="Power Drill"
                class="w-full max-w-[250px] object-contain"
              />
            </div>

            <!-- Right Content Section -->
            <div class="space-y-2">
              <!-- Summer Sales -->
              <div class="bg-white rounded-lg p-4 shadow-lg relative">
                <div
                  class="absolute z-10 top-2 left-2 bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded"
                >
                  SUMMER SALES
                </div>
                <div class="w-full h-36 overflow-hidden">
                  <img
                  :src="RoofSheets"
                  alt="Roofing Sheets"
                  class="relative -top-10 "
                />
                </div>
                <h3 class="font-semibold mt-2">Metal Barrel Roof Tiles</h3>
                <div class="text-lg font-bold text-green-600">29% OFF</div>
              </div>

              <div class="bg-white rounded-lg p-4 shadow-lg">
                <img
                  :src="HydraulicHinges"
                  alt="Hydraulic Hinges"
                  class="w-full max-w-[150px] mx-auto mb-2"
                />
                <h3 class="font-semibold mb-2 text-center">Hydraulic Hinges</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-12 bg-white">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          <div class="flex items-center space-x-4">
            <div class="p-3 bg-gray-100 rounded-lg">
              <PackageIcon class="h-6 w-6 text-navy-600" />
            </div>
            <div>
              <h3 class="font-semibold">Discount</h3>
              <p class="text-sm text-gray-600">Every month new sales</p>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <div class="p-3 main rounded-lg">
              <TruckIcon class="h-6 w-6 text-navy-600" />
            </div>
            <div>
              <h3 class="font-semibold">Free Delivery</h3>
              <p class="text-sm text-gray-600">100% Free for all orders</p>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <div class="p-3 main rounded-lg">
              <HeadphonesIcon class="h-6 w-6 text-navy-600" />
            </div>
            <div>
              <h3 class="font-semibold">Great Support 24/7</h3>
              <p class="text-sm text-gray-600">We care your experiences</p>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <div class="p-3 main rounded-lg">
              <ShieldIcon class="h-6 w-6 text-navy-600" />
            </div>
            <div>
              <h3 class="font-semibold">Secure Payment</h3>
              <p class="text-sm text-gray-600">100% Secure Payment Method</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Latest Products Section -->
    <section class="py-12 bg-gray-50">
      <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="flex justify-between items-center mb-8">
          <div>
            <div class="flex items-center space-x-2 mb-2">
              <div class="w-4 h-8 bg-navy-600 rounded"></div>
              <span class="text-sm font-medium">This Month</span>
            </div>
            <h2 class="text-2xl font-bold">Latest Products</h2>
          </div>
        </div>

        <div class="relative overflow-hidden">
          <!-- Carousel Wrapper -->
          <div
            class="flex transition-transform duration-300"
            :style="{ transform: `translateX(-${currentSlide * (100 / visibleCards)}%)` }"
            ref="productCarousel"
          >
            <Link
              v-for="latestProduct in latestProducts"
              :key="latestProduct.id"
              :href="route('product.view', { slug: latestProduct.slug })"
              class="flex-none px-1"
              :class="{
                'w-1/2': visibleCards === 2,
                'w-1/3': visibleCards === 3,
                'w-1/4': visibleCards === 4,
                'w-1/5': visibleCards === 5,
              }"
            >
              <div
                class="bg-white rounded-lg p-2 sm:p-3 hover:shadow-lg transition-all duration-300 border border-gray-200 hover:border-blue-500"
              >
                <!-- Brand Name -->
                <span
                  class="text-end text-xs sm:text-sm font-semibold text-black uppercase mb-1 sm:mb-2 block"
                >
                  {{ latestProduct.brand }}
                </span>

                <!-- Product Image -->
                <div
                  class="flex justify-center items-center mb-2 sm:mb-3 aspect-w-1 aspect-h-1"
                >
                  <img
                    :src="
                      latestProduct.image
                        ? '/storage/' + latestProduct.image
                        : 'storage/default.jpg'
                    "
                    :alt="latestProduct.name"
                    class="w-full h-28 sm:h-32 md:h-36 lg:h-40 object-cover rounded-lg"
                  />
                </div>

                <!-- Product Name -->
                <h3
                  class="primary-text text-sm sm:text-base font-medium mb-1 sm:mb-2 truncate whitespace-nowrap overflow-hidden"
                >
                  {{ latestProduct.name }}
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
                          n <= latestProduct.rating
                            ? 'text-yellow-400 fill-current'
                            : 'text-gray-400'
                        "
                      />
                      <span class="text-gray-400 text-xs sm:text-sm ml-1 sm:ml-2">
                        ({{ latestProduct.stock }})
                      </span>
                    </div>
                    <span class="primary-text font-bold text-sm sm:text-base lg:text-lg">
                      ₱{{ latestProduct.price }}
                    </span>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex space-x-1 sm:space-x-2 mt-1 sm:mt-2">
                    <button class="p-1.5 sm:p-1.5 primary-text main rounded-lg">
                      <HeartIcon class="h-4 w-4 sm:h-5 sm:w-5 hover:text-red-500" />
                    </button>
                    <button
                      @click.prevent="addToCart(latestProduct)"
                      class="p-1.5 sm:p-1.5 text-white bg-navy-900 rounded-lg"
                    >
                      <ShoppingCartIcon class="h-4 w-4 sm:h-5 sm:w-5" />
                    </button>
                  </div>
                </div>
              </div>
            </Link>
          </div>

          <!-- Navigation Buttons -->
          <button
            @click="prevSlide"
            class="absolute left-0 top-1/2 -translate-y-1/2 bg-navy-800 p-2 rounded-full hover:bg-navy-700 transition sm:p-1 lg:p-2"
          >
            <ChevronLeft class="h-6 w-6 text-white" />
          </button>
          <button
            @click="nextSlide"
            class="absolute right-0 top-1/2 -translate-y-1/2 bg-navy-800 p-2 rounded-full hover:bg-navy-700 transition sm:p-1 lg:p-2"
          >
            <ChevronRight class="h-6 w-6 text-white" />
          </button>
        </div>
      </div>
    </section>

    <!-- Categories Section -->
    <section class="py-12 bg-navy-900 text-white">
      <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold mb-8">Categories</h2>
        <div class="relative overflow-hidden">
          <!-- Carousel Wrapper -->
          <div
            class="flex transition-transform duration-300"
            :style="{
              transform: `translateX(-${
                currentCategorySlide * (100 / visibleCategoryCards)
              }%)`,
            }"
            ref="categoryCarousel"
          >
            <Link
              :href="route('category.products', { categorySlug: category.slug })"
              v-for="category in categories"
              :key="category.id"
              class="flex-none px-1"
              :class="{
                'w-1/2': visibleCategoryCards === 2,
                'w-1/3': visibleCategoryCards === 3,
                'w-1/4': visibleCategoryCards === 4,
                'w-1/5': visibleCategoryCards === 5,
              }"
            >
              <div class="bg-navy-800 rounded-lg overflow-hidden">
                <img
                  :src="
                    category.image ? '/storage/' + category.image : 'storage/default.jpg'
                  "
                  :alt="category.name"
                  class="w-full h-48 object-contain"
                />
                <div class="p-4">
                  <h3 class="font-medium truncate whitespace-nowrap overflow-hidden">
                    {{ category.name }}
                  </h3>
                </div>
              </div>
            </Link>
          </div>

          <!-- Navigation Buttons -->
          <button
            @click="prevCategorySlide"
            class="absolute left-0 top-1/2 -translate-y-1/2 bg-navy-800 p-2 rounded-full hover:bg-navy-700 transition sm:p-1 lg:p-2"
          >
            <ChevronLeft class="h-6 w-6 text-white" />
          </button>
          <button
            @click="nextCategorySlide"
            class="absolute right-0 top-1/2 -translate-y-1/2 bg-navy-800 p-2 rounded-full hover:bg-navy-700 transition sm:p-1 lg:p-2"
          >
            <ChevronRight class="h-6 w-6 text-white" />
          </button>
        </div>
      </div>
    </section>

    <!-- Explore Products Section -->
    <section class="py-12 bg-gray-50">
      <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="flex items-center space-x-2 mb-2">
          <div class="w-4 h-8 bg-navy-600 rounded"></div>
          <span class="text-sm font-medium">Our Products</span>
        </div>
        <div class="flex justify-between items-center mb-8">
          <h2 class="text-2xl font-bold">Explore Our Products</h2>
          <Link
            :href="route('product.list')"
            class="px-4 py-2 bg-navy-900 text-white rounded-lg hover:bg-navy-800"
          >
            Browse All Products
          </Link>
        </div>

        <!-- Responsive Products Grid -->
        <div
          class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 sm:gap-2"
        >
          <Link
            :href="route('product.view', { slug: product.slug })"
            v-for="product in exploreProducts"
            :key="product.id"
            class=" relative bg-white rounded-lg p-2 sm:p-3 hover:shadow-lg transition-all duration-300 border border-gray-200 block hover:border-blue-500"
          >
            <!-- Brand Name -->
            <span
              class="text-end text-xs sm:text-sm font-semibold text-black uppercase mb-1 sm:mb-2 block"
            >
              {{ product.brand.name }}
            </span>

            <!-- Product Image -->
            <div
              class="flex justify-center items-center mb-2 sm:mb-3 aspect-w-1 aspect-h-1"
            >
              <img
                :src="product.image ? '/storage/' + product.image : 'storage/default.jpg'"
                :alt="product.name"
                class="w-full h-28 sm:h-32 md:h-36 lg:h-40 object-cover rounded-lg"
              />
            </div>

            <!-- Product Name -->
            <h3
              class="primary-text text-sm sm:text-base font-medium mb-1 sm:mb-2 truncate whitespace-nowrap overflow-hidden"
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
                  <span class="text-gray-400 text-xs sm:text-sm ml-1 sm:ml-2"
                    >({{ product.stock }})</span
                  >
                </div>
                <span class="primary-text font-bold text-sm sm:text-base lg:text-lg"
                  >₱{{ product.price }}</span
                >
              </div>

              <!-- Action Buttons -->
              <div class="flex space-x-1 sm:space-x-2 mt-1 sm:mt-2">
                <button class="p-1.5 sm:p-1.5 primary-text main rounded-lg">
                  <HeartIcon class="h-4 w-4 sm:h-5 sm:w-5 hover:text-red-500" />
                </button>
                <button
                  @click.prevent="addToCart(product)"
                  class="p-1.5 sm:p-1.5 button-primary rounded-lg"
                >
                  <ShoppingCartIcon class="h-4 w-4 sm:h-5 sm:w-5" />
                </button>
              </div>
            </div>
          </Link>
        </div>
      </div>
    </section>

    <!-- Reviews Section -->
    <section class="py-12 bg-navy-900 text-white">
      <div class="container mx-auto px-4">
        <div class="flex items-center space-x-2 mb-2">
          <div class="w-4 h-8 bg-white rounded"></div>
          <span class="text-sm font-medium">Reviews</span>
        </div>
        <h2 class="text-2xl font-bold mb-8">What our customers say about</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="review in reviews"
            :key="review.id"
            class="bg-navy-800 rounded-lg p-6"
          >
            <div class="flex items-center mb-4">
              <img
                :src="review.avatar"
                :alt="review.name"
                class="w-12 h-12 rounded-full"
              />
              <div class="ml-4">
                <h3 class="font-medium">{{ review.name }}</h3>
                <p class="text-sm text-gray-400">{{ review.title }}</p>
              </div>
            </div>
            <p class="text-gray-300">{{ review.content }}</p>
            <p class="mt-4 text-sm text-gray-400">{{ review.date }}</p>
          </div>
        </div>
      </div>
    </section>
    <Footer />
    <AddToCartModal
      :is-open="showSuccessModal"
      :product="addedProduct"
      @close="showSuccessModal = false"
    />

    <!-- Floating Messenger Icon -->
    <div
      class="fixed bottom-6 right-6 z-50 group"
      @mouseenter="showMessengerTooltip = true"
      @mouseleave="showMessengerTooltip = false"
    >
      <!-- Tooltip -->

      <div
        v-if="showMessengerTooltip"
        class="absolute bottom-full right-0 mb-2 bg-white text-gray-800 px-4 py-2 rounded-lg shadow-lg text-sm whitespace-nowrap transform transition-opacity duration-200"
      >
        Something in your mind?
        <div
          class="absolute bottom-0 right-4 transform translate-y-1/2 rotate-45 w-2 h-2 bg-white"
        ></div>
      </div>

      <!-- Messenger Button -->
      <button
        @click="openMessenger"
        class="bg-navy-600 hover:bg-navy-700 text-white rounded-full p-4 shadow-lg transition-all duration-300 hover:scale-110"
      >
        <MessageCircle class="h-6 w-6" />
      </button>

      <!-- Notification Badge -->
      <div
        v-if="unreadMessages > 0"
        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"
      >
        {{ unreadMessages }}
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Add these styles if you want a pulse animation */
@keyframes pulse {
  0% {
    box-shadow: 0 0 0 0 rgba(26, 35, 126, 0.7);
  }
  70% {
    box-shadow: 0 0 0 1px rgba(26, 35, 126, 0);
  }
  100% {
    box-shadow: 0 0 0 0 rgba(26, 35, 126, 0);
  }
}

.group:hover button {
  animation: pulse 2s infinite;
}
</style>
