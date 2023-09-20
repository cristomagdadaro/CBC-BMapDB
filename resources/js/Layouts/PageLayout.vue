<script setup>
import Header from '@/Pages/Header.vue';
import Footer from '@/Pages/Footer.vue';
import Body from '@/Pages/Body.vue';
</script>
<template>
    <div class="flex flex-col relative">
        <Header :can-login="true" :can-register="true" />
        <Body>
            <slot />
        </Body>
        <Footer />
    </div>
</template>