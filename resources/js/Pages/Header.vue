<script setup>
import HeaderLayout from '@/Layouts/HeaderLayout.vue';
import TabLink from '@/Components/Header/TabLink.vue';
import Logo from '@/Components/Icons/Logo.vue';
import {CBCProjectsPublic} from "@/Pages/constants.ts";
import {usePage} from "@inertiajs/vue3";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const supportLinks = [
    {name: 'What is PIN?', link: 'support.what-is-pin'},
    {name: 'Virtual Tour', link: 'support.cbc-tour'},
    {name: 'Term of Use', link: 'support.terms-of-use'},
    /*{name: 'Policy Notice', link: 'support.policy-notice'},*/
    {name: 'Privacy Policy', link: 'support.privacy-policy'},
    {name: 'Contributors', link: 'support.developers'},
    {name: 'Sitemap', link: 'support.sitemap'},
];

</script>
<template>
    <header-layout :active="route().current('dashboard')">
        <template #icon>
            <Logo />
        </template>
        <template #subtitle>
            {{ $companyName }}
        </template>
        <template #title>
            {{ $appName }}
        </template>
        <template #links>
            <ul class="lg:flex sm:gap-2">
                <tab-link v-if="$page.props.auth.user" :link="route('dashboard')" :active="route().current('dashboard')">Dashboard</tab-link>
                <template v-if="!$page.props.auth.user">
                    <tab-link v-if="canLogin" :link="route('login')" :active="route().current('login')">Log in</tab-link>
                </template>
                <tab-link sublinks :link="route('projects')" :active="route().current('projects') || route().current('home')">
                    <template #trigger>
                        Databases
                    </template>
                    <template #content>
                        <tab-link v-for="project in CBCProjectsPublic" :key="project.id" :link="route(project.route_public)" class="text-gray-700">
                            {{ project.label }}
                        </tab-link>
                    </template>
                </tab-link>
                <tab-link v-if="!$page.props.auth.user" sublinks :link="route('support.what-is-pin')" :active="route().current('support.about-us')">
                    <template #trigger>
                        Support
                    </template>
                    <template #content>
                        <tab-link v-for="link in supportLinks" :key="link.link" :link="route(link.link)" class="text-gray-700">
                            {{ link.name }}
                        </tab-link>
                    </template>
                </tab-link>
            </ul>
        </template>
    </header-layout>
</template>
