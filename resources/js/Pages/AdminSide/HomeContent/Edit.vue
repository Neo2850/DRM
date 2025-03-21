<template>
  <Head title=" | Edit Home Content"></Head>
  <div class="min-h-screen bg-gray-50">
    <Sidebar></Sidebar>
    <main class="lg:ml-64 min-h-screen">
      <Header title="Edit Home Content" :showSearch="false"></Header>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow">
          <div class="p-6">
            <form @submit.prevent="updateForm">
              <!-- Hero Input -->
              <div class="mb-6">
                <label for="hero" class="block text-sm font-medium text-gray-700 mb-2">
                  Hero Title
                </label>
                <input
                  id="hero"
                  v-model="form.hero"
                  type="text"
                  @focus="form.clearErrors('hero')"
                  :class="[
                    'w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500',
                    { 'border-red-500': form.errors.hero },
                  ]"
                />
                <small class="text-red-700">{{ form.errors.hero }}</small>
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
                  rows="4"
                ></textarea>
                <small class="text-red-700">{{ form.errors.description }}</small>
              </div>

              <!-- Image Upload -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Background Image</label>
                <div
                  class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-lg"
                  :class="['border-gray-300', { 'border-red-500': form.errors.image }]"
                  @dragover.prevent
                  @drop="handleDrop"
                  @click="form.clearErrors('image')"
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
                          accept="image/*"
                          @change="handleFileUpload"
                        />
                      </label>
                      <p class="pl-1">or drag and drop</p>
                    </div>

                    <!-- File Format Info -->
                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                  </div>
                </div>
                <small class="text-red-700">{{ form.errors.image }}</small>
              </div>

              <!-- Active Status -->
              <div class="mb-6">
                <label class="flex items-center">
                  <input
                    type="checkbox"
                    v-model="form.is_active"
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                  />
                  <span class="ml-2 text-sm text-gray-600">Set as active hero section</span>
                </label>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end space-x-4">
                <Link
                  :href="route('home-content.index')"
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
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import Sidebar from "../../../Components/Sidebar.vue";
import Header from "../../../Components/Header.vue";

const props = defineProps({
  content: {
    type: Object,
    required: true,
  },
});

const imagePreview = ref(props.content.image ? '/storage/' + props.content.image : null);

const form = useForm({
  hero: props.content.hero,
  description: props.content.description,
  image: null,
  is_active: props.content.is_active,
  _method: "PUT",
});

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.image = file;
    createImagePreview(file);
  }
};

const handleDrop = (event) => {
  event.preventDefault();
  const file = event.dataTransfer.files[0];
  if (file) {
    form.image = file;
    createImagePreview(file);
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
  imagePreview.value = null;
  form.image = null;
};

const updateForm = () => {
  form.post(route("home-content.update", props.content.id), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset("image");
      imagePreview.value = form.image || null;
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
