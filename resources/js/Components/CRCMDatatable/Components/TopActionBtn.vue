<template>
    <base-button :top-text="topText" class="text-gray-200 flex items-center justify-center gap-0.5 sm:h-7 h-6">
        <div class="flex gap-1 item-center justify-center py-1">
            <slot name="icon" />
            <span class="flex items-center justify-center">
                <slot />
            </span>
        </div>
    </base-button>
</template>
<script setup>
import BaseButton from "@/Components/CRCMDatatable/Components/BaseButton.vue";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";

defineProps({
    topText: {
        type: [String, Number],
        default: null
    }
});
</script>
