<template>
    <div v-if="topText" class="relative shadow active:bg-opacity-70 hover:drop-shadow-lg px-2 py-0.5 rounded my-0.5 uppercase text-sm flex items-center select-none" :class="classes">
        <span class="absolute -top-3 -right-2 p-1 drop-shadow-lg bg-blue-700 text-[0.6rem] rounded-full">{{topText}}</span>
        <button><slot /></button>
    </div>
    <button v-else :class="classes" class="shadow active:bg-opacity-70 hover:drop-shadow-lg px-2 py-0.5 rounded my-0.5 uppercase text-sm flex items-center select-none"><slot /></button>
</template>
<script setup>

defineProps({
  classes: {
    type: String,
    required: false,
  },
    topText: {
        type: [String, Number],
        default: null
    }
})
</script>
