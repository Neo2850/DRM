<template>
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-lg font-medium mb-4">Specifications</h2>
      <div class="space-y-4">
        <div v-for="(field, index) in fields" :key="index">
          <label class="block text-sm font-medium text-gray-700 mb-1">{{ field.label }}</label>
          <input
            v-model="specifications[field.key]"
            type="text"
            :placeholder="field.placeholder"
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { defineProps } from 'vue';

  // Props
  defineProps({
    specifications: {
      type: Object,
      required: true,
    },
    fields: {
      type: Array,
      required: true,
    },
  });
  </script>

  <style scoped>
  /* Add any specific styles if needed */
  </style>
