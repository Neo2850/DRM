<template>
    <div class="min-h-screen bg-gray-50">
      <!-- Navigation -->
      <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-4">
          <div class="flex items-center justify-between h-16">
            <Link href="/" class="flex items-center">
              <span class="text-2xl font-bold text-navy-900">ArtzworkPC</span>
            </Link>
  
            <div class="hidden md:flex items-center space-x-8">
              <Link 
                v-for="item in navigation" 
                :key="item.name"
                :href="item.href"
                class="text-gray-700 hover:text-navy-600"
              >
                {{ item.name }}
              </Link>
            </div>
  
            <div class="flex items-center space-x-4">
              <button class="text-gray-700 hover:text-navy-600">
                <ShoppingCartIcon class="h-6 w-6" />
              </button>
              <button class="text-gray-700 hover:text-navy-600">
                <UserIcon class="h-6 w-6" />
              </button>
            </div>
          </div>
        </div>
      </nav>
  
      <!-- Main Content -->
      <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
          <!-- Sidebar -->
          <div class="w-full md:w-64 shrink-0">
            <nav class="space-y-1">
              <Link
                v-for="item in sidebarNavigation"
                :key="item.name"
                :href="item.href"
                :class="[
                  item.current
                    ? 'bg-gray-100 text-gray-900'
                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                  'group flex items-center px-4 py-3 text-sm font-medium rounded-lg'
                ]"
              >
                <component 
                  :is="item.icon" 
                  class="mr-3 h-5 w-5"
                  :class="item.current ? 'text-gray-900' : 'text-gray-400 group-hover:text-gray-500'"
                />
                {{ item.name }}
              </Link>
            </nav>
          </div>
  
          <!-- Orders Content -->
          <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">My Orders</h1>
  
            <!-- Order Status Tabs -->
            <div class="border-b border-gray-200 mb-6">
              <nav class="flex space-x-8">
                <button
                  v-for="tab in tabs"
                  :key="tab.name"
                  @click="currentTab = tab.id"
                  :class="[
                    currentTab === tab.id
                      ? 'border-navy-600 text-navy-600'
                      : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                    'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
                  ]"
                >
                  {{ tab.name }}
                </button>
              </nav>
            </div>
  
            <!-- Orders List -->
            <div class="space-y-4">
              <div 
                v-for="order in filteredOrders" 
                :key="order.id"
                class="bg-white rounded-lg shadow-sm p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4"
              >
                <!-- Product Image -->
                <img 
                  :src="order.image" 
                  :alt="order.productName"
                  class="w-20 h-20 object-cover rounded-lg"
                />
  
                <!-- Order Details -->
                <div class="flex-1">
                  <h3 class="font-medium text-gray-900">{{ order.productName }}</h3>
                  <div class="flex flex-wrap gap-x-2 text-sm text-gray-500 mt-1">
                    <span>{{ order.category }}</span>
                    <span>•</span>
                    <span>{{ order.brand }}</span>
                    <span>•</span>
                    <span>{{ order.sku }}</span>
                  </div>
                  <div class="flex items-center text-sm text-gray-500 mt-2">
                    <CalendarIcon class="h-4 w-4 mr-1" />
                    <span>Order Date: {{ order.orderDate }}</span>
                  </div>
                </div>
  
                <!-- Price and Actions -->
                <div class="flex flex-col items-end gap-2 min-w-[150px]">
                  <div class="text-right">
                    <div class="font-medium text-gray-900">${{ formatPrice(order.price) }}</div>
                    <div class="text-sm text-gray-500">Qty: {{ order.quantity }}</div>
                  </div>
                  <div class="flex gap-2">
                    <button 
                      v-if="currentTab === 'completed'"
                      class="px-3 py-1 text-sm bg-navy-600 text-white rounded hover:bg-navy-700"
                    >
                      Review
                    </button>
                    <button 
                      class="px-3 py-1 text-sm bg-navy-900 text-white rounded hover:bg-navy-800"
                    >
                      View Order Details
                    </button>
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
  import { ref, computed } from 'vue'
  import { Link } from '@inertiajs/vue3'
  import {
    ShoppingCartIcon,
    UserIcon,
    UserCircleIcon,
    ShoppingBagIcon,
    MapPinIcon,
    HeartIcon,
    LockIcon,
    CalendarIcon
  } from 'lucide-vue-next'
  
  // Navigation
  const navigation = [
    { name: 'Home', href: '/' },
    { name: 'Products', href: '/products' },
    { name: 'PC Builder', href: '/pc-builder' },
    { name: 'Laptops', href: '/laptops' },
    { name: 'Desktops', href: '/desktops' },
  ]
  
  // Sidebar Navigation
  const sidebarNavigation = [
    { name: 'Account Profile', href: '/account', icon: UserCircleIcon, current: false },
    { name: 'My Orders', href: '/orders', icon: ShoppingBagIcon, current: true },
    { name: 'Addresses', href: '/addresses', icon: MapPinIcon, current: false },
    { name: 'Wishlists', href: '/wishlists', icon: HeartIcon, current: false },
    { name: 'Security', href: '/security', icon: LockIcon, current: false },
  ]
  
  // Tabs
  const tabs = [
    { id: 'to-pay', name: 'To Pay' },
    { id: 'shipped', name: 'Shipped' },
    { id: 'completed', name: 'Completed' },
    { id: 'canceled', name: 'Canceled' },
  ]
  
  const currentTab = ref('to-pay')
  
  // Sample Orders Data
  const orders = [
    {
      id: 1,
      productName: 'RUIX Black',
      category: 'PC Case',
      brand: 'RUIX',
      sku: 'CASE-RUIX-003',
      price: 2599.00,
      quantity: 1,
      orderDate: 'May 16, 2022',
      status: 'to-pay',
      image: '/placeholder.svg?height=200&width=200'
    },
    {
      id: 2,
      productName: 'RUIX Black',
      category: 'PC Case',
      brand: 'RUIX',
      sku: 'CASE-RUIX-003',
      price: 2599.00,
      quantity: 1,
      orderDate: 'May 16, 2022',
      status: 'to-pay',
      image: '/placeholder.svg?height=200&width=200'
    },
    {
      id: 3,
      productName: 'RUIX Black',
      category: 'PC Case',
      brand: 'RUIX',
      sku: 'CASE-RUIX-003',
      price: 2599.00,
      quantity: 1,
      orderDate: 'May 16, 2022',
      status: 'to-pay',
      image: '/placeholder.svg?height=200&width=200'
    }
  ]
  
  // Computed
  const filteredOrders = computed(() => {
    return orders.filter(order => order.status === currentTab.value)
  })
  
  // Methods
  const formatPrice = (price) => {
    return price.toLocaleString('en-PH', { minimumFractionDigits: 2 })
  }
  </script>
  
  <style scoped>
  .bg-navy-600 {
    background-color: #1a237e;
  }
  
  .bg-navy-700 {
    background-color: #151b60;
  }
  
  .bg-navy-800 {
    background-color: #0d1147;
  }
  
  .bg-navy-900 {
    background-color: #070b2e;
  }
  
  .text-navy-600 {
    color: #1a237e;
  }
  
  .border-navy-600 {
    border-color: #1a237e;
  }
  
  .hover\:bg-navy-700:hover {
    background-color: #151b60;
  }
  
  .hover\:bg-navy-800:hover {
    background-color: #0d1147;
  }
  </style>