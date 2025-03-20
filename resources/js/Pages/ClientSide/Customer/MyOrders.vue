    <template>
        <div class="min-h-screen bg-gray-50">
        <NavLink />
        <Toast />
        <!-- Main Content -->
        <div class="container mx-auto px-4 py-8">
            <div v-if="$page.props.success" onMounted="$toast.success($page.props.success)">
            </div>
            <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <ProfileSidebar :customer="customer" />

           <!-- Orders Content -->
            <div class="flex-1">
            <div class="container mx-auto px-4 py-8 bg-white rounded-lg shadow-sm">
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
                        'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                    ]"
                    >
                    {{ tab.name }}
                    </button>
                </nav>
                </div>

                <!-- Orders List -->
                <div class="space-y-4">
                    <!-- No Orders Message -->
                    <div v-if="filteredOrders.length === 0" class="flex flex-col items-center justify-center py-12 bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <ShoppingBagIcon class="mx-auto h-12 w-12 text-gray-400 mb-4" />
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No {{ currentTab }} orders</h3>
                            <p class="text-gray-500 max-w-sm">
                                {{ getNoOrdersMessage(currentTab) }}
                            </p>
                            <Link
                                :href = "route('product.list')"
                                class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-navy-600 hover:bg-navy-700"
                            >
                                Start Shopping
                            </Link>
                        </div>
                    </div>

                    <!-- Orders List (existing code) -->
                    <div
                        v-else
                        v-for="order in filteredOrders"
                        :key="order.id"
                        class="bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4"
                    >
                        <!-- Order Details -->
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 uppercase">
                            {{ order.reference_number }}
                            </h3>
                            <div class="flex items-center text-sm text-gray-500 mt-2">
                            <CalendarIcon class="h-4 w-4 mr-1" />
                            <span>Order Date: {{ getDate(order.order_date) }}</span>
                            </div>
                        </div>

                        <!-- Price and Actions -->
                        <div class="flex flex-col items-end gap-2 min-w-[150px]">
                            <div class="text-right">
                            <div class="font-medium text-gray-900">
                                ₱{{ order.total_amount }}
                            </div>
                            </div>
                            <div class="flex gap-2">
                            <button
                                v-if="currentTab === 'delivered'"
                                class="px-3 py-1 text-sm bg-navy-600 text-white rounded hover:bg-navy-700"
                            >
                                Review
                            </button>
                            <Link
                                :href="route('customer.orderDetails', order.reference_number)"
                                class="px-3 py-1 text-sm bg-navy-900 text-white rounded hover:bg-navy-800"
                            >
                                View Order Details
                            </Link>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Orders List -->
                </div>


            </div>
            </div>
        </div>
        </div>
    </template>

    <script setup>
    import { ref, computed } from "vue";
    import { Link } from "@inertiajs/vue3";
    import {
        CalendarIcon,
        ShoppingBagIcon,
    } from "lucide-vue-next";
    import NavLink from "../../../Components/NavLink.vue";
    import ProfileSidebar from "../../../Components/ProfileSidebar.vue";
    import Toast from "../../../Components/Toast.vue";
import { route } from "../../../../../vendor/tightenco/ziggy/src/js";

    const props = defineProps({
        customer: Object,
        orders: Array,
        success: String
    });

    // Tabs
    const tabs = [
        { id: "pending", name: "Pending" },
        { id: "processing", name: "Processing" },
        { id: "shipped", name: "Shipped" },
        { id: "delivered", name: "Delivered" },
        { id: "cancelled", name: "Canceled" },
    ];

const getDate = (date) =>
  new Date(date).toLocaleDateString("en-us", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });

    const currentTab = ref("pending");

    // Sample Orders Data


    // Computed
    const filteredOrders = computed(() => {
        return props.orders.filter((order) => order.status === currentTab.value);
    });

    // Methods
    // const formatPrice = (price) => {
    //     return price.toLocaleString("en-PH", { minimumFractionDigits: 2 });
    // };

    const getNoOrdersMessage = (status) => {
        const messages = {
            pending: "You don't have any pending orders at the moment. Start shopping to place an order!",
            processing: "No orders are currently being processed. Check back later!",
            shipped: "No orders have been shipped yet. Your future shipments will appear here.",
            delivered: "You don't have any delivered orders yet. Completed deliveries will be shown here.",
            cancelled: "You don't have any cancelled orders. That's good news!"
        };
        return messages[status] || "No orders found.";
    };
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
    </style>
