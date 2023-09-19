<script setup>
import Header from '@/Pages/Header.vue';
import Footer from '@/Pages/Footer.vue';
</script>
<template>
    <div class="relative flex flex-col sm:mx-60 mx-2">
        <Header />
        <main class="min-h-screen bg-white">
            <slot />
        </main>
        <Footer />
    </div>
</template>