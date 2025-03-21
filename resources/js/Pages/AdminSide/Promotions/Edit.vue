<template>
  <Head title=" | Edit Promotion"></Head>
  <div class="min-h-screen bg-gray-50">
    <Sidebar></Sidebar>
    <main class="lg:ml-64 min-h-screen">
      <Header title="Edit Promotion" :showSearch="false"></Header>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow">
          <div class="p-6">
            <form @submit.prevent="updateForm">
              <!-- Name Input -->
              <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                  Name
                </label>
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  @focus="form.clearErrors('name')"
                  :class="[
                    'w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500',
                    { 'border-red-500': form.errors.name },
                  ]"
                />
                <small class="text-red-700">{{ form.errors.name }}</small>
              </div>

              <!-- Description Input -->
              <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                  Description
                </label>
                <textarea
                  id="description"
                  v-model="form.description"
                  @focus="form.clearErrors('description')"
                  :class="[
                    'w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500',
                    { 'border-red-500': form.errors.description },
                  ]"
                  rows="3"
                ></textarea>
                <small class="text-red-700">{{ form.errors.description }}</small>
              </div>

              <!-- Code Input -->
              <div class="mb-6">
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                  Promo Code
                </label>
                <input
                  id="code"
                  v-model="form.code"
                  type="text"
                  @focus="form.clearErrors('code')"
                  :class="[
                    'w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500',
                    { 'border-red-500': form.errors.code },
                  ]"
                />
                <small class="text-red-700">{{ form.errors.code }}</small>
              </div>

              <!-- Type Selection -->
              <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                  Type
                </label>
                <select
                  id="type"
                  v-model="form.type"
                  @focus="form.clearErrors('type')"
                  :class="[
                    'w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500',
                    { 'border-red-500': form.errors.type },
                  ]"
                >
                  <option value="item">Item</option>
                  <option value="shipping">Shipping</option>
                </select>
                <small class="text-red-700">{{ form.errors.type }}</small>
              </div>

              <!-- Discount Input -->
              <div class="mb-6">
                <label for="discount" class="block text-sm font-medium text-gray-700 mb-2">
                  Discount (%)
                </label>
                <input
                  id="discount"
                  v-model="form.discount"
                  type="number"
                  min="0"
                  max="100"
                  @focus="form.clearErrors('discount')"
                  :class="[
                    'w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500',
                    { 'border-red-500': form.errors.discount },
                  ]"
                />
                <small class="text-red-700">{{ form.errors.discount }}</small>
              </div>

              <!-- Date Range -->
              <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                  <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Start Date
                  </label>
                  <input
                    id="start_date"
                    v-model="form.start_date"
                    type="date"
                    @focus="form.clearErrors('start_date')"
                    :class="[
                      'w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500',
                      { 'border-red-500': form.errors.start_date },
                    ]"
                  />
                  <small class="text-red-700">{{ form.errors.start_date }}</small>
                </div>
                <div>
                  <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                    End Date
                  </label>
                  <input
                    id="end_date"
                    v-model="form.end_date"
                    type="date"
                    @focus="form.clearErrors('end_date')"
                    :class="[
                      'w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500',
                      { 'border-red-500': form.errors.end_date },
                    ]"
                  />
                  <small class="text-red-700">{{ form.errors.end_date }}</small>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end space-x-4">
                <Link
                  :href="route('promotions.index')"
                  class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50"
                >
                  Cancel
                </Link>
                <button
                  type="submit"
                  class="px-4 py-2 bg-navy-600 text-white rounded-md hover:bg-navy-700"
                  :disabled="form.processing"
                >
                  <span v-if="form.processing">Updating...</span>
                  <span v-else>Update</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import Sidebar from "../../../Components/Sidebar.vue";
import Header from "../../../Components/Header.vue";

const props = defineProps({
  promotion: {
    type: Object,
    required: true,
  },
});

const form = useForm({
  name: props.promotion.name,
  description: props.promotion.description,
  code: props.promotion.code,
  type: props.promotion.type,
  discount: props.promotion.discount,
  start_date: props.promotion.start_date,
  end_date: props.promotion.end_date,
  _method: "PUT",
});

const updateForm = () => {
  form.post(route("promotions.update", props.promotion.id), {
    preserveScroll: true,
  });
};
</script>

<style scoped>
.bg-navy-600 {
  background-color: #1a237e;
}

.bg-navy-700 {
  background-color: #151c63;
}

.hover\:bg-navy-700:hover {
  background-color: #151c63;
}
</style>
