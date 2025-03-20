<template>
  <div class="fixed top-4 right-4 z-50">
    <TransitionGroup
      tag="div"
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-100"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
      class="flex flex-col gap-2"
    >
      <div
        v-for="toast in toasts"
        :key="toast.id"
        :class="[
          'w-[300px] shadow-lg rounded-lg pointer-events-auto overflow-hidden',
          toast.type === 'success' ? 'bg-green-50 border border-green-400' : '',
          toast.type === 'error' ? 'bg-red-50 border border-red-400' : '',
        ]"
      >
        <div class="p-3">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <CheckCircle2Icon
                v-if="toast.type === 'success'"
                class="h-5 w-5 text-green-400"
              />
              <XCircleIcon v-if="toast.type === 'error'" class="h-5 w-5 text-red-400" />
            </div>
            <div class="ml-2 flex-1">
              <p
                :class="[
                  'text-sm',
                  toast.type === 'success' ? 'text-green-800' : '',
                  toast.type === 'error' ? 'text-red-800' : '',
                ]"
              >
                {{ toast.message }}
              </p>
            </div>
            <div class="ml-2 flex-shrink-0">
              <button
                @click="removeToast(toast.id)"
                class="inline-flex text-gray-400 hover:text-gray-500"
              >
                <XIcon class="h-4 w-4" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { TransitionGroup } from "vue";
import { CheckCircle2Icon, XCircleIcon, XIcon } from "lucide-vue-next";

const toasts = ref([]);
let toastId = 0;

const addToast = (message, type = "success") => {
  const id = toastId++;
  toasts.value.push({ id, message, type });
  setTimeout(() => removeToast(id), 5000);
};

const removeToast = (id) => {
  const index = toasts.value.findIndex((toast) => toast.id === id);
  if (index > -1) {
    toasts.value.splice(index, 1);
  }
};

defineExpose({ addToast });
</script>
