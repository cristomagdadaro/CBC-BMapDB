<template>
    <div class="bg-gray-200 text-gray-900 opacity-50 w-full h-full flex justify-center items-center z-50 rounded-md">
        <h1 class="text-2xl flex">
            <SpinnerIcon class="animate-spin w-7 mr-3" />
            Please Wait...
        </h1>
        <span class="text-2xl flex mx-2"><slot /></span>
    </div>
</template>
<script setup>
import SpinnerIcon from '@/Components/Icons/SpinnerIcon.vue';
</script>
