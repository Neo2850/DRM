<template>
    <Head title=" | Create Product"></Head>
  <div class="min-h-screen bg-gray-50">
    <!-- Mobile Sidebar Toggle -->
    <Sidebar></Sidebar>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
      <!-- Header -->
      <Header title="Create Product" :showSearch="false"></Header>

      <!-- Create Product Form -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form @submit.prevent="submitForm" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Main Form Content -->
          <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
              <h2 class="text-lg font-medium mb-4">Product Information</h2>

              <!-- Product Name -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1"
                  >Product Name</label
                >
                <input
                  v-model="form.name"
                  type="text"
                  @focus="clearRelatedErrors"
                  :class="[
                    'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                    { 'border-red-500': form.errors.name }
                  ]"
                />
                <small v-show="form.errors.name" class="text-red-700">{{ form.errors.name }}</small>
              </div>

              <!-- Slug and SKU -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                  <input
                    v-model="form.slug"
                    type="text"
                    disabled
                    :class="[
                      'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                      { 'border-red-500': form.errors.slug }
                    ]"
                  />
                  <small v-show="form.errors.slug" class="text-red-700">{{ form.errors.slug }}</small>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                  <input
                    v-model="form.sku"
                    disabled
                    type="text"
                    :class="[
                      'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                      { 'border-red-500': form.errors.sku }
                    ]"
                  />
                  <small v-show="form.errors.sku" class="text-red-700">{{ form.errors.sku }}</small>
                </div>
              </div>

              <!-- Description -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1"
                  >Description</label
                >
                <textarea
                  v-model="form.description"
                  rows="4"
                  @focus="form.clearErrors('description')"
                  :class="[
                    'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                    { 'border-red-500': form.errors.description }
                  ]"
                ></textarea>
                <small v-show="form.errors.description" class="text-red-700">{{ form.errors.description }}</small>
              </div>
              <!-- Image Upload -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Images</label>
                <div
                  class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-md"
                  :class="[
                    'border-gray-300',
                    { 'border-red-500': form.errors.image }
                  ]"
                  @dragover.prevent
                  @drop.prevent="handleDrop"
                  @click="form.clearErrors('image')"
                >
                  <div class="space-y-1 text-center">
                    <!-- Image Previews -->
                    <div v-if="imagePreviews.length" class="mb-4 flex flex-wrap gap-4 justify-center">
                      <div v-for="(preview, index) in imagePreviews" :key="index" class="relative group">
                        <img :src="preview" alt="Preview" class="h-32 w-auto rounded-md shadow-md" />
                        <button
                          @click="removeImage(index)"
                          type="button"
                          class="absolute top-1 right-1 text-red-600 w-6 h-6 rounded-full flex items-center justify-center shadow-lg"
                        >
                          ✕
                        </button>
                      </div>
                    </div>

                    <div v-else class="flex text-sm text-gray-600">
                      <label
                        for="file-upload"
                        class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500"
                      >
                        <span>Upload files</span>
                        <input
                          id="file-upload"
                          type="file"
                          multiple
                          accept="image/*"
                          class="sr-only"
                          @change="handleFileUpload"
                        />

                      </label>
                      <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB each</p>
                  </div>
                </div>
                <small v-show="form.errors.image" class="text-red-700">{{ form.errors.image }}</small>
              </div>
            </div>

            <!-- Specifications -->
            <!-- Specifications -->
            <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium mb-4">Specifications</h2>
            <div class="space-y-4">
                <div v-for="(spec, index) in currentSpecifications" :key="spec.id">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ spec.name }}
                </label>
                <input
                    v-model="form.specifications[index].value"
                    type="text"
                    :placeholder="`Enter ${spec.name}`"
                    @focus="form.clearErrors(`specifications.${index}.value`)"
                    :class="[
                    'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                    { 'border-red-500': form.errors[`specifications.${index}.value`] }
                    ]"
                />
                <input
                    type="hidden"
                    value="spec.id"
                    v-model="form.specifications[index].id"
                />
                <small v-show="form.errors[`specifications.${index}.value`]" class="text-red-700">
                    {{ form.errors[`specifications.${index}.value`] }}
                </small>
                </div>
            </div>
            </div>
          </div>

          <!-- Side Panel -->
          <div class="space-y-6">
            <!-- Price Section -->
            <div class="bg-white rounded-lg shadow p-6">
              <h2 class="text-lg font-medium mb-4">Price</h2>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                <input
                  v-model="form.price"
                  type="number"
                  placeholder="Enter price"
                  @focus="form.clearErrors('price')"
                  :class="[
                    'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                    { 'border-red-500': form.errors.price }
                  ]"
                />
                <small v-show="form.errors.price" class="text-red-700">{{ form.errors.price }}</small>
              </div>
            </div>

            <!-- Stock Section -->
            <div class="bg-white rounded-lg shadow p-6">
              <h2 class="text-lg font-medium mb-4">Stock</h2>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                <input
                  v-model="form.stock"
                  type="number"
                  placeholder="Enter Stock"
                  @focus="form.clearErrors('stock')"
                  :class="[
                    'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                    { 'border-red-500': form.errors.stock }
                  ]"
                />
                <small v-show="form.errors.stock" class="text-red-700">{{ form.errors.stock }}</small>
              </div>
            </div>

            <!-- Associations -->
            <div class="bg-white rounded-lg shadow p-6">
              <h2 class="text-lg font-medium mb-4">Associations</h2>
              <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select
                        v-model="form.category_id"
                        @change="handleCategoryChange"
                        @focus="form.clearErrors('category_id')"
                        :class="[
                          'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                          { 'border-red-500': form.errors.category_id }
                        ]"
                    >
                        <option value="">Select Category</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                        </option>
                    </select>
                    <small v-show="form.errors.category_id" class="text-red-700">{{ form.errors.category_id }}</small>
                    </div>

                    <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                    <select
                        v-model="form.brand_id"
                        @focus="form.clearErrors('brand_id')"
                        :class="[
                          'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                          { 'border-red-500': form.errors.brand_id }
                        ]"
                    >
                        <option value="">Select Brand</option>
                        <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                        {{ brand.name }}
                        </option>
                    </select>
                    <small v-show="form.errors.brand_id" class="text-red-700">{{ form.errors.brand_id }}</small>
                    </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1"
                    >Warranty</label
                  >
                  <input
                    v-model="form.warranty"
                    type="text"
                    placeholder="e.g 12 months"
                    @focus="form.clearErrors('warranty')"
                    :class="[
                      'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500',
                      { 'border-red-500': form.errors.warranty }
                    ]"
                  />
                  <small class="text-red-700">{{ form.errors.warranty }}</small>
                  </input>
                </div>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4">
              <Link
                :href="route('products.index')"
                class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50"
              >
                Cancel
              </Link>
              <button
                type="submit"
                :disabled="form.processing"
                class="px-4 py-2 bg-navy-600 text-white rounded-md hover:bg-navy-700 disabled:opacity-50"
                >
                <span v-if="form.processing">Creating...</span>
                <span v-else>Create</span>
                </button>
            </div>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { Link, useForm, } from "@inertiajs/vue3";
