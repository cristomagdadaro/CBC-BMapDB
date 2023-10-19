<template>
  <button :class="class"><slot /></button>
</template>
<script setup>

defineProps({
  class: {
    type: String,
    required: false,
    default: 'bg-white active:bg-gray-300 px-2 py-0.5 rounded my-0.5 uppercase text-sm',
  },
})
</script>
