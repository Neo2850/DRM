<template>
  <Head title=" | Create Brand"></Head>
  <div class="min-h-screen bg-gray-50">
    <!-- Mobile Sidebar Toggle -->
    <Sidebar> </Sidebar>
    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
      <!-- Header -->
      <Header title="Create Brand" :showSearch="false"> </Header>

      <!-- Create Category Form -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow">
          <div class="p-6">
            <form @submit.prevent="submitForm">
              <!-- Name Input -->
              <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                  Name
                </label>
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <small class="text-red-700">{{ form.errors.name }}</small>
              </div>

              <!-- Slug Input -->
              <div class="mb-6">
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                  Slug
                </label>
                <input
                  id="slug"
                  v-model="form.slug"
                  type="text"
                  class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                  disabled
                />
                <small class="text-red-700">{{ form.errors.slug }}</small>
              </div>

              <!-- SKU Input -->
              <!-- <div class="mb-6">
                  <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                    SKU
                  </label>
                  <input
                    id="sku"
                    v-model="form.sku"
                    type="text"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    disabled
                  />
                  <small class="text-red-700">{{ form.errors.sku }}</small>
                </div> -->

              <!-- Image Upload -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Image
                </label>
                <div
                  class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg"
                  @dragover.prevent
                  @drop="handleDrop"
                >
                  <div class="space-y-1 text-center">
                    <!-- Image Previews -->
                    <div v-if="imagePreview" class="mb-4 relative">
                      <img
                        :src="imagePreview"
                        alt="Preview"
                        class="h-32 w-auto rounded-md shadow-md"
                      />
                      <button
                        @click="removeImage"
                        type="button"
                        class="absolute top-1 right-1 text-red-600 w-6 h-6 rounded-full flex items-center justify-center shadow-lg"
                      >
                        ✕
                      </button>
                    </div>

                    <!-- Upload Instructions (show only if no previews) -->
                    <div v-else class="flex text-sm text-gray-600">
                      <label
                        for="file-upload"
                        class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500"
                      >
                        <span>Upload a file</span>
                        <input
                          id="file-upload"
                          type="file"
                          class="sr-only"
                          accept="image/*"
                          @change="handleFileUpload"
                        />
                        <small class="text-red-700">{{ form.errors.image }}</small>
                      </label>
                      <p class="pl-1">or drag and drop</p>
                    </div>

                    <!-- File Format Info -->
                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                  </div>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end space-x-4">
                <Link
                  :href="route('brands.index')"
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
import Toast from "../../../Components/Toast.vue";

// State

const imagePreview = ref(null);

const form = useForm({
  name: "",
  slug: "",
  image: null,
});

watch(
  () => form.name,
  (newName) => {
    form.slug = newName.toLowerCase().trim().replace(/\s+/g, "-");
  }
);

// Methods
// Handle File Upload via Input
const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.image = file; // Append file to the form object
    createImagePreview(file);
  }
};

// Handle File Upload via Drag-and-Drop
const handleDrop = (event) => {
  event.preventDefault(); // Prevent default drag behaviors
  const file = event.dataTransfer.files[0];
  if (file) {
    form.image = file; // Append file to the form object
    createImagePreview(file);
  }
};

// Create Image Preview
const createImagePreview = (file) => {
  const reader = new FileReader();
  reader.onload = (e) => {
    imagePreview.value = e.target.result;
  };
  reader.readAsDataURL(file);
};

const removeImage = () => {
  imagePreview.value = null;
  form.image = null;
  // Reset the file input
  const fileInput = document.getElementById("file-upload");
  if (fileInput) fileInput.value = "";
};

const submitForm = () => {
  form.post(route("brands.store"), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  });
};

const logout = () => {
  // Implement logout logic
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
