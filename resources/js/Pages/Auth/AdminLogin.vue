<template>
  <div class="min-h-screen bg-gray-100 flex flex-col justify-center">
    <!-- Logo Section -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md mb-6">
      <h2 class="mt-4 text-center text-2xl font-bold tracking-tight text-gray-900">
        Admin Portal
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Sign in to your administrator account
      </p>
    </div>

    <!-- Login Form Card -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow-xl rounded-lg sm:px-10">
        <form @submit.prevent="submitForm" class="space-y-6">
          <!-- Email Input -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Email address
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
                required
                class="appearance-none block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-navy-500 focus:border-navy-500 sm:text-sm"
                :class="{ 'border-red-500': form.errors.email }"
                placeholder="admin@example.com"
              />
              <div
                v-if="form.errors.email"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <AlertCircle class="h-5 w-5 text-red-500" />
              </div>
            </div>
            <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">
              {{ form.errors.email }}
            </p>
          </div>

          <!-- Password Input -->
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
                required
                class="appearance-none block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-navy-500 focus:border-navy-500 sm:text-sm"
                :class="{ 'border-red-500': form.errors.password }"
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
            <p v-if="form.errors.password" class="mt-2 text-sm text-red-600">
              {{ form.errors.password }}
            </p>
          </div>

          <!-- Remember Me & Forgot Password -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember"
                v-model="form.remember"
                type="checkbox"
                class="h-4 w-4 text-navy-600 focus:ring-navy-500 border-gray-300 rounded"
              />
              <label for="remember" class="ml-2 block text-sm text-gray-700">
                Remember me
              </label>
            </div>

            <div class="text-sm">
              <Link
                class="font-medium text-navy-600 hover:text-navy-500"
              >
                Forgot your password?
              </Link>
            </div>
          </div>

          <!-- Submit Button -->
          <div>
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-navy-600 hover:bg-navy-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <Loader2 v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5" />
              {{ form.processing ? "Signing in..." : "Sign in" }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Footer -->
    <p class="mt-8 text-center text-sm text-gray-600">
      Need help? Contact
      <a
        href="mailto:support@example.com"
        class="font-medium text-navy-600 hover:text-navy-500"
      >
        IT Support
      </a>
    </p>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import { Eye, EyeOff, Mail, Lock, AlertCircle, Loader2 } from "lucide-vue-next";

const showPassword = ref(false);

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submitForm = () => {
  form.post(route("admin.login"), {
    onFinish: () => {
      if (!form.hasErrors) {
        form.reset("password");
      }
    },
  });
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
