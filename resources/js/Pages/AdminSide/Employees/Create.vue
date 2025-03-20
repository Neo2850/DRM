<template>
  <Head title=" | Create Employee" />
  <div class="min-h-screen bg-gray-50">
    <!-- Mobile Sidebar Toggle -->
    <Sidebar> </Sidebar>
    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
      <!-- Header -->
      <Header title="Create Employee" :showSearch="false"> </Header>

      <!-- Create Category Form -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow">
          <div class="p-6">
            <form @submit.prevent="createForm">
              <!-- Name Input -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1"
                    >First Name</label
                  >
                  <input
                    v-model="form.first_name"
                    type="text"
                    pattern="^[A-Za-z\s]+$"
                    title="Only letters and spaces are allowed"
                    @focus="form.clearErrors('first_name')"
                    :class="[
                      'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                      { 'border-red-500': form.errors.first_name },
                    ]"
                  />
                  <small class="text-red-700">{{ form.errors.first_name }}</small>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1"
                    >Last Name</label
                  >
                  <input
                    v-model="form.last_name"
                    type="text"
                    pattern="^[A-Za-z\s]+$"
                    title="Only letters and spaces are allowed"
                    @focus="form.clearErrors('last_name')"
                    :class="[
                      'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                      { 'border-red-500': form.errors.last_name },
                    ]"
                  />
                  <small class="text-red-700">{{ form.errors.last_name }}</small>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1"
                    >Email Address</label
                  >
                  <input
                    v-model="form.email"
                    type="email"
                    @focus="form.clearErrors('email')"
                    :class="[
                      'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                      { 'border-red-500': form.errors.email },
                    ]"
                  />
                  <small class="text-red-700">{{ form.errors.email }}</small>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1"
                    >Phone Number</label
                  >
                  <input
                    v-model="form.phone_number"
                    type="tel"
                    pattern="[0-9]+"
                    @focus="form.clearErrors('phone_number')"
                    :class="[
                      'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                      { 'border-red-500': form.errors.phone_number },
                    ]"
                  />
                  <small class="text-red-700">{{ form.errors.phone_number }}</small>
                </div>
              </div>

              <!-- Role and Status -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                  <input
                    v-model="form.role"
                    type="text"
                    @focus="form.clearErrors('role')"
                    :class="[
                      'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                      { 'border-red-500': form.errors.role },
                    ]"
                  />
                  <small class="text-red-700">{{ form.errors.role }}</small>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1"
                    >Status</label
                  >
                  <select
                    v-model="form.status"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    readonly
                    disabled
                  >
                    <option value="Inactive">Inactive</option>
                    <option value="Active">Active</option>
                  </select>
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1"
                    >Password</label
                  >
                  <input
                    v-model="form.password"
                    type="password"
                    pattern="^(?=.*[A-Z])(?=.*\d).+$"
                    @focus="form.clearErrors('password')"
                    :class="[
                      'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                      { 'border-red-500': form.errors.password },
                    ]"
                  />
                  <small class="text-red-700">{{ form.errors.password }}</small>
                  <small class="text-gray-500">Password must contain at least one capital letter and one number</small>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1"
                    >Confirm Password</label
                  >
                  <input
                    v-model="form.password_confirmation"
                    type="password"
                    @focus="form.clearErrors('password_confirmation')"
                    :class="[
                      'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                      { 'border-red-500': form.errors.password_confirmation },
                    ]"
                  />
                  <small class="text-red-700">{{
                    form.errors.password_confirmation
                  }}</small>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end space-x-4">
                <Link
                  :href="route('employees.index')"
                  class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50"
                >
                  Cancel
                </Link>
                <button
                  type="submit"
                  class="px-4 py-2 bg-navy-600 text-white rounded-md hover:bg-navy-700"
                  :disabled="form.processing"
                >
                  <span v-if="form.processing">Creating...</span>
                  <span v-else>Create</span>
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
import { ref, watch } from "vue";
import { Link } from "@inertiajs/vue3";
import Sidebar from "../../../Components/Sidebar.vue";
import Header from "../../../Components/Header.vue";

const form = useForm({
  first_name: "",
  last_name: "",
  email: "",
  phone_number: "",
  role: "",
  status: "Inactive",
  password: "",
  password_confirmation: "",
});

const createForm = () => {
  form.post(route("employees.store"));
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
