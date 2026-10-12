<script>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
import FullscreenToggle from "@/Components/FullscreenToggle.vue";
import { CBCProjects } from "@/Pages/constants.ts";
import TopActionBtn from "@/Components/CRCMDatatable/Components/TopActionBtn.vue";
import BellIcon from "@/Components/Icons/BellIcon.vue";
import Notification from "@/Components/Modal/Notification/Notification.ts";
import Modal from "@/Components/Modal.vue";
import Hamburger from "@/Components/Icons/Hamburger.vue";
import SidebarLayout from "@/Layouts/SidebarLayout.vue";
import NotifBanner from "@/Components/Modal/Notification/NotifBanner.vue";
import SelectField from "@/Components/Form/SelectField.vue";
import User from "@/Modules/core/domain/auth/User";
import ApiService from "@/Modules/core/infrastructure/ApiService";
import TD from "@/Components/CRCMDatatable/Components/TD.vue";

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
        Hamburger,
        Modal,
        TD,
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
            showHistoryModal: false,
            historyItems: [],
            historyMeta: null,
            historyLoading: false,
            historyError: null,
            historyPage: 1,
            historyHasMore: true,
            historyScrollThreshold: 120,
        };
    },
    computed: {
        User() {
            return User;
        },
    },
    setup(props) {
        const showingNavigationDropdown = ref(false);

        const switchToTeam = (team) => {
            router.put(
                route("current-team.update"),
                {
                    team_id: team.id,
                },
                {
                    preserveState: false,
                },
            );
        };

        const requestNewApplicationAccess = async () => {
            this.apiService = new ApiService(route("api.accounts.store"));
            const response = await this.apiService.post(this.form);
            if (response instanceof this.DtoError) {
                this.errors = response;
                new Notification(response);
            }
        };

        const logout = () => {
            router.post(route("logout"));

            localStorage.clear();
        };

        return {
            Notification,
            showingNavigationDropdown,
            switchToTeam,
            logout,
            requestNewApplicationAccess,
        };
    },
    methods: {
        async loadHistory(page = 1, append = false) {
            this.historyLoading = true;
            this.historyError = null;
            try {
                const svc = new ApiService("/api/activity-logs");
                const response = await svc.get({
                    per_page: 15,
                    page,
                    mine: true,
                });
                if (response?.data?.data) {
                    const items = response.data.data || [];
                    this.historyItems = append
                        ? [...this.historyItems, ...items]
                        : items;
                    this.historyMeta = response.data.meta || null;
                    this.historyPage = this.historyMeta?.current_page || page;
                    this.historyHasMore = this.historyMeta
                        ? this.historyMeta.current_page <
                          this.historyMeta.last_page
                        : items.length > 0;
                } else {
                    this.historyItems = [];
                    this.historyMeta = null;
                    this.historyHasMore = false;
                }
            } catch (error) {
                this.historyError = "Failed to load activity history.";
                Notification.pushNotification({
                    title: "History",
                    message: "Failed to load activity history.",
                    type: "failed",
                    timeout: 8000,
                    show: true,
                });
            } finally {
                this.historyLoading = false;
            }
        },
        openHistory() {
            this.showHistoryModal = true;
            this.historyPage = 1;
            this.historyHasMore = true;
            this.loadHistory(1, false);
        },
        async onHistoryScroll(event) {
            if (this.historyLoading || !this.historyHasMore) return;
            const target = event?.target;
            if (!target) return;

            const distanceToBottom =
                target.scrollHeight - target.scrollTop - target.clientHeight;
            if (distanceToBottom <= this.historyScrollThreshold) {
                const nextPage = (this.historyPage || 1) + 1;
                await this.loadHistory(nextPage, true);
            }
        },
        closeHistory() {
            this.showHistoryModal = false;
        },
        formatAction(method) {
            switch ((method || "").toUpperCase()) {
                case "POST":
                    return "Created";
                case "PUT":
                case "PATCH":
                    return "Updated";
                case "DELETE":
                    return "Deleted";
                default:
                    return method || "Action";
            }
        },
        formatModel(model) {
            if (!model) return "Unknown Item";
            return String(model)
                .replace(/[-_]/g, " ")
                .replace(/\b\w/g, (l) => l.toUpperCase());
        },
        formatTarget(item) {
            return item?.modified_id ? `#${item.modified_id}` : "-";
        },
        formatActor(item) {
            return item?.user_role ? `${item.user_role}` : "You";
        },
        formatWhen(value) {
            if (!value) return "-";
            const date = new Date(value);
            if (Number.isNaN(date.getTime())) return value;
            return date.toLocaleString();
        },
        formatDescription(item) {
            if (item?.description) return item.description;
            const action = this.formatAction(item?.method).toLowerCase();
            const model = this.formatModel(item?.model);
            const target = item?.modified_id ? `#${item.modified_id}` : "";
            const actor = item?.user_role ? item.user_role : "You";
            return `${actor} ${action} ${model}${target ? " " + target : ""}`.trim();
        },
    },
};
</script>

<template>
    <Head :title="title" />

    <NotifBanner />
    <div
        class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-200"
    >
        <nav
            v-if="user"
            class="bg-white dark:bg-gray-800 shadow border-b border-gray-100 dark:border-gray-700/60 transition-colors duration-200"
        >
            <!-- Primary Navigation Menu -->
            <div
                class="px-4 sm:px-6 py-3 lg:px-8 bg-cbc-dark-green dark:bg-gray-950"
            >
                <div class="flex justify-between items-center h-10">
                    <div
                        id="user-details"
                        class="flex gap-1 items-center relative group"
                    >
                        <img
                            v-if="$page.props.auth.user.profile_photo_url"
                            :src="$page.props.auth.user.profile_photo_url"
                            :alt="$page.props.auth.user.name"
                            class="rounded-full h-10 w-10 object-cover ring-2 ring-white/20"
                        />
                        <div class="sm:flex hidden flex-col text-gray-50">
                            <div
                                class="flex flex-col items-left px-1 leading-none"
                            >
                                <span
                                    class="leading-none text-normal uppercase font-semibold"
                                >
                                    {{ user.getFullName }}
                                </span>
                                <span
                                    class="leading-none border-white text-xs opacity-80"
                                >
                                    {{ user.getRole }}
                                </span>
                            </div>
                        </div>
                        <div
                            class="absolute left-0 top-full mt-2 hidden group-hover:block bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 shadow-xl rounded-xl p-3 text-xs z-50 min-w-[16rem] border border-gray-100 dark:border-gray-700/60 transition-all duration-150"
                        >
                            <div
                                class="font-semibold text-gray-900 dark:text-gray-100 mb-1"
                            >
                                {{ user.getFullName }}
                            </div>
                            <div class="text-gray-600 dark:text-gray-400 mb-2">
                                {{ user.affiliation }}
                            </div>
                            <div class="flex flex-col gap-1">
                                <div>
                                    <span
                                        class="text-gray-500 dark:text-gray-400 font-medium"
                                        >Role:</span
                                    >
                                    {{ user.getRole }}
                                </div>
                                <div>
                                    <span
                                        class="text-gray-500 dark:text-gray-400 font-medium"
                                        >Email:</span
                                    >
                                    {{ user.email }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <!-- Settings Dropdown -->
                        <div class="flex flex-row gap-3 relative">
                            <!-- List of Accounts Dropdown -->
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150"
                                        >
                                            Apps
                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <!-- Applications Management -->
                                    <div
                                        class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-500 font-medium uppercase tracking-wider"
                                    >
                                        Available Applications
                                    </div>
                                    <template
                                        v-for="account in user.accounts"
                                        :key="account.application.id"
                                    >
                                        <DropdownLink
                                            v-if="
                                                account.application.status &&
                                                account.application.status ===
                                                    'true'
                                            "
                                            :href="
                                                route(account.application.url)
                                            "
                                        >
                                            <div class="flex items-center">
                                                <svg
                                                    v-if="
                                                        route().current(
                                                            account.application
                                                                .url,
                                                        )
                                                    "
                                                    class="mr-2 h-5 w-5 text-emerald-500 dark:text-emerald-400"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    />
                                                </svg>
                                                <div>
                                                    {{
                                                        account.application.name
                                                    }}
                                                </div>
                                            </div>
                                        </DropdownLink>
                                    </template>
                                    <div
                                        class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-500 font-medium uppercase tracking-wider"
                                    >
                                        Additional Access
                                    </div>
                                    <form
                                        @submit.prevent="
                                            requestNewApplicationAccess(team)
                                        "
                                    >
                                        <DropdownLink
                                            :href="route('profile.show')"
                                        >
                                            Request New Access
                                        </DropdownLink>
                                    </form>
                                </template>
                            </Dropdown>

                            <top-action-btn
                                class="shadow-none hover:scale-105 active:scale-100 dark:bg-gray-800 dark:border-gray-700"
                                @click="openHistory"
                                title="View activity history"
                            >
                                <template #icon>
                                    <bell-icon
                                        class="h-auto sm:w-6 w-4 text-gray-700 dark:text-gray-200"
                                        :class="
                                            Notification.notifications.value
                                                .length
                                                ? 'animate-wiggle'
                                                : ''
                                        "
                                    />
                                </template>
                            </top-action-btn>
                            <FullscreenToggle />
                            <!-- Teams Dropdown -->
                            <Dropdown
                                v-if="$page.props.jetstream.hasTeamFeatures"
                                align="right"
                                width="60"
                            >
                                <template #trigger>
                                    <span
                                        v-if="
                                            $page.props.auth.user.current_team
                                        "
                                        class="inline-flex rounded-md"
                                    >
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150"
                                        >
                                            {{
                                                $page.props.auth.user
                                                    .current_team.name
                                            }}

                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <div class="w-60">
                                        <!-- Group Management -->
                                        <div
                                            class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-500 font-medium uppercase tracking-wider"
                                        >
                                            Manage Group
                                        </div>

                                        <!-- Team Settings -->
                                        <DropdownLink
                                            v-if="
                                                $page.props.auth.user
                                                    .current_team
                                            "
                                            :href="
                                                route(
                                                    'teams.show',
                                                    $page.props.auth.user
                                                        .current_team,
                                                )
                                            "
                                        >
                                            Group Settings
                                        </DropdownLink>

                                        <DropdownLink
                                            v-if="
                                                $page.props.jetstream
                                                    .canCreateTeams
                                            "
                                            :href="route('teams.create')"
                                        >
                                            Create New Group
                                        </DropdownLink>

                                        <!-- Group Switcher -->
                                        <template
                                            v-if="
                                                $page.props.auth.user.all_teams
                                                    .length > 1
                                            "
                                        >
                                            <div
                                                class="border-t border-gray-200 dark:border-gray-700"
                                            />

                                            <div
                                                class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-500 font-medium uppercase tracking-wider"
                                            >
                                                Switch Group
                                            </div>

                                            <template
                                                v-for="team in $page.props.auth
                                                    .user.all_teams"
                                                :key="team.id"
                                            >
                                                <form
                                                    @submit.prevent="
                                                        switchToTeam(team)
                                                    "
                                                >
                                                    <DropdownLink as="button">
                                                        <div
                                                            class="flex items-center"
                                                        >
                                                            <svg
                                                                v-if="
                                                                    team.id ==
                                                                    $page.props
                                                                        .auth
                                                                        .user
                                                                        .current_team_id
                                                                "
                                                                class="mr-2 h-5 w-5 text-emerald-500 dark:text-emerald-400"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke-width="1.5"
                                                                stroke="currentColor"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                                />
                                                            </svg>

                                                            <div>
                                                                {{ team.name }}
                                                            </div>
                                                        </div>
                                                    </DropdownLink>
                                                </form>
                                            </template>
                                        </template>
                                    </div>
                                </template>
                            </Dropdown>

                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150"
                                        >
                                            Settings
                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <!-- Account Management -->
                                    <div
                                        class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-500 font-medium uppercase tracking-wider"
                                    >
                                        Manage Account
                                    </div>

                                    <DropdownLink :href="route('profile.show')">
                                        Profile
                                    </DropdownLink>

                                    <DropdownLink
                                        v-if="
                                            $page.props.jetstream.hasApiFeatures
                                        "
                                        :href="route('api-tokens.index')"
                                    >
                                        API Tokens
                                    </DropdownLink>

                                    <div
                                        class="border-t border-gray-200 dark:border-gray-700"
                                    />

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
                    <div
                        class="-mr-2 flex items-center sm:hidden w-full justify-end gap-2"
                    >
                        <FullscreenToggle />
                        <button
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-white/10 focus:outline-none transition duration-150 ease-in-out"
                            @click="
                                showingNavigationDropdown =
                                    !showingNavigationDropdown
                            "
                        >
                            <svg
                                class="h-6 w-6"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex':
                                            !showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex':
                                            showingNavigationDropdown,
                                    }"
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
            <div
                :class="{
                    block: showingNavigationDropdown,
                    hidden: !showingNavigationDropdown,
                }"
                class="sm:hidden bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700"
            >
                <ResponsiveNavLink
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                >
                    Dashboard
                </ResponsiveNavLink>
                <ResponsiveNavLink
                    v-if="user.isAdmin"
                    :href="route('administrator.index')"
                    :active="route().current('administrator.index')"
                >
                    Administrator
                </ResponsiveNavLink>
                <template
                    v-if="user.accounts"
                    v-for="account in user.accounts"
                    :key="account.id"
                >
                    <ResponsiveNavLink
                        v-if="account.application.status === 'true'"
                        :href="route(account.application.url)"
                        :active="route().current(account.application.url)"
                    >
                        {{ account.application.name }}
                    </ResponsiveNavLink>
                </template>

                <!-- Responsive Settings Options -->
                <div
                    class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700"
                >
                    <div
                        class="border-t border-gray-200 dark:border-gray-700 py-2"
                    >
                        <div
                            class="block px-4 py-1 text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider"
                        >
                            Profile
                        </div>
                        <div v-if="user" class="flex items-center px-4">
                            <div
                                class="flex flex-col text-gray-700 dark:text-gray-300 whitespace-nowrap"
                            >
                                <div class="flex items-center">
                                    <span
                                        class="leading-tight uppercase font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        {{ user.getFullName }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-1 text-xs">
                                    <span
                                        class="leading-tight text-gray-500 dark:text-gray-400"
                                    >
                                        {{ user.getRole }}
                                    </span>
                                    <span class="mx-1 text-gray-400">|</span>
                                    <span
                                        class="leading-tight text-gray-500 dark:text-gray-400"
                                    >
                                        {{ user.email }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div
                            v-else
                            class="flex items-center p-4 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300"
                        >
                            Please Login
                        </div>
                    </div>
                    <!-- Team Management -->
                    <div
                        v-if="$page.props.jetstream.hasTeamFeatures"
                        class="border-t border-gray-200 dark:border-gray-700 py-2"
                    >
                        <div
                            class="block px-4 py-1 text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider"
                        >
                            Manage Team
                        </div>

                        <!-- Team Settings -->
                        <ResponsiveNavLink
                            :href="
                                route(
                                    'teams.show',
                                    $page.props.auth.user.current_team,
                                )
                            "
                            :active="route().current('teams.show')"
                        >
                            Team Settings
                        </ResponsiveNavLink>

                        <ResponsiveNavLink
                            v-if="$page.props.jetstream.canCreateTeams"
                            :href="route('teams.create')"
                            :active="route().current('teams.create')"
                        >
                            Create New Team
                        </ResponsiveNavLink>

                        <!-- Team Switcher -->
                        <div
                            v-if="$page.props.auth.user.all_teams.length > 1"
                            class="border-t border-gray-200 dark:border-gray-700 py-2"
                        >
                            <div
                                class="block px-4 py-1 text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider"
                            >
                                Switch Teams
                            </div>

                            <template
                                v-for="team in $page.props.auth.user.all_teams"
                                :key="team.id"
                            >
                                <form @submit.prevent="switchToTeam(team)">
                                    <ResponsiveNavLink as="button">
                                        <div class="flex items-center">
                                            <svg
                                                v-if="
                                                    team.id ===
                                                    $page.props.auth.user
                                                        .current_team_id
                                                "
                                                class="me-2 h-5 w-5 text-emerald-500 dark:text-emerald-400"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <div>{{ team.name }}</div>
                                        </div>
                                    </ResponsiveNavLink>
                                </form>
                            </template>
                        </div>
                    </div>

                    <div
                        class="border-t border-gray-200 dark:border-gray-700 py-2"
                    >
                        <div
                            class="block px-4 py-1 text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider"
                        >
                            Settings
                        </div>
                        <ResponsiveNavLink
                            :href="route('profile.show')"
                            :active="route().current('profile.show')"
                        >
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
                <NavLink
                    class="text-white hover:text-gray-200"
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                >
                    <div class="flex gap-1 items-center sm:p-2 p-1">
                        <span class="sm:flex hidden whitespace-nowrap">
                            Dashboard
                        </span>
                    </div>
                </NavLink>
                <NavLink
                    v-if="user.isAdmin"
                    class="text-white hover:text-gray-200"
                    :href="route('administrator.index')"
                    :active="route().current('administrator.index')"
                >
                    <div class="flex gap-1 items-center sm:p-2 p-1">
                        <span class="sm:flex hidden whitespace-nowrap">
                            Administrator
                        </span>
                    </div>
                    <template #subLinks>
                        <router-link
                            v-for="subLink in [
                                {
                                    name: 'administrator.users',
                                    label: 'Users',
                                },
                                {
                                    name: 'administrator.approved-accounts',
                                    label: 'Accounts Approval',
                                },
                                {
                                    name: 'administrator.applications',
                                    label: 'Applications',
                                },
                            ]"
                            v-bind:key="subLink.name"
                            :to="{ name: subLink.name }"
                            :class="
                                $route.name === subLink.name
                                    ? 'inline-flex items-center px-1 pt-1 border-b-2 border-emerald-400 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-emerald-700 transition duration-150 ease-in-out'
                                    : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out'
                            "
                        >
                            {{ subLink.label }}
                        </router-link>
                    </template>
                </NavLink>
                <template
                    v-for="account in user.accounts"
                    :key="account.application.id"
                >
                    <NavLink
                        v-if="account.application.status === 'true'"
                        class="text-white hover:text-gray-200"
                        :href="route(account.application.url)"
                        :active="route().current(account.application.url)"
                    >
                        <div class="flex gap-1 items-center sm:p-2 p-1">
                            <span class="sm:flex hidden whitespace-nowrap">
                                {{ account.application.name }}
                            </span>
                        </div>
                        <template #subLinks>
                            <router-link
                                v-for="subLink in account.application.appTabs"
                                v-bind:key="subLink.name"
                                :to="{ name: subLink.name }"
                                :class="
                                    $route.name === subLink.name
                                        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-emerald-400 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-emerald-700 transition duration-150 ease-in-out'
                                        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out'
                                "
                            >
                                {{ subLink.label }}
                            </router-link>
                        </template>
                    </NavLink>
                </template>
                <NavLink class="text-white hover:text-gray-200" href="/">
                    <div class="flex gap-1 items-center sm:p-2 p-1">
                        <span class="sm:flex hidden whitespace-nowrap">
                            Gene Bank
                        </span>
                    </div>
                </NavLink>
            </template>
            <template #content>
                <main>
                    <slot />
                </main>
            </template>
        </sidebar-layout>

        <!-- Activity Log History Modal -->
        <Modal :show="showHistoryModal" maxWidth="2xl" @close="closeHistory">
            <div
                class="p-6 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-xl transition-colors duration-200"
            >
                <div
                    class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700/60"
                >
                    <h2 class="text-base font-semibold flex items-center gap-2">
                        <i
                            class="fas fa-history text-emerald-500 dark:text-emerald-400"
                        ></i>
                        Activity History
                    </h2>
                    <button
                        class="text-xs font-medium text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"
                        @click="closeHistory"
                    >
                        Close
                    </button>
                </div>

                <div class="mt-4">
                    <div
                        v-if="historyError"
                        class="text-sm text-red-600 dark:text-red-400 p-2 bg-red-50 dark:bg-red-950/40 rounded-lg border border-red-100 dark:border-red-900/40"
                    >
                        {{ historyError }}
                    </div>
                    <div
                        v-else
                        class="max-h-[60vh] overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg"
                        @scroll.passive="onHistoryScroll"
                    >
                        <table
                            class="min-w-full text-xs divide-y divide-gray-200 dark:divide-gray-700"
                        >
                            <thead
                                class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 font-semibold sticky top-0"
                            >
                                <tr>
                                    <th class="px-3 py-2.5 text-left">When</th>
                                    <th class="px-3 py-2.5 text-left">
                                        Description
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-100 dark:divide-gray-700/60 bg-white dark:bg-gray-800"
                            >
                                <tr
                                    v-for="item in historyItems"
                                    :key="item.id"
                                    class="hover:bg-gray-50/50 dark:hover:bg-gray-700/40 transition-colors"
                                >
                                    <td
                                        class="px-3 py-2.5 text-gray-500 dark:text-gray-400 whitespace-nowrap"
                                    >
                                        {{ formatWhen(item.created_at) }}
                                    </td>
                                    <td
                                        class="px-3 py-2.5 text-gray-800 dark:text-gray-200 font-medium"
                                    >
                                        {{ formatDescription(item) }}
                                    </td>
                                </tr>
                                <tr
                                    v-if="historyLoading"
                                    class="text-gray-500 dark:text-gray-400"
                                >
                                    <td
                                        colspan="2"
                                        class="px-3 py-3 text-center"
                                    >
                                        <div
                                            class="inline-flex items-center gap-2"
                                        >
                                            <div
                                                class="w-3.5 h-3.5 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin"
                                            ></div>
                                            Loading activity...
                                        </div>
                                    </td>
                                </tr>
                                <tr
                                    v-else-if="
                                        !historyItems.length && !historyLoading
                                    "
                                    class="text-sm text-gray-400 dark:text-gray-500"
                                >
                                    <td
                                        colspan="2"
                                        class="px-3 py-6 text-center"
                                    >
                                        No activity recorded yet
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </Modal>
    </div>
</template>
