<template>
  <Head title=" | Edit Brands"></Head>
  <div class="min-h-screen bg-gray-50">
    <!-- Mobile Sidebar Toggle -->
    <Sidebar></Sidebar>
    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
      <!-- Header -->
      <Header title="Edit Brand" :showSearch="false"></Header>

      <!-- Create Brands Form -->
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

              <!-- Image Upload -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                <div
                  class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg"
                  @dragover.prevent
                  @drop="handleDrop"
                >
                  <div class="space-y-1 text-center">
                    <!-- Preview Section -->
                    <div v-if="imagePreview" class="mb-4">
                      <img
                        :src="imagePreview"
                        alt="Preview"
                        class="mx-auto h-32 w-auto"
                      />
                      <button
                        type="button"
                        class="mt-2 text-red-600 text-sm hover:underline"
                        @click="removeImage"
                      >
                        Remove Image
                      </button>
                    </div>

                    <!-- Upload Instructions -->
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
                          accept=".jpg,.jpeg,.png,.gif"
                          @change="handleFileUpload"
                        />
                      </label>
                      <p class="pl-1">or drag and drop</p>
                    </div>

                    <!-- File Format Info -->
                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                  </div>
                </div>

                <!-- Hidden Input for Image Removal -->
                <input
                  type="hidden"
                  name="remove_image"
                  :value="imagePreview === null ? 1 : 0"
                />

                <small class="text-red-700">{{ form.errors.image }}</small>
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
import { ref, watch } from "vue";
import { Link } from "@inertiajs/vue3";
import Sidebar from "../../../Components/Sidebar.vue";
import Header from "../../../Components/Header.vue";

// Reactive States
const props = defineProps({
  brands: {
    type: Object,
    required: true,
  },
});
const imagePreview = ref(props.brands?.image || null);
const form = useForm({
  name: props.brands?.name,
  slug: props.brands?.slug,
  sku: props.brands?.sku,
  image: null,
  _method: "PUT",
});

// Watcher for Slug and SKU
watch(
  () => form.name,
  (newName) => {
    form.slug = newName
      .toLowerCase()
      .trim()
      .replace(/[\s\/]+/g, "-");
  }
);

// Methods
const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.image = file; // Bind the file to the form
    createImagePreview(file); // Generate a preview
  }
};

const handleDrop = (event) => {
  event.preventDefault();
  const file = event.dataTransfer.files[0];
  if (file) {
    form.image = file; // Bind the file to the form
    createImagePreview(file); // Generate a preview
  }
};

const createImagePreview = (file) => {
  const reader = new FileReader();
  reader.onload = (e) => {
    imagePreview.value = e.target.result;
  };
  reader.readAsDataURL(file);
};

const removeImage = () => {
  imagePreview.value = null; // Clear the preview
  form.image = null; // Remove the image from the form
};

const updateForm = () => {
  form.post(route("brands.update", props.brands.id), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset("image");
      imagePreview.value = form.image || null; // Update preview on success
    },
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
