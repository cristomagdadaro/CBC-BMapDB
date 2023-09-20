<script setup>
import BodyLayout from '@/Layouts/BodyLayout.vue';
</script>
<template>
    <BodyLayout>
        <main class="min-h-screen bg-white">
            <slot />
        </main>
    </BodyLayout>
</template>