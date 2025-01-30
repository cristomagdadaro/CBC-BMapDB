<script setup>
import WarningIcon from '@/Components/Icons/WarningIcon.vue';
import CloseIcon from '@/Components/Icons/CloseIcon.vue';
import Notification from "@/Components/Modal/Notification/Notification.ts";
import SuccessIcon from "@/Components/Icons/SuccessIcon.vue";
import ErrorIcon from "@/Components/Icons/ErrorIcon.vue";
import FailedIcon from "@/Components/Icons/FailedIcon.vue";
</script>
<template>
    <div class="top-3 right-3 z-[100] flex justify-end fixed">
      <div v-if="Notification.notifications.value.length" class="flex items-end flex-col gap-1">
        <template v-for="notify in Notification.notifications.value" :key="notify.id">
          <transition
              enter-active-class="duration-1000"
              enter-from-class="translate-x-full"
              enter-to-class="translate-x-0"
              leave-active-class="duration-1000"
              leave-from-class="translate-x-0"
              leave-to-class="translate-x-full"
          >
            <div v-show="notify.show"
                 :class="notify.type"
                 class="max-w-fit shadow-lg z-50 flex items-center duration-1000 overflow-hidden px-5 py-1 sm:px-3.5 sm:before:flex-1">
              <success-icon v-if="notify.type === 'success'" class="w-6 mr-2 success" />
              <warning-icon v-else-if="notify.type === 'warning'" class="w-6 mr-2 warning" />
              <failed-icon v-else-if="notify.type === 'failed'" class="w-6 mr-2 failed" />
              <error-icon v-else class="w-6 mr-2 error" />
              <div class="w-full flex flex-col">
                        <span class="text-xs text-he leading-3 opacity-50">
                          {{ notify.title }} <template v-if="notify.errno">: {{ notify.errno }}</template>
                        </span>
                <span class="leading-5">
                          {{ notify.message }}
                        </span>
              </div>
              <button
                  type="button"
                  @click="notify.close()"
                  class="ml-1"
              >
                <span class="sr-only">Dismiss</span>
                <close-icon class="w-6 h-auto text-gray-400 hover:text-gray-700 hover:rotate-90 duration-300" />
              </button>
            </div>
          </transition>
        </template>
      </div>
    </div>
</template>
<style scoped>
.success {
    @apply bg-green-200 text-green-800;
}

.warning {
    @apply bg-yellow-200 text-yellow-800;
}

.failed {
    @apply bg-red-200 text-red-800;
}

.error {
    @apply bg-red-200 text-red-800;
}
</style>
