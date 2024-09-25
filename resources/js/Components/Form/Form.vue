<script setup>
import { defineEmits } from "vue";

defineEmits(['submitted']);
</script>
<template>
    <form @submit.prevent="$emit('submitted')">
        <h1 class="text-sm font-medium"><slot name="title" /></h1>
        <slot name="form" />
        <div class="flex items-center">
            <slot name="actions" />
        </div>
    </form>
</template>
