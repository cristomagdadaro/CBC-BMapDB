<script>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import PageLayout from "@/Layouts/PageLayout.vue";
import FullscreenToggle from "@/Components/FullscreenToggle.vue";
import {CBCProjects} from "@/Pages/constants.ts";
import TopActionBtn from "@/Components/CRCMDatatable/Components/TopActionBtn.vue";
import BellIcon from "@/Components/Icons/BellIcon.vue";
import Notification from "@/Components/Modal/Notification/Notification.js";
import Footer from "@/Pages/Footer.vue";
import Hamburger from "@/Components/Icons/Hamburger.vue";
import SidebarLayout from "@/Layouts/SidebarLayout.vue";
import NotifBanner from "@/Components/Modal/Notification/NotifBanner.vue";
import UserPermissions from "@/Pages/mixins/UserPermissions.js";
import SelectField from "@/Components/Form/SelectField.vue";

export default {
    mixins: [UserPermissions],
    components: {
        SelectField,
        NotifBanner,
        SidebarLayout,
        Head,
        Link,
        Banner,
        Dropdown,
        DropdownLink,
        NavLink,
        ResponsiveNavLink,
        PageLayout,
        FullscreenToggle,
        TopActionBtn,
        BellIcon,
        Footer,
        Hamburger,
    },
    props: {
        title: {
            type: String,
            default: null,
        },
    },
    data() {
        return {
            showSidebar: true,
            CBCProjects,
        }
    },
    mounted() {
      /*CBCProjects.forEach((project) => {
        project.show = this.hasPermission(project.permission);
      });

      console.log(CBCProjects);*/
    },
    setup(props) {
        const showingNavigationDropdown = ref(false);

        const switchToTeam = (team) => {
            router.put(route('current-team.update'), {
                team_id: team.id,
            }, {
                preserveState: false,
            });
        };

        const logout = () => {
            router.post(route('logout'));
        };

        return {
            Notification,
            showingNavigationDropdown,
            switchToTeam,
            logout,
        }
    }
}
</script>

<template>
    <Head :title="title" />

    <NotifBanner />

    <div class="min-h-screen bg-gray-100">
        <nav v-if="$page.props.auth.user" class="bg-white border-b border-gray-100">
            <!-- Primary Navigation Menu -->
            <div class="px-4 sm:px-6 py-2 lg:px-8 bg-cbc-dark-green">
                <div class="flex justify-between h-10">
                    <div class="flex">
                        <!-- Navigation Links -->
<!--                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <NavLink class="text-white" v-for="project in CBCProjects" :key="project.id" :href="route(project.value)" :active="route().current(project.value)">
                                {{ project.label }}
                            </NavLink>
                        </div>-->
                    </div>
                    <span class="flex gap-2 items-center text-gray-100">
                        {{$page.props.auth.user.email}}
                        <select-field
                            v-if="permissions"
                            class="text-gray-900"
                            :options="permissions.map(
                                (permission) => ({
                                    label: permission,
                                    value: permission,
                                })
                            )" />
                    </span>
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                      <top-action-btn
                          class="shadow-none hover:scale-105 active:scale-100"
                          @click="new Notification('Test','This is a test notification '+ Notification.notifications.value.length, Array.from(['error', 'success', 'warning', 'failed'])[Math.floor(Math.random() * 4)], 5000, true)">
                        <template #icon>
                          <bell-icon class="h-auto sm:w-6 w-4" :class="Notification.notifications.value.length?'animate-wiggle':''" />
                        </template>
                      </top-action-btn>
                        <FullscreenToggle />
                        <!-- Settings Dropdown -->
                        <div class="ml-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                            Settings
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <!-- Account Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        Manage Account
                                    </div>

                                    <DropdownLink :href="route('profile.show')">
                                        Profile
                                    </DropdownLink>

                                    <div class="border-t border-gray-200" />

                                    <!-- Authentication -->
                                    <form @submit.prevent="logout">
                                        <DropdownLink as="button">
                                            Log Out
                                        </DropdownLink>
                                    </form>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" @click="showingNavigationDropdown = ! showingNavigationDropdown">
                            <svg
                                class="h-6 w-6"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink v-for="project in CBCProjects" :key="project.id" :href="route(project.value)" :active="route().current(project.value)">
                        {{ project.label }}
                    </ResponsiveNavLink>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div v-if="$page.props.auth.user">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.fname }} {{ $page.props.auth.user.lname }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>
                        <div v-else>
                            Please Login
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                            Profile
                        </ResponsiveNavLink>

                        <!-- Authentication -->
                        <form method="POST" @submit.prevent="logout">
                            <ResponsiveNavLink as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Page Content -->
        <sidebar-layout>
            <template #options>
                <template v-for="project in CBCProjects" :key="project.id" >
                    <NavLink v-if="project.show" class="text-white" :href="route(project.value)" :active="route().current(project.value)">
                        <div class="flex gap-1 select-none items-center">
                            <img :src="project.icon" class="hidden h-8 w-8"  :alt="project.label"/>
                            <span class="sm:flex hidden whitespace-nowrap">
                            {{ project.label }}
                        </span>
                        </div>
                    </NavLink>
                </template>
            </template>
            <template #content>
                <main>
                    <slot />
                </main>
            </template>
        </sidebar-layout>
    </div>
    <Footer />
</template>
