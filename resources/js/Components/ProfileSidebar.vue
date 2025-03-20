<template>
    <div
      :class="[
        'lg:w-80 transition-all duration-300 ease-in-out',
        isMobileMenuOpen ? 'block' : 'hidden lg:block',
      ]"
    >
      <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
          <div class="flex items-center gap-4 mb-6">
            <div
              class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white text-lg font-semibold"
            >
              {{ initials }}
            </div>
            <div>
              <h3 class="font-medium">
                {{ customer.first_name }} {{ customer.last_name }}
              </h3>
              <p class="text-sm text-gray-500">{{ customer.email }}</p>
            </div>
          </div>

          <nav class="space-y-1">
            <Link
              :href="route('customer.profile')"
              :class="[
                'flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors',
                route().current('customer.profile')
                  ? 'text-blue-600 bg-blue-50'
                  : 'text-gray-600 hover:bg-gray-50'
              ]"
            >
              <User class="w-5 h-5" />
              Account Profile
            </Link>

            <Link
              :href="route('customer.myOrders')"
              :class="[
                'flex items-center gap-3 px-4 py-3 rounded-lg transition-colors',
                route().current('customer.myOrders')
                  ? 'text-blue-600 bg-blue-50'
                  : 'text-gray-600 hover:bg-gray-50'
              ]"
            >
              <ShoppingBag class="w-5 h-5" />
              My Orders
            </Link>

            <Link

              :class="[
                'flex items-center gap-3 px-4 py-3 rounded-lg transition-colors',
                route().current('customer.addresses')
                  ? 'text-blue-600 bg-blue-50'
                  : 'text-gray-600 hover:bg-gray-50'
              ]"
            >
              <MapPin class="w-5 h-5" />
              Addresses
            </Link>

            <Link

              :class="[
                'flex items-center gap-3 px-4 py-3 rounded-lg transition-colors',
                route().current('customer.wishlists')
                  ? 'text-blue-600 bg-blue-50'
                  : 'text-gray-600 hover:bg-gray-50'
              ]"
            >
              <Heart class="w-5 h-5" />
              Wishlists
            </Link>

            <Link

              :class="[
                'flex items-center gap-3 px-4 py-3 rounded-lg transition-colors',
                route().current('customer.security')
                  ? 'text-blue-600 bg-blue-50'
                  : 'text-gray-600 hover:bg-gray-50'
              ]"
            >
              <Lock class="w-5 h-5" />
              Security
            </Link>
          </nav>
        </div>
      </div>
    </div>
  </template>

  <script setup>
import { computed } from "vue";
import { Link } from '@inertiajs/vue3';
import { User, ShoppingBag, MapPin, Heart, Lock } from "lucide-vue-next";

const props = defineProps({
  customer: Object,
  isMobileMenuOpen: Boolean,
});

const initials = computed(() => {
  const firstInitial = props.customer?.first_name?.charAt(0) || "";
  const lastInitial = props.customer?.last_name?.charAt(0) || "";
  return `${firstInitial}${lastInitial}`.toUpperCase();
});
</script>
