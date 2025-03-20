<template>
  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-50">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black bg-opacity-25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel
              class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
            >
              <div class="flex items-center justify-between mb-4">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  Added to Cart!
                </DialogTitle>
                <button @click="closeModal" class="text-gray-400 hover:text-gray-500">
                  <XIcon class="h-5 w-5" />
                </button>
              </div>

              <div class="flex items-center space-x-4 mb-4">
                <img
                  :src="product?.image"
                  :alt="product?.name"
                  class="w-20 h-20 object-contain rounded-lg border border-gray-200"
                />
                <div>
                  <h4 class="font-medium text-gray-900">{{ product?.name }}</h4>
                  <p class="text-gray-600">${{ product?.price }}</p>
                  <p class="text-sm text-gray-500">Quantity: {{ product?.quantity }}</p>
                </div>
              </div>

              <div class="mt-4 flex space-x-3">
                <button
                  @click="closeModal"
                  class="inline-flex justify-center rounded-md border border-transparent bg-navy-600 px-4 py-2 text-sm font-medium text-white hover:bg-navy-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-navy-500 focus-visible:ring-offset-2"
                >
                  Continue Shopping
                </button>
                <Link
                  :href="route('cart.index')"
                  class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-navy-500 focus-visible:ring-offset-2"
                >
                  View Cart
                </Link>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import { XIcon } from "lucide-vue-next";
import { Link } from "@inertiajs/vue3";

defineProps({
  isOpen: Boolean,
  product: Object,
});

const emit = defineEmits(["close"]);

const closeModal = () => {
  emit("close");
};
</script>
