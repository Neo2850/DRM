<template>
  <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center px-6">
    <div class="text-center">
      <!-- Error Code -->
      <h1 class="text-9xl font-bold text-navy-900">404</h1>

      <!-- Error Message -->
      <h2 class="text-2xl md:text-3xl font-medium text-gray-800 mt-4">
        Page Not Found
      </h2>
      <p class="text-gray-600 mt-2 mb-8">
        The page you're looking for doesn't exist or has been moved.
      </p>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <Link
          href="/"
          class="px-6 py-3 bg-navy-900 text-white rounded-lg hover:bg-navy-700 transition-colors duration-200"
        >
          Go to Home
        </Link>
        <button
          @click="$inertia.back()"
          class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200"
        >
          Go Back
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
