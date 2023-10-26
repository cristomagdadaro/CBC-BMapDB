<template>
  <button :class="classes" class="shadow active:bg-opacity-70 hover:scale-105 active:scale-100 px-2 py-0.5 rounded my-0.5 uppercase text-sm flex items-center select-none"><slot /></button>
</template>
<script setup>

defineProps({
  classes: {
    type: String,
    required: false,
  },
})
</script>
