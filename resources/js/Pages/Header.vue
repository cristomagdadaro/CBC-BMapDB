<script setup>
import HeaderLayout from '@/Layouts/HeaderLayout.vue';
import TabLink from '@/Components/Header/TabLink.vue';
import Logo from '@/Components/Icons/Logo.vue';
import {CBCProjectsPublic} from "@/Pages/constants.ts";
import {Link} from "@inertiajs/vue3";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const supportLinks = [
    {name: 'What is PIN?', link: 'support.what-is-pin', description: 'Learn about our mission'},
    {name: 'Terms of Use', link: 'support.terms-of-use', description: 'Usage policies'},
    {name: 'Privacy Policy', link: 'support.privacy-policy', description: 'Your data protection rights'},
    {name: 'Data Privacy Notice', link: 'support.data-privacy', description: 'Notice under RA 10173'},
    {name: 'Contributors', link: 'support.contributors', description: 'Meet our partners'},
    {name: 'Sitemap', link: 'support.sitemap', description: 'Navigate the platform'},
];

</script>
<template>
    <header-layout :active="route().current('dashboard')">
        <template #icon>
            <div class="w-10 h-10 bg-pin-green rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform overflow-hidden">
                <Logo classes="w-8 h-8 object-contain" />
            </div>
        </template>
        <template #subtitle>
            {{ $companyName }}
        </template>
        <template #title>
            {{ $appName }}
        </template>

        <!-- Desktop Links -->
        <template #links="{ isScrolled }">
            <tab-link v-if="$page.props.auth.user" :link="route('dashboard')" :active="route().current('dashboard')" :is-scrolled="isScrolled">Dashboard</tab-link>

            <tab-link sublinks :link="route('projects')" :active="route().current('projects') || route().current('home')" :is-scrolled="isScrolled">
                <template #trigger>
                    Databases
                </template>
                <template #content>
                    <Link v-for="project in CBCProjectsPublic" :key="project.id" :href="route(project.route_public)"
                          class="block px-5 py-4 hover:bg-pin-green-light transition-colors group">
                        <p class="font-medium text-gray-900 group-hover:text-pin-green transition-colors">{{ project.label }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ project.description?.substring(0, 60) }}...</p>
                    </Link>
                </template>
            </tab-link>

            <tab-link v-if="!$page.props.auth.user" sublinks :link="route('support.what-is-pin')" :active="route().current('support.*')" :is-scrolled="isScrolled">
                <template #trigger>
                    Support
                </template>
                <template #content>
                    <Link v-for="link in supportLinks" :key="link.link" :href="route(link.link)"
                          class="block px-5 py-4 hover:bg-pin-green-light transition-colors group">
                        <p class="font-medium text-gray-900 group-hover:text-pin-green transition-colors">{{ link.name }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ link.description }}</p>
                    </Link>
                </template>
            </tab-link>

            <!-- CTA Buttons -->
            <div class="flex items-center gap-3 ml-4">
                <template v-if="!$page.props.auth.user">
                    <Link v-if="canLogin" :href="route('login')"
                          :class="[
                              'flex items-center gap-2 px-5 py-2.5 rounded-lg font-medium transition-all focus-ring',
                              isScrolled
                                  ? 'bg-pin-green text-white hover:bg-pin-green-dark hover:shadow-lg'
                                  : 'bg-white text-pin-green hover:bg-white/90'
                          ]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Log in
                    </Link>
                </template>
            </div>
        </template>

        <!-- Mobile Links -->
        <template #mobile-links>
            <tab-link mobile v-if="$page.props.auth.user" :link="route('dashboard')" :active="route().current('dashboard')">Dashboard</tab-link>

            <tab-link mobile sublinks :link="route('projects')" :active="route().current('projects') || route().current('home')">
                <template #trigger>Databases</template>
                <template #content>
                    <Link v-for="project in CBCProjectsPublic" :key="project.id" :href="route(project.route_public)"
                          class="block px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-pin-green-light hover:text-pin-green transition-colors">
                        {{ project.label }}
                    </Link>
                </template>
            </tab-link>

            <tab-link mobile v-if="!$page.props.auth.user" sublinks :link="route('support.what-is-pin')" :active="route().current('support.*')">
                <template #trigger>Support</template>
                <template #content>
                    <Link v-for="link in supportLinks" :key="link.link" :href="route(link.link)"
                          class="block px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-pin-green-light hover:text-pin-green transition-colors">
                        {{ link.name }}
                    </Link>
                </template>
            </tab-link>

            <div class="pt-4 border-t border-gray-100">
                <template v-if="!$page.props.auth.user">
                    <Link v-if="canLogin" :href="route('login')"
                          class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-pin-green text-white rounded-lg font-medium hover:bg-pin-green-dark transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Log in
                    </Link>
                </template>
            </div>
        </template>
    </header-layout>
</template>
