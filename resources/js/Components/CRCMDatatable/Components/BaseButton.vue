<template>
  <button :class="classes" class="bg-white active:bg-gray-300 px-2 py-0.5 rounded my-0.5 uppercase text-sm flex items-center select-none"><slot /></button>
</template>
<script setup>

defineProps({
  classes: {
    type: String,
    required: false,
  },
})
</script>
