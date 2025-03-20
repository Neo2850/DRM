<template>
  <Head title="Dashboard"></Head>
  <div class="min-h-screen bg-gray-50">
    <!-- Mobile Sidebar Toggle -->
    <Sidebar></Sidebar>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
      <!-- Header -->
      <Header title="Dashboard"></Header>

      <!-- Dashboard Content -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Message -->
        <div class="mb-6">
          <h2 class="text-3xl font-bold text-gray-800">Welcome back, {{ userName }}!</h2>
        </div>

        <!-- Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
          <!-- Total Revenue -->
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-100 rounded-lg">
                  <DollarSignIcon class="w-6 h-6 text-indigo-600" />
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                  <p class="text-xl font-semibold">₱{{ metrics.total_revenue }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Yearly Revenue -->
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-green-100 rounded-lg">
                  <BarChart3Icon class="w-6 h-6 text-green-600" />
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-600">Yearly Revenue</p>
                  <p class="text-xl font-semibold">₱{{ metrics.yearly_revenue }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Daily Revenue (Replacing Total Products) -->
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                  <ActivityIcon class="w-6 h-6 text-blue-600" />
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-600">Daily Revenue</p>
                  <p class="text-xl font-semibold">₱{{ metrics.daily_revenue }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Total Customers -->
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-purple-100 rounded-lg">
                  <UsersIcon class="w-6 h-6 text-purple-600" />
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-600">Total Customers</p>
                  <p class="text-xl font-semibold">{{ metrics.total_customers }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Chart and Most Sold Items -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Overview Chart -->
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <div class="flex items-center gap-2">
                <CalendarIcon class="w-5 h-5 text-gray-500" />
                <h3 class="text-lg font-medium text-gray-900">Revenue Overview</h3>
              </div>
              <div class="relative">
                <Datepicker
                  v-model="dateRange"
                  range
                  :enable-time-picker="false"
                  format="MMM yyyy"
                  :min-date="new Date(2023, 0, 1)"
                  :max-date="new Date()"
                  placeholder="Select date range"
                  :clearable="false"
                  class="revenue-datepicker"
                  @update:modelValue="updateChartData"
                  auto-apply
                  :preview-format="(date) => formatPreview(date)"
                  menu-class-name="dp-custom-menu"
                >
                  <template #trigger>
                    <div class="dp__custom_input">
                      <input
                        :value="dateRange ? formatPreview(dateRange[0]) + ' - ' + formatPreview(dateRange[1]) : ''"
                        readonly
                        placeholder="Select date range"
                        class="w-full cursor-pointer"
                      />
                      <CalendarDaysIcon class="w-5 h-5 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2" />
                    </div>
                  </template>
                </Datepicker>
              </div>
            </div>
            <div class="h-64">
              <Bar
                v-if="monthlyRevenue.length"
                :data="chartData"
                :options="chartOptions"
              />
            </div>
          </div>

          <!-- Most Sold Items -->
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-lg font-medium mb-4">Most Sold Items</h3>
            <div class="space-y-4">
              <div
                v-for="item in mostSoldItems"
                :key="item.name"
                class="flex items-center"
              >
                <span class="w-32 text-sm truncate" :title="item.name">{{ item.name }}</span>
                <div class="flex-1 mx-4">
                  <div class="h-2 bg-gray-200 rounded-full">
                    <div
                      class="h-2 bg-blue-600 rounded-full"
                      :style="{ width: `${item.percentage}%` }"
                    ></div>
                  </div>
                </div>
                <span class="text-sm font-medium w-14 text-right">{{ item.percentage }}%</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Latest Orders -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
          <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-medium">Latest Orders</h3>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    v-for="header in tableHeaders"
                    :key="header"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    {{ header }}
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="order in latestOrders" :key="order.id">
                  <td class="px-6 py-4 whitespace-nowrap">{{ order.product }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">#{{ order.reference_number }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ order.date }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ order.customer }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="[
                        'px-2 py-1 text-xs rounded-full uppercase',
                        order.status === 'Delivered'
                          ? 'bg-green-100 text-green-800'
                          : order.status === 'Pending'
                          ? 'bg-yellow-100 text-yellow-600'
                          : 'bg-yellow-100 text-yellow-400',
                      ]"
                    >
                      {{ order.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">₱{{ order.amount }}</td>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, usePage } from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";
import {
  DollarSignIcon,
  CalendarIcon,
  CalendarDaysIcon,
  BarChart3Icon,
  PackageIcon,
  UsersIcon,
  PhilippinePeso,
  ActivityIcon
} from "lucide-vue-next";
import Sidebar from "@/Components/Sidebar.vue";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
  Filler
} from 'chart.js';
import { Bar } from 'vue-chartjs';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

// Register ChartJS components
ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

const page = usePage();
const userName = computed(() => page.props.auth?.user?.first_name || "User");
const metrics = computed(() => page.props.metrics);
const mostSoldItems = computed(() => page.props.mostSoldItems);
const latestOrders = computed(() => page.props.latestOrders);
const monthlyRevenue = computed(() => page.props.monthlyRevenue || []);

// Initialize date range
const dateRange = ref([
  new Date(new Date().setMonth(new Date().getMonth() - 5)), // 6 months ago
  new Date() // today
]);

// Update chart data based on date range
const updateChartData = async () => {
  try {
    const response = await axios.get('/api/revenue', {
      params: {
        start_date: dateRange.value[0].toISOString().split('T')[0],
        end_date: dateRange.value[1].toISOString().split('T')[0]
      }
    });
    // Update the chart data...
  } catch (error) {
    console.error('Failed to fetch revenue data:', error);
  }
};

// Chart configuration
const chartData = computed(() => ({
  labels: monthlyRevenue.value.map(item => item.month),
  datasets: [
    {
      label: 'Monthly Revenue',
      data: monthlyRevenue.value.map(item => item.total),
      backgroundColor: 'rgba(79, 70, 229, 0.8)',
      borderColor: 'rgba(79, 70, 229, 1)',
      borderWidth: 1,
      borderRadius: 5,
      maxBarThickness: 50
    }
  ]
}));

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
    },
    tooltip: {
      callbacks: {
        label: (context) => `₱${context.formattedValue}`
      }
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
        callback: (value) => `₱${value}`
      }
    },
    x: {
      grid: {
        display: false
      }
    }
  }
};

const tableHeaders = [
  "Products",
  "Order ID",
  "Date",
  "Customer name",
  "Status",
  "Amount",
];

const formatPreview = (date) => {
  if (!date) return '';
  const options = { month: 'short', year: 'numeric' };
  return new Date(date).toLocaleDateString('en-US', options);
};
</script>

<style>
/* Updated Datepicker Styles */
.dp__custom_input {
  position: relative;
  padding: 8px 12px;
  font-size: 0.875rem;
  line-height: 1.25rem;
  border-radius: 0.375rem;
  border: 1px solid #e5e7eb;
  background-color: #ffffff;
  width: 200px;
  color: #374151;
  cursor: pointer;
}

.dp__custom_input:hover {
  border-color: #d1d5db;
}

.dp__custom_input input {
  border: none;
  outline: none;
  background: transparent;
  padding-right: 28px;
  cursor: pointer;
}

/* Hide default datepicker input */
.revenue-datepicker .dp__input {
  display: none;
}

.dp__month_year_row button:first-child,
.dp__month_year_row button:last-child {
  /* Style for prev/next month buttons */
  padding: 4px !important;
  border-radius: 4px !important;
}

.dp__month_year_row button:first-child:hover,
.dp__month_year_row button:last-child:hover {
  background-color: #f3f4f6 !important;
}

/* Previous styles remain the same */
.dp-custom-menu {
  margin-top: 4px;
  border: 1px solid #e5e7eb !important;
  border-radius: 0.375rem !important;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
  background-color: white !important;
}

.dp__today {
  border: 1px solid #6366f1 !important;
}

.dp__active_date {
  background-color: #6366f1 !important;
  color: white !important;
}

.dp__range_start,
.dp__range_end {
  background-color: #6366f1 !important;
  color: white !important;
}

.dp__range_between {
  background-color: #e0e7ff !important;
  color: #4f46e5 !important;
}

.dp__action_button {
  color: #6366f1 !important;
  font-weight: 500;
}

.dp__action_button:hover {
  background-color: #e0e7ff !important;
  color: #4f46e5 !important;
}

.dp__month_year_select {
  color: #1f2937 !important;
  font-weight: 500;
}

.dp__calendar_header_item {
  color: #6b7280 !important;
  font-weight: 500;
  height: 32px !important;
}

.dp__cell_inner {
  height: 32px !important;
  width: 32px !important;
  font-weight: 400;
  color: #1f2937;
}

.dp__arrow_top {
  display: none !important;
}

.dp__month_year_row {
  margin: 16px 16px 8px 16px !important;
}

.dp__calendar_header {
  padding: 0 16px !important;
}

.dp__calendar {
  padding: 0 16px 16px 16px !important;
}

.dp__action_row {
  padding: 8px 16px !important;
  border-top: 1px solid #e5e7eb !important;
}

.dp__selection_preview {
  font-weight: 400;
  color: #6b7280;
}

.dp__action_buttons {
  display: flex;
  gap: 8px;
}
</style>
