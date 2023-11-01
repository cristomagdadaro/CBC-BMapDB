<script setup>
import headerlayout from '@/Layouts/HeaderLayout.vue';
import tablink from '@/Components/Header/TabLink.vue';
import Logo from '@/Components/Icons/Logo.vue';
import HoverDropdownLink from '@/Components/Header/HoverDropdownLink.vue';
import HoverDropdownVue from '@/Components/Header/HoverDropdown.vue';
import { CBCProjects } from "@/Pages/constants.ts";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const TabLinks = [
    {
        name: 'Home',
        link: '/',
    },
    /*{
       name: 'Projects',
       link: '#',
   },*//*
   {     name: 'Articles',
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
   },*/
];
</script>
<template>
    <headerlayout :active="route().current('dashboard')">
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
                <tablink v-if="$page.props.auth.user" :link="route('dashboard')" :active="route().current('dashboard')">Dashboard</tablink>
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
                        <tablink v-for="project in CBCProjects" :key="project.id" :link="route(project.value)" class="text-gray-700">
                            {{ project.label }}
                        </tablink>
                    </template>
                </tablink>
                <template v-if="canLogin && $page.props.auth.user === null ">
                    <tablink :link="route('login')">Log in</tablink>
                    <tablink v-if="canRegister" :link="route('register')">Register</tablink>
                </template>
            </ul>
        </template>
    </headerlayout>
</template>
