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
            <h2 class="text-3xl font-bold text-center text-gray-900">Welcome</h2>
            <p class="mt-2 text-center text-gray-600">Sign in to your account</p>
          </div>

          <!-- Form -->
          <form @submit.prevent="submitForm" class="space-y-6">
            <!-- Email -->
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
                  class="appearance-none block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-navy-500 focus:border-navy-500 sm:text-sm"
                  :class="{ 'border-red-500': form.errors.email }"
                  placeholder="you@example.com"
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
              <small class="text-red-700">{{ form.errors.password }}</small>
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
                  class="font-medium text-navy-600 hover:text-navy-500 hover:underline"
                >
                  Forgot password?
                </Link>
              </div>
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-navy-900 hover:bg-navy-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <Loader2 v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5" />
              {{ form.processing ? "Signing in..." : "Sign in" }}
            </button>
          </form>

          <!-- Register Link -->
          <p class="mt-6 text-center text-sm text-gray-600">
            Don't have an account?
            <Link
              href="/customer/register"
              class="font-medium text-navy-600 hover:text-navy-500 hover:underline"
            >
              Create one
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
import { Eye, EyeOff, Mail, Lock, AlertCircle, Loader2 } from "lucide-vue-next";
import NavLink from "../../Components/NavLink.vue";

const showPassword = ref(false);

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submitForm = () => {
  form.post(route("customer.login"), {
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
