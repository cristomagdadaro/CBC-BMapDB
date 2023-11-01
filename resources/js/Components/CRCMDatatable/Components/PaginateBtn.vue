<template>
  <base-button :classes="classes" class="bg-gray-400">
    <slot />
  </base-button>
</template>
<script setup>
import BaseButton from "@/Components/CRCMDatatable/Components/BaseButton.vue";
import { defineProps } from "vue";
defineProps({
  classes: String,
})
</script>
