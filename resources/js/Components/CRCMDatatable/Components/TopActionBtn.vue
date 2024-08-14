<template>
    <base-button :top-text="topText" class="text-gray-200 flex items-center gap-0.5 sm:h-7 h-6">
        <slot name="icon" />
        <transition-container>
            <slot />
        </transition-container>
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
