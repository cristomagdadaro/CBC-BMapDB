<template>
  <base-button :classes="classes">
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
