<script setup>
import HeaderLayout from '@/Layouts/HeaderLayout.vue';
import TabLink from '@/Components/Header/TabLink.vue';
import Logo from '@/Components/Icons/Logo.vue';
import {CBCProjectsPublic} from "@/Pages/constants.ts";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const TabLinks = [

];

const supportLinks = [
    {name: 'What is PIN?', link: 'support.about-us'},
    {name: 'Term of Use', link: 'support.terms-of-use'},
    {name: 'Policy Notice', link: 'support.policy-notice'},
    {name: 'Privacy Policy', link: 'support.privacy-policy'},
    {name: 'Sitemap', link: 'support.sitemap'},
    {name: 'Developers', link: 'support.developers'},
];

</script>
<template>
    <header-layout :active="route().current('dashboard')">
        <template #icon>
            <Logo classes="sm:h-18 h-16 w-auto"/>
        </template>
        <template #subtitle>
            {{ $page.props.companyName }}
        </template>
        <template #title>
            {{ $page.props.appName }}
        </template>
        <template #links>
            <ul class="lg:flex sm:gap-2">
                <tab-link v-if="$page.props.auth.user" :link="route('dashboard')" :active="route().current('dashboard')">Dashboard</tab-link>
                <template v-for="link in TabLinks">
                    <tab-link :link="route(link.link)" :active="route().current(link.link)">
                        {{ link.name }}
                    </tab-link>
                </template>
                <tab-link sublinks :link="route('projects')" :active="route().current('projects') || route().current('home')">
                    <template #trigger>
                        Databases
                    </template>
                    <template #content>
                        <tab-link v-for="project in CBCProjectsPublic" :key="project.id" :link="route(project.value)" class="text-gray-700">
                            {{ project.label }}
                        </tab-link>
                    </template>
                </tab-link>
                <template v-if="!$page.props.auth.user ">
                    <tab-link v-if="canLogin" :link="route('login')" :active="route().current('login')">Log in</tab-link>
                    <tab-link v-if="canRegister" :link="route('register')" :active="route().current('register')">Register</tab-link>
                </template>
                <tab-link v-if="!$page.props.auth.user" sublinks :link="route('support.about-us')" :active="route().current('support.about-us')">
                    <template #trigger>
                        Support
                    </template>
                    <template #content>
                        <tab-link link="https://dacbc.philrice.gov.ph" external-link class="text-gray-700">
                            About Us
                        </tab-link>
                        <tab-link link="https://cbc360tour.philrice.gov.ph" external-link class="text-gray-700">
                            Visit Us
                        </tab-link>
                        <tab-link v-for="link in supportLinks" :key="link.link" :link="route(link.link)" class="text-gray-700">
                            {{ link.name }}
                        </tab-link>
                    </template>
                </tab-link>
            </ul>
        </template>
    </header-layout>
</template>
