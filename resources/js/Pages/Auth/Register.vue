<template>
  <div class="min-h-screen main">
    <NavLink />

    <div
      class="flex min-h-[calc(95vh-80px)] items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
    >
      <div class="w-full max-w-md">
        <div class="bg-white shadow-lg rounded-lg px-8 py-10">
          <!-- Header -->
          <div class="mb-8">
            <h2 class="text-3xl font-bold text-center text-gray-900">Create Account</h2>
            <p class="mt-2 text-center text-gray-600">
              Please fill in the details to create your account.
            </p>
          </div>

          <!-- Form -->
          <form @submit.prevent="submitForm" class="space-y-6">
            <!-- First Name -->
            <div>
              <label for="firstname" class="block text-sm font-medium text-gray-700">
                First Name
              </label>
              <div class="mt-1 relative">
                <div
                  class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                >
                  <User class="h-5 w-5 text-gray-400" />
                </div>
                <input
                  id="firstname"
                  v-model="form.first_name"
                  type="text"
                  @blur="validateName(form.first_name, 'first_name')"
                  class="block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-navy-500 focus:border-navy-500"
                  :class="{
                    'border-red-500':
                      validationErrors.first_name || form.errors.first_name,
                  }"
                  placeholder="John"
                />
                <div
                  v-if="validationErrors.first_name || form.errors.first_name"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center"
                >
                  <AlertCircle class="h-5 w-5 text-red-500" />
                </div>
              </div>
              <small class="text-red-700">{{
                form.errors.first_name || validationErrors.first_name
              }}</small>
            </div>

            <!-- Last Name -->
            <div>
              <label for="lastname" class="block text-sm font-medium text-gray-700">
                Last Name
              </label>
              <div class="mt-1 relative">
                <div
                  class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                >
                  <User class="h-5 w-5 text-gray-400" />
                </div>
                <input
                  id="lastname"
                  v-model="form.last_name"
                  type="text"
                  @blur="validateName(form.last_name, 'last_name')"
                  class="block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-navy-500 focus:border-navy-500"
                  :class="{
                    'border-red-500': validationErrors.last_name || form.errors.last_name,
                  }"
                  placeholder="Doe"
                />
                <div
                  v-if="validationErrors.last_name || form.errors.last_name"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center"
                >
                  <AlertCircle class="h-5 w-5 text-red-500" />
                </div>
              </div>
              <small class="text-red-700">{{
                form.errors.last_name || validationErrors.last_name
              }}</small>
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">
                Email
              </label>
              <div class="mt-1 relative">
                <div
                  class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                >
                  <Mail class="h-5 w-5 text-gray-400" />
                </div>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  class="block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-navy-500 focus:border-navy-500"
                  :class="{ 'border-red-500': form.errors.email }"
                  placeholder="john.doe@example.com"
                />
                <div
                  v-if="form.errors.email"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center"
                >
                  <AlertCircle class="h-5 w-5 text-red-500" />
                </div>
              </div>
              <small class="text-red-700">{{ form.errors.email }}</small>
            </div>

            <!-- Password -->
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700">
                Password
              </label>
              <div class="mt-1 relative">
                <div
                  class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                >
                  <Lock class="h-5 w-5 text-gray-400" />
                </div>
                <input
                  id="password"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  @blur="validatePassword(form.password)"
                  class="block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-navy-500 focus:border-navy-500"
                  :class="{
                    'border-red-500': validationErrors.password || form.errors.password,
                  }"
                  placeholder="••••••••"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center"
                >
                  <Eye
                    v-if="showPassword"
                    class="h-5 w-5 text-gray-400 hover:text-gray-500"
                  />
                  <EyeOff v-else class="h-5 w-5 text-gray-400 hover:text-gray-500" />
                </button>
              </div>
              <small class="text-red-700">{{
                form.errors.password || validationErrors.password
              }}</small>
            </div>

            <!-- Confirm Password -->
            <div>
              <label
                for="password_confirmation"
                class="block text-sm font-medium text-gray-700"
              >
                Confirm Password
              </label>
              <div class="mt-1 relative">
                <div
                  class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                >
                  <Lock class="h-5 w-5 text-gray-400" />
                </div>
                <input
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  type="password"
                  class="block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-navy-500 focus:border-navy-500"
                  :class="{ 'border-red-500': form.errors.password_confirmation }"
                  placeholder="••••••••"
                />
              </div>
              <small class="text-red-700">{{ form.errors.password_confirmation }}</small>
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-navy-900 hover:bg-navy-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <Loader2 v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5" />
              {{ form.processing ? "Creating Account..." : "Create Account" }}
            </button>
          </form>

          <!-- Login Link -->
          <p class="mt-6 text-center text-sm text-gray-600">
            Already have an account?
            <Link
              href="/customer/login"
              class="font-medium text-navy-600 hover:text-navy-500 hover:underline"
            >
              Sign in
            </Link>
          </p>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="footer-color">
      <div class="mx-auto max-w-7xl px-4 py-4">
        <p class="text-center text-sm text-gray-500">Artzwork PC © 2024</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import { Eye, EyeOff, User, Mail, Lock, AlertCircle, Loader2 } from "lucide-vue-next";
import NavLink from "../../Components/NavLink.vue";

// State
const showPassword = ref(false);
const validationErrors = ref({
  first_name: "",
  last_name: "",
  password: "",
});

const form = useForm({
  first_name: "",
  last_name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

// Validation functions
const validateName = (name, field) => {
  // Skip validation if field is empty
  if (!name) {
    validationErrors.value[field] = "";
    return true;
  }

  const nameRegex = /^[A-Za-z]{2,}$/;
  if (!nameRegex.test(name)) {
    validationErrors.value[field] = `${
      field.charAt(0).toUpperCase() + field.slice(1)
    } must contain at least 2 letters and no numbers or special characters`;
    return false;
  }
  validationErrors.value[field] = "";
  return true;
};

const validatePassword = (password) => {
  // Skip validation if password is empty
  if (!password) {
    validationErrors.value.password = "";
    return true;
  }

  const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
  if (!passwordRegex.test(password)) {
    validationErrors.value.password =
      "Password must be at least 8 characters long and include at least one letter, one number, and one special character";
    return false;
  }
  validationErrors.value.password = "";
  return true;
};

// Form submission with validation
const submitForm = () => {
  const isFirstNameValid =
    !form.first_name || validateName(form.first_name, "first_name");
  const isLastNameValid = !form.last_name || validateName(form.last_name, "last_name");
  const isPasswordValid = !form.password || validatePassword(form.password);

  if (isFirstNameValid && isLastNameValid && isPasswordValid) {
    form.post(route("customer.register"), {
      onSuccess: () => {
        window.location.href = route("verification.notice");
      },
      onFinish: () => {
        if (!form.hasErrors) {
          form.reset("password", "password_confirmation");
        }
      },
    });
  }
};
</script>

<style scoped>
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
