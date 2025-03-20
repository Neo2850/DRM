<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <NavLink />

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
      <div class="max-w-3xl mx-auto">
        <!-- Progress Steps -->
        <div class="mb-8">
          <div class="flex items-center justify-between relative">
            <div class="absolute left-0 right-0 top-1/2 h-0.5 bg-gray-200 -z-10"></div>
            <div
              class="absolute left-0 right-0 top-1/2 h-0.5 bg-green-500 -z-10 transition-all duration-300"
              :style="{ width: `${(currentStep - 1) * 50}%` }"
            ></div>
            <div v-for="step in steps" :key="step.id" class="flex flex-col items-center">
              <div
                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium transition-colors"
                :class="[
                  step.id <= currentStep
                    ? 'bg-green-500 text-white'
                    : 'bg-gray-200 text-gray-600',
                ]"
              >
                {{ step.id }}
              </div>
              <span class="mt-2 text-sm text-gray-600">{{ step.name }}</span>
            </div>
          </div>
        </div>

        <!-- Form Content -->
        <div class="bg-white rounded-lg shadow-sm p-6 md:p-8">
          <!-- Step 1: User Account -->
          <form v-if="currentStep === 1" @submit.prevent="nextStep" class="space-y-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">User Account</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  First Name
                </label>
                <input
                  v-model="customer.first_name"
                  type="text"
                  class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                  readonly
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Last Name
                </label>
                <input
                  v-model="customer.last_name"
                  type="text"
                  class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                  readonly
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Email Address
              </label>
              <div class="flex items-center">
                <input
                  v-model="customer.email"
                  type="email"
                  :class="[
                    'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2',
                    customer.email_verified_at
                      ? ' border-green-500'
                      : 'focus:ring-navy-500',
                  ]"
                  readonly
                />
                <span v-if="customer.email_verified_at" class="ml-2 text-green-500">
                  Verified
                </span>
              </div>
            </div>

            <div class="flex justify-between pt-4">
              <button
                type="button"
                class="px-6 py-2 border rounded-lg text-gray-600 hover:bg-gray-50"
                @click="$router.back()"
              >
                Back
              </button>
              <button
                type="submit"
                class="px-6 py-2 bg-navy-600 text-white rounded-lg hover:bg-navy-700"
              >
                Continue
              </button>
            </div>
          </form>

          <!-- Step 2: Personal Information -->
          <form v-if="currentStep === 2" @submit.prevent="nextStep" class="space-y-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">
              Personal Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Username
                </label>
                <input
                  v-model="form.username"
                  type="text"
                  class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Gender
                </label>
                <select
                  v-model="form.gender"
                  class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                >
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Birthday
                </label>
                <input
                  v-model="form.birthday"
                  type="date"
                  class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Phone Number
                </label>
                <input
                  v-model="form.phone"
                  type="tel"
                  class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                />
              </div>
              <small class="text-red-500">{{ form.errors.phone }}</small>
            </div>

            <div class="flex justify-between pt-4">
              <button
                type="button"
                class="px-6 py-2 border rounded-lg text-gray-600 hover:bg-gray-50"
                @click="currentStep--"
              >
                Back
              </button>
              <button
                type="submit"
                class="px-6 py-2 bg-navy-600 text-white rounded-lg hover:bg-navy-700"
              >
                Continue
              </button>
            </div>
          </form>

          <!-- Step 3: Checkout Information -->
          <form v-if="currentStep === 3" @submit.prevent="submitForm" class="space-y-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">
              Checkout Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Province
                </label>
                <select
                  v-model="form.province"
                  class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                >
                  <option value="metro-manila">Metro Manila</option>
                  <!-- Add more provinces -->
                </select>
                <small class="text-red-500">{{ form.errors.province }}</small>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"> City </label>
                <select
                  v-model="form.city"
                  class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
                >
                  <option value="caloocan">Caloocan City</option>
                  <!-- Add more cities -->
                </select>
                <small class="text-red-500">{{ form.errors.city }}</small>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Zip Code
              </label>
              <input
                v-model="form.zip_code"
                type="text"
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
              />
              <small class="text-red-500">{{ form.errors.zip_code }}</small>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Complete Address
              </label>
              <textarea
                v-model="form.address"
                rows="4"
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-navy-500"
              ></textarea>
              <small class="text-red-500">{{ form.errors.address }}</small>
            </div>

            <div class="flex justify-between pt-4">
              <button
                type="button"
                class="px-6 py-2 border rounded-lg text-gray-600 hover:bg-gray-50"
                @click="currentStep--"
              >
                Back
              </button>

              <button
                type="submit"
                class="px-6 py-2 bg-navy-600 text-white rounded-lg hover:bg-navy-700"
                :disabled="form.processing"
              >
                <span v-if="form.processing">Updating...</span>
                <span v-else>Complete</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import { Link, usePage, useForm } from "@inertiajs/vue3";
import { UploadIcon } from "lucide-vue-next";
import NavLink from "@/Components/NavLink.vue";

const customer = usePage().props.customer;
// Steps configuration
const steps = [
  { id: 1, name: "User Account" },
  { id: 2, name: "Personal Information" },
  { id: 3, name: "Checkout Information" },
];

// Form state
const currentStep = ref(1);
const form = useForm({
  firstName: customer.first_name || "",
  lastName: customer.last_name || "",
  email: customer.email || "",
  username: customer.username || "",
  gender: customer.gender || "",
  birthday: customer.birthday || "",
  phone: customer.phone || "",
  province: customer.addresses?.province || "",
  city: customer.addresses?.city || "",
  zip_code: customer.addresses?.zip_code || "",
  address: customer.addresses?.complete_address || "",
});

// Methods

const nextStep = () => {
  if (!customer.email_verified_at) {
    return; // Prevent proceeding if email isn't verified
  }
  currentStep.value++;
};

const submitForm = () => {
  form.post(route("customer.register.process.update"), {
    onSuccess: () => {
      console.log("Profile updated successfully.");
    },
    onError: (errors) => {
      console.error("Failed to update profile:", errors);
    },
    preserveScroll: true,
  });
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

.focus\:ring-navy-500:focus {
  --tw-ring-color: #1e2b8f;
}

/* Hide number input spinners */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Add tooltip styles */
[data-tooltip] {
  position: relative;
}

[data-tooltip]:hover::after {
  content: attr(data-tooltip);
  position: absolute;
  bottom: 100%;
  right: 0;
  transform: translateY(-4px);
  background-color: #1f2937;
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  white-space: nowrap;
  z-index: 10;
}
</style>
