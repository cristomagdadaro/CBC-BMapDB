<template>
    <base-button :top-text="topText" class="text-gray-200 flex items-center justify-center gap-0.5 sm:h-7 h-6">
        <div class="flex gap-1 item-center justify-center">
            <slot name="icon" />
           <template v-if="showText">
                <span class="flex items-center justify-center">
                    <slot />
                </span>
           </template>
        </div>
    </base-button>
</template>
<script setup>
import BaseButton from "@/Components/CRCMDatatable/Components/BaseButton.vue";

defineProps({
    topText: {
        type: [String, Number],
        default: null
    },
    showText: {
        type: Boolean,
        default: false
    }
});
</script>
