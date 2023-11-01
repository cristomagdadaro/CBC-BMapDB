<template>
  <button :class="classes" class="shadow active:bg-opacity-70 hover:drop-shadow-lg px-2 py-0.5 rounded my-0.5 uppercase text-sm flex items-center select-none"><slot /></button>
</template>
<script setup>

defineProps({
  classes: {
    type: String,
    required: false,
  },
})
</script>
