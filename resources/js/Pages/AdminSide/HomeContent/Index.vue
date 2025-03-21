<template>
  <Head title=" | Home Content" />
  <Toast ref="toast" />
  <div class="min-h-screen bg-gray-50">
    <Sidebar></Sidebar>

    <main class="lg:ml-64 min-h-screen">
      <Header title="Home Content"></Header>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold">Hero Section Content</h2>
          <button class="px-4 py-2 bg-navy-600 text-white rounded-md hover:bg-navy-700">
            <Link :href="route('home-content.create')">New Content</Link>
          </button>
        </div>

        <!-- Content Table -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  v-for="header in headers"
                  :key="header"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  {{ header }}
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(content, index) in landingContents" :key="content.id">
                <td class="px-6 py-4 whitespace-nowrap">{{ content.hero }}</td>
                <td class="px-6 py-4">
                  <p class="text-sm text-gray-900 line-clamp-2">{{ content.description }}</p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <img
                    :src="content.image ? '/storage/' + content.image : '/storage/default.jpg'"
                    class="h-12 w-24 object-cover rounded-md shadow-sm"
                  />
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="[
                      'px-2 py-1 text-xs font-medium rounded-full',
                      content.is_active
                        ? 'bg-green-100 text-green-800'
                        : 'bg-red-100 text-red-800',
                    ]"
                  >
                    {{ content.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right relative">
                  <button
                    @click="openActionMenu(index)"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none"
                  >
                    <MoreVerticalIcon class="h-5 w-5" />
                  </button>
                  <!-- Action Menu -->
                  <div
                    v-if="activeActionMenu === index"
                    class="absolute z-50 bg-white border border-gray-200 shadow-lg rounded-md"
                    style="top: 0; right: 50%; margin-right: 0.5rem;"
                  >
                    <Link :href="route('home-content.edit', content.id)">
                      <button
                        class="block w-full px-4 py-2 text-sm text-green-500 hover:bg-green-100 text-left"
                      >
                        Edit
                      </button>
                    </Link>
                    <button
                      @click="openDeleteModal(content)"
                      class="block w-full px-4 py-2 text-sm text-red-600 hover:bg-red-100 text-left"
                    >
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full p-6">
          <div class="flex flex-col items-center">
            <div
              class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100"
            >
              <TrashIcon class="h-6 w-6 text-red-600" />
            </div>
            <h3 class="mt-4 text-lg font-semibold">Delete Content</h3>
            <p class="mt-2 text-sm text-gray-500">Are you sure you want to delete this content?</p>
            <div class="mt-6 flex gap-4">
              <button
                @click="showDeleteModal = false"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
              >
                Cancel
              </button>
              <button
                @click="confirmDelete"
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { MoreVerticalIcon, TrashIcon } from "lucide-vue-next";
import Sidebar from "../../../Components/Sidebar.vue";
import Header from "../../../Components/Header.vue";
import Toast from "@/Components/Toast.vue";

const props = defineProps({
  landingContents: Array,
});

const headers = ["HERO", "DESCRIPTION", "IMAGE", "STATUS", ""];
const showDeleteModal = ref(false);
const contentToDelete = ref(null);
const activeActionMenu = ref(null);
const toast = ref(null);

const openActionMenu = (index) => {
  activeActionMenu.value = activeActionMenu.value === index ? null : index;
};

const openDeleteModal = (content) => {
  contentToDelete.value = content;
  showDeleteModal.value = true;
  activeActionMenu.value = null;
};

const confirmDelete = () => {
  if (!contentToDelete.value) return;

  router.delete(route("home-content.destroy", contentToDelete.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false;
      contentToDelete.value = null;
    },
    onError: (errors) => {
      showDeleteModal.value = false;
      if (errors.error) {
        toast.value.addToast(errors.error, "error");
      }
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
