<script setup>
import headerlayout from '@/Layouts/HeaderLayout.vue';
import tablink from '@/Components/Header/TabLink.vue';
import Logo from '@/Components/Icons/Logo.vue';
import HoverDropdownLink from '@/Components/Header/HoverDropdownLink.vue';
import HoverDropdownVue from '@/Components/Header/HoverDropdown.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
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
    <headerlayout>
        <template #icon>
            <Logo />
        </template>
        <template #subtitle>
            Department of Agriculture
        </template>
        <template #title>
            Crop Biotechnology Center
        </template>
        <template #links>
            <ul class="lg:flex sm:gap-2">
                <template v-for="link in TabLinks">
                    <tablink :link="link.link">
                        {{ link.name }}
                    </tablink>
                </template>
                <tablink :sublinks="true">
                    <template #trigger>
                        Projects
                    </template>
                    <template #content>
                        <HoverDropdownLink :href="route('profile.show')" class="text-black">
                            Home
                        </HoverDropdownLink>
                    </template>
                </tablink>
                <template v-if="canLogin">
                    <tablink :link="route('login')">Log in</tablink>
                    <tablink v-if="canRegister" :link="route('register')">Register</tablink>
                </template>
            </ul>
            <HoverDropdownVue align="center" width="48">
                <template #trigger>
                    <span class="inline-flex rounded-md">
                        <button type="button" class="inline-flex items-center px-3 py-2 border-transparent text-sm leading-4 rounded-md text-gray-500 bg-cbc-dark-green hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                            Sample
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </span>
                </template>
                <template #content>
                    <HoverDropdownLink :href="route('profile.show')" class="text-black">
                        Home
                    </HoverDropdownLink>
                </template>
            </HoverDropdownVue>
        </template>
        <template #modal>
            
        </template>
    </headerlayout>
</template>