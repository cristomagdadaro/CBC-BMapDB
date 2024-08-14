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
import Notification from "@/Components/Modal/Notification/Notification.ts";
import Footer from "@/Pages/Footer.vue";
import Hamburger from "@/Components/Icons/Hamburger.vue";
import SidebarLayout from "@/Layouts/SidebarLayout.vue";
import NotifBanner from "@/Components/Modal/Notification/NotifBanner.vue";
import SelectField from "@/Components/Form/SelectField.vue";
import User from "@/Modules/core/domain/auth/User";

export default {
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
            user: new User(this.$page.props.auth.user),
        }
    },
    mounted() {
      /*CBCProjects.forEach((project) => {
        project.show = this.hasPermission(project.permission);
      });

      console.log(CBCProjects);*/
    },
    computed: {
        User() {
            return User;
        },
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
        <nav v-if="user" class="bg-white shadow">
            <!-- Primary Navigation Menu -->
            <div class="px-4 sm:px-6 py-2 lg:px-8 bg-cbc-dark-green">
                <div class="flex justify-between items-center h-10">
                    <div class="sm:flex hidden flex-col text-gray-50">
                        <div class="flex items-center">
                            <span class="leading-tight text-sm uppercase">
                                {{ user.getFullName }}
                            </span>
                        </div>
                        <div class="flex items-center gap-1 text-xs">
                            <span class="leading-tight">
                            {{ user.getRole }}
                            </span>
                            <span class="mx-1">|</span>
                            <span class="leading-tight">
                                {{ user.affiliation }}
                            </span>
                            <span class="mx-1">|</span>
                            <span class="leading-tight">
                                {{ user.email }}
                            </span>
                        </div>
                    </div>
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
                    <div class="-mr-2 flex items-center sm:hidden w-full justify-end">
                        <FullscreenToggle />
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
                <ResponsiveNavLink :href="route('dashboard')"
                                   :active="route().current('dashboard')">
                    Dashboard
                </ResponsiveNavLink>
                <ResponsiveNavLink v-if="user.isAdmin"
                                   :href="route('administrator.index')"
                                   :active="route().current('administrator.index')"
                >
                    Administrator
                </ResponsiveNavLink>
                <template v-if="user.accounts">
                    <ResponsiveNavLink v-for="account in user.accounts"
                                       :key="account.id"
                                       :href="route(account.application.url)"
                                       :active="route().current(account.application.url)"
                    >
                        {{ account.application.name }}
                    </ResponsiveNavLink>
                </template>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div v-if="user">
                            <div class="flex flex-col text-gray-700 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="leading-tight uppercase">
                                        {{ user.getFullName }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-1">
                                        <span class="leading-tight">
                                            {{ user.getRole }}
                                        </span>

<!--                                        <span class="mx-1">|</span>

                                        <span class="leading-tight">
                                            {{ user.affiliation }}
                                        </span>-->

                                        <span class="mx-1">|</span>

                                        <span class="leading-tight">
                                            {{ user.email }}
                                        </span>
                                    </div>
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
                <NavLink class="text-white" :href="route('dashboard')" :active="route().current('dashboard')">
                    <div class="flex gap-1 select-none items-center sm:p-2 p-1">
                        <span class="sm:flex hidden whitespace-nowrap">
                           Dashboard
                        </span>
                    </div>
                </NavLink>
                <NavLink v-if="user.isAdmin" class="text-white" :href="route('administrator.index')" :active="route().current('administrator.index')">
                    <div class="flex gap-1 select-none items-center sm:p-2 p-1">
                        <span class="sm:flex hidden whitespace-nowrap">
                           Administrator
                        </span>
                    </div>
                    <template #subLinks>
                        <router-link  v-for="subLink in  [
                            {
                                name: 'administrator.unverified-users',
                                label: 'Unverified Users',
                            },
                            {
                                name: 'administrator.approved-accounts',
                                label: 'Approved Accounts',
                            },
                            {
                                name: 'administrator.applications',
                                label: 'Applications',
                            }
                        ]"
                            :to="{ name: subLink.name }"
                                     :class="$route.name === subLink.name ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out':'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-300 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out'">
                            {{ subLink.label }}
                        </router-link>
                    </template>
                </NavLink>
                <template v-for="account in user.accounts" :key="account.application.id" >
                    <NavLink v-if="account.application"
                             class="text-white"
                             :href="route(account.application.url)"
                             :active="route().current(account.application.url)"
                    >
                        <div class="flex gap-1 select-none items-center sm:p-2 p-1">
                            <span class="sm:flex hidden whitespace-nowrap">
                            {{ account.application.name }}
                            </span>
                        </div>
                        <template #subLinks>
                            <router-link v-for="subLink in account.application.appTabs" :to="{ name: subLink.name }" :class="$route.name === subLink.name ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out':'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-300 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out'">
                                {{ subLink.label }}
                            </router-link>
                        </template>
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
