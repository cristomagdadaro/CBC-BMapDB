<script setup>
import { Head } from '@inertiajs/vue3';
import headerlayout from '@/Layouts/HeaderLayout.vue';
import tablink from '@/Components/Header/TabLink.vue';
defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

const TabLinks = [
    {
        name: 'Home',
        link: '/',
    },
    {
        name: 'Projects',
        link: '#',
    },
    {
        name: 'Articles',
        link: '#',
    },
    {
        name: 'Careers',
        link: '#',
    },
    {
        name: 'Services',
        link: '#',
    },
    {
        name: 'Resources',
        link: '#',
    },
    {
        name: 'About Us',
        link: '#',
    },
];
</script>

<template>
    <Head title="Welcome" />
    <img src="/img/bg.jpg" alt="Crop Biotechnology Center Logo" class="absolute top-0 h-auto z-0">
    <headerlayout>
        <template #icon>
            <img src="/img/logo.png" alt="Crop Biotechnology Center Logo" class="sm:h-12 h-10 w-auto">
        </template>
        <template #subtitle>
            Department of Agriculture
        </template>
        <template #title>
            Crop Biotechnology Center
        </template>
        <template #links>
            <ul class="sm:flex sm:gap-5">
                <template v-for="link in TabLinks">
                    <tablink :link="link.link">
                        {{ link.name }}
                    </tablink>
                </template>
                <template v-if="canLogin">
                    <tablink :link="route('login')">Log in</tablink>
                    <tablink v-if="canRegister" :link="route('register')">Register</tablink>
                </template>
            </ul>
        </template>
    </headerlayout>
    <div>
        <slot />
    </div>
</template>
