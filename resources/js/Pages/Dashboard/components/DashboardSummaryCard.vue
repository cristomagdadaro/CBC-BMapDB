<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    title: string;
    sumValue: number | string;
    sumValueLabel?: string;
    subValue?: number | string;
    subValueLabel?: string;
    backgroundColor?: string;
    to?: string;
}>();

// Dynamically render either a router-link or div to avoid template duplication
const tag = computed(() => (props.to ? "router-link" : "div"));
const targetRoute = computed(() => (props.to ? { name: props.to } : undefined));
</script>

<template>
    <component
        :is="tag"
        :to="targetRoute"
        class="rounded-xl flex items-center w-full shadow-md hover:shadow-lg p-5 text-white transition-all duration-200 border border-white/10 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 group"
        :class="[
            backgroundColor
                ? backgroundColor
                : 'bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-800',
            to ? 'transform hover:-translate-y-0.5 cursor-pointer' : '',
        ]"
    >
        <div class="flex items-center w-full justify-between">
            <div class="space-y-1">
                <p
                    class="text-white/80 dark:text-white/70 text-xs font-semibold tracking-wide uppercase"
                >
                    {{ title }}
                </p>
                <div class="flex items-baseline gap-1.5">
                    <h3
                        class="text-3xl font-extrabold tracking-tight text-white"
                    >
                        {{ sumValue }}
                    </h3>
                    <span
                        v-if="sumValueLabel"
                        class="text-xs font-medium text-white/80"
                    >
                        {{ sumValueLabel }}
                    </span>
                </div>
                <p
                    v-if="subValue || subValueLabel"
                    class="text-white/75 dark:text-white/65 text-xs font-normal"
                >
                    <span v-if="subValue" class="font-semibold text-white">{{
                        subValue
                    }}</span>
                    {{ subValueLabel }}
                </p>
            </div>

            <div
                v-if="$slots.default"
                class="bg-white/20 dark:bg-black/20 backdrop-blur-sm rounded-lg p-2.5 text-white flex items-center justify-center transition-transform group-hover:scale-110"
            >
                <slot />
            </div>
        </div>
    </component>
</template>
