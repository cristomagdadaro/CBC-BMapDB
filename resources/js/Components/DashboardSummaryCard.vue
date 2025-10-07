<script setup lang="ts">
const props = defineProps<{
    title: string;
    sumValue: number | string;
    sumValueLabel?: string;
    subValue?: number | string;
    subValueLabel?: string;
    backgroundColor?: string;
}>();
</script>

<template>
    <div
        :class="'rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200 ' + (backgroundColor ? backgroundColor : 'bg-gradient-to-br from-purple-500 to-purple-600 ')">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium mb-1">{{title}}</p>
                <h3 class="text-4xl font-bold">{{ sumValue }} {{ sumValueLabel }}</h3>
                <p class="text-purple-100 text-xs mt-2">
                    <span class="font-semibold">{{ subValue }}</span> {{ subValueLabel }}
                </p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
