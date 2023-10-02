<script setup>
import { defineProps, defineEmits } from "vue";

defineEmits(['submitted']);
</script>
<template>
    <form class="bg-gray-100 p-2 shadow-md" @submit.prevent="$emit('submitted')">
        <h1 class="text-sm font-medium"><slot name="title" /></h1>
        <slot name="form" />
        <div class="flex items-center">
            <slot name="actions" />
        </div>
    </form>
</template>