import Sidebar from "../../../Components/Sidebar.vue";
import Header from "../../../Components/Header.vue";
// State

const props = defineProps({
  categories: Array,
  brands: Array,
});

const form = useForm({
  name: "",
  slug: "",
  sku: "",
  description: "",
  images: [],
  price: "",
  stock: "",
  category_id: "",
  brand_id: "",
  warranty: "",
  specifications: [],
});

const currentSpecifications = ref([]);

// Watch for changes to the selected category
watch(
  () => form.category_id, // Watch for changes to the selected category
  () => {
    handleCategoryChange(); // Populate specifications dynamically
  }
);

watch(
  () => form.name,
  (newName) => {
    form.slug = newName.toLowerCase().trim().replace(/[\s\/]+/g, "-");
  }
);

const handleCategoryChange = () => {
  const selectedCategory = props.categories.find(
    (category) => category.id === form.category_id
  );
  currentSpecifications.value = selectedCategory
    ? selectedCategory.specifications
    : [];
  form.specifications = currentSpecifications.value.map((spec) => ({
    id: spec.id,
    value: "", // Initialize the value as empty
  }));
};

// Methods
const imagePreviews = ref([]); // Array to store image previews

// Handle file upload
// Handle file upload
const handleFileUpload = (event) => {
  const files = Array.from(event.target.files); // Convert FileList to Array
  addFiles(files);
};

// Handle drag-and-drop
const handleDrop = (event) => {
  event.preventDefault(); // Prevent default behavior
  const files = Array.from(event.dataTransfer.files);
  addFiles(files);
};

// Add files to the form
const addFiles = (files) => {
  files.forEach((file) => {
    if (!file.type.startsWith("image/")) {
      alert("Only image files are allowed.");
      return;
    }

    if (file.size > 10 * 1024 * 1024) {
      alert("File size must not exceed 10MB.");
      return;
    }

    form.images.push(file); // Add the file to form.images
    createImagePreview(file); // Create a preview for the image
  });
};

// Create image previews
const createImagePreview = (file) => {
  const reader = new FileReader();
  reader.onload = (e) => {
    imagePreviews.value.push(e.target.result); // Store the base64 preview
  };
  reader.readAsDataURL(file);
};

// Remove an image
const removeImage = (index) => {
  form.images.splice(index, 1); // Remove file from form data
  imagePreviews.value.splice(index, 1); // Remove preview
};

const submitForm = () => {
  form.post(route("products.store"), {
    onSuccess: () => {
      // Optional: Redirect to the product index or reset the form
      form.reset();
      currentSpecifications.value = [];
    },
    onError: (errors) => {
      // Handle validation errors
      console.error(errors);
    },
  });
};

// Add this function in the script setup section
const clearRelatedErrors = () => {
  form.clearErrors('name');
  form.clearErrors('slug');
  form.clearErrors('sku');
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
