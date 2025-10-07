<template>
    <div class="bg-gradient-to-br p-6 rounded-lg shadow-lg text-white" :class="bgColor">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90">{{ title }}</p>
                <p class="text-3xl font-bold mt-2">{{ value }}</p>
                <p v-if="subtitle" class="text-xs mt-1 opacity-75">{{ subtitle }}</p>
            </div>
            <div class="text-4xl opacity-50">
                <slot name="icon">
                    <i :class="icon"></i>
                </slot>
            </div>
        </div>
        <div v-if="trend" class="mt-4 flex items-center text-sm">
            <span :class="trend > 0 ? 'text-green-200' : 'text-red-200'" class="flex items-center">
                <i :class="trend > 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'" class="mr-1"></i>
                {{ Math.abs(trend) }}%
            </span>
            <span class="ml-2 opacity-75">vs last period</span>
        </div>
    </div>
</template>

<script setup>
defineProps({
    title: String,
    value: [String, Number],
    subtitle: String,
    icon: String,
    bgColor: {
        type: String,
        default: 'from-blue-500 to-blue-600'
    },
    trend: Number
});
</script>
