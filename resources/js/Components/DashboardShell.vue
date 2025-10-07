<template>
  <div class="w-full px-4 py-6 space-y-6 bg-gray-50 min-h-screen">
    <!-- Header with Actions -->
    <div class="bg-white rounded-xl shadow-sm p-3 mb-3">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ title }}</h1>
          <p v-if="lastUpdated" class="text-gray-600 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm">Last Fetched: {{ new Date(lastUpdated).toLocaleString() }}</span>
          </p>
        </div>

        <div class="flex flex-wrap gap-3">
          <button
              @click="$emit('refresh')"
              :disabled="isLoading"
              class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg class="w-5 h-5" :class="{ 'animate-spin': isLoading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Refresh
          </button>

          <!-- Custom actions (e.g., Export buttons) -->
          <slot name="actions"/>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="flex flex-col items-center justify-center py-20">
      <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600 mb-4"></div>
      <p class="text-gray-600 font-medium">Loading dashboard data...</p>
    </div>

    <!-- Content -->
    <div v-else>
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  title: string;
  isLoading?: boolean;
  lastUpdated?: string | null;
}>();

defineEmits<{
  (e: 'refresh'): void;
}>();
</script>

