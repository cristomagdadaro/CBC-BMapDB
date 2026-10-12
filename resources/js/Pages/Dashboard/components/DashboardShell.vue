<template>
    <div
        class="w-full px-4 py-6 space-y-6 bg-gray-50 dark:bg-gray-900 min-h-screen z-50 transition-colors duration-200"
    >
        <!-- Header with Actions -->
        <div
            :class="{ hidden: hideHeader }"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 p-4 sm:p-5 mb-3 transition-colors duration-200"
        >
            <div
                class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4"
            >
                <div>
                    <h1
                        class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-1.5 tracking-tight"
                    >
                        {{ title }}
                    </h1>
                    <p
                        v-if="lastUpdated"
                        class="text-gray-500 dark:text-gray-400 flex items-center gap-2 text-sm"
                    >
                        <svg
                            class="w-4 h-4 text-gray-400 dark:text-gray-500 flex-shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        <span
                            >Last Fetched:
                            {{ new Date(lastUpdated).toLocaleString() }}</span
                        >
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button
                        @click="$emit('refresh')"
                        :disabled="isLoading"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-medium text-sm rounded-lg shadow-sm hover:shadow transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                    >
                        <svg
                            class="w-4 h-4"
                            :class="{ 'animate-spin': isLoading }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>
                        <span>Refresh</span>
                    </button>

                    <!-- Custom actions (e.g., Export buttons) -->
                    <slot name="actions" />
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div
            v-if="isLoading"
            class="flex flex-col items-center justify-center py-24 min-h-[300px]"
        >
            <div class="relative w-14 h-14 mb-4">
                <!-- Background track spinner -->
                <div
                    class="w-full h-full rounded-full border-4 border-gray-200 dark:border-gray-700"
                ></div>
                <!-- Active spinning border -->
                <div
                    class="absolute top-0 left-0 w-full h-full rounded-full border-4 border-emerald-600 dark:border-emerald-500 border-t-transparent dark:border-t-transparent animate-spin"
                ></div>
            </div>
            <p
                class="text-gray-600 dark:text-gray-400 font-medium text-sm animate-pulse"
            >
                Loading dashboard data...
            </p>
        </div>

        <!-- Content -->
        <div v-else class="transition-opacity duration-200">
            <slot />
        </div>
    </div>
</template>

<script setup lang="ts">
const props = defineProps<{
    title: string;
    isLoading?: boolean;
    lastUpdated?: string | null;
    hideHeader?: boolean;
}>();

defineEmits<{
    (e: "refresh"): void;
}>();
</script>
