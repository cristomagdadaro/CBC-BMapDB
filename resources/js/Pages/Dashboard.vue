<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import User from "@/Modules/core/domain/auth/User.ts";
import { usePage } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import { ref, onMounted } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Logo from "@/Components/Icons/Logo.vue";
import DashboardCard from "@/Pages/Dashboard/components/DashboardCard.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import OnlineUsersWidget from "@/Pages/Dashboard/components/OnlineUsersWidget.vue";
import RecentActivitiesWidget from "@/Pages/Dashboard/components/RecentActivitiesWidget.vue";
import SystemOverviewWidget from "@/Pages/Dashboard/components/SystemOverviewWidget.vue";
import QuickActionsWidget from "@/Pages/Dashboard/components/QuickActionsWidget.vue";
import DashboardService from "@/Services/DashboardService.js";
import DashboardShell from "@/Pages/Dashboard/components/DashboardShell.vue";
import DashboardSummaryCard from "@/Pages/Dashboard/components/DashboardSummaryCard.vue";

const page = usePage();

const user = new User(page.props.auth.user);
const roleNames = (page.props?.auth?.user?.roles || [])
    .map((role) => role?.name ?? role)
    .filter(Boolean);
const isAdmin = roleNames.includes("Administrator");
const isFocal = roleNames.includes("Focal Person");
const isBreeder = roleNames.includes("Breeder");
const isResearcher = roleNames.includes("Researcher");
const isTwgManager = roleNames.includes("TWG Manager");
const showNote = ref(true);
const showtempPasswordAlert = ref(false);

// Dashboard data fetched from API
const systemStats = ref({});
const onlineUsers = ref([]);
const recentUsers = ref([]);
const systemActivities = ref([]);
const userRoleDistribution = ref({});
const loading = ref(true);
const lastUpdated = ref(null);

onMounted(async () => {
    const hasSeenNote = localStorage.getItem("hasSeenNote");
    if (!hasSeenNote) {
        showNote.value = true;
        localStorage.setItem("hasSeenNote", "true");
    } else {
        showNote.value = false;
    }

    showtempPasswordAlert.value = !!page.props.tempPasswordAlert;

    // Fetch dashboard data from API
    await fetchDashboardData();
    lastUpdated.value = new Date().toISOString();

    // Track user activity
    await trackActivity();
    setInterval(trackActivity, 60000); // Update every minute
});

const fetchDashboardData = async () => {
    try {
        loading.value = true;

        // Fetch system stats (available to all users)
        systemStats.value = await DashboardService.getSystemStats();

        // Fetch system activities
        systemActivities.value = await DashboardService.getSystemActivities();

        // Fetch admin-specific data
        if (user.isAdmin) {
            try {
                onlineUsers.value = await DashboardService.getOnlineUsers();
                recentUsers.value = await DashboardService.getRecentUsers();
                userRoleDistribution.value =
                    await DashboardService.getUserRoleDistribution();
            } catch (error) {
                console.error("Error fetching admin data:", error);
            }
        }
    } catch (error) {
        console.error("Error fetching dashboard data:", error);
    } finally {
        loading.value = false;
    }
};

const refreshDashboard = async () => {
    await fetchDashboardData();
    lastUpdated.value = new Date().toISOString();
};

const trackActivity = async () => {
    try {
        await DashboardService.updateActivity();
    } catch (error) {
        console.error("Failed to track activity:", error);
    }
};

const refreshActivities = async () => {
    try {
        systemActivities.value = await DashboardService.getSystemActivities();
    } catch (error) {
        console.error("Error refreshing activities:", error);
    }
};
</script>

<template>
    <AppLayout title="Dashboard">
        <DashboardShell
            title="System Dashboard"
            :isLoading="loading"
            :lastUpdated="lastUpdated"
            @refresh="refreshDashboard"
        >
            <!-- Notes/Alerts Modals -->
            <modal :show="showNote" @close="showNote = false">
                <div
                    class="p-6 md:p-8 text-justify flex flex-col gap-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 transition-colors duration-200"
                >
                    <div
                        class="sm:text-xl text-lg text-center font-bold text-gray-900 dark:text-white"
                    >
                        <logo
                            class="w-auto h-20 mx-auto fill-current text-gray-900 dark:text-white"
                        />
                        <div class="leading-tight mt-2">
                            <h3
                                class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-medium"
                            >
                                Welcome to
                            </h3>
                            <h3
                                class="text-xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ $appName }}
                            </h3>
                        </div>
                    </div>
                    <p class="text-sm leading-relaxed">
                        An integrated platform designed to centralize and manage
                        all databases for
                        <span
                            class="font-semibold text-gray-900 dark:text-white whitespace-nowrap"
                            >{{ $companyName }}</span
                        >. This system serves as a foundational tool in
                        streamlining data access and management across the
                        country.
                    </p>
                    <p class="text-sm leading-relaxed">
                        We appreciate your patience and understanding as we
                        continue to improve and evolve the system to meet the
                        highest standards of reliability and efficiency. Your
                        feedback is invaluable in helping us identify and
                        address any issues, ensuring that
                        <span
                            class="font-semibold text-gray-900 dark:text-white whitespace-nowrap"
                            >{{ $appName }}</span
                        >
                        becomes an indispensable resource for
                        <span
                            class="font-semibold text-gray-900 dark:text-white whitespace-nowrap"
                            >{{ $companyName }}'s</span
                        >
                        operations.
                    </p>
                    <p class="text-sm leading-relaxed">
                        Thank you for your support as we work to deliver a
                        robust and dependable solution.
                    </p>
                    <div
                        class="bg-amber-50 dark:bg-amber-950/40 border-l-4 border-amber-500 text-amber-800 dark:text-amber-200 p-3 text-xs leading-relaxed rounded-r"
                    >
                        Please note that the platform is currently in early
                        development. While we are actively refining and
                        enhancing features, some temporary errors or
                        inconsistencies may arise.
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        You may contact us via email at
                        <a
                            href="mailto:pin.dacbc@gmail.com"
                            class="text-emerald-600 dark:text-emerald-400 hover:underline"
                            >pin.dacbc@gmail.com</a
                        >
                        for any concerns or inquiries.
                    </p>
                    <primary-button
                        @click="showNote = false"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white py-2 px-4 rounded-lg font-medium transition items-center flex justify-center shadow-sm"
                    >
                        Got it!
                    </primary-button>
                </div>
            </modal>

            <modal
                :show="showtempPasswordAlert"
                @close="showtempPasswordAlert = false"
            >
                <div
                    class="p-6 md:p-8 text-justify flex flex-col gap-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 transition-colors duration-200"
                >
                    <div class="text-center">
                        <logo
                            class="w-auto h-20 mx-auto fill-current text-gray-900 dark:text-white"
                        />
                        <div
                            class="leading-tight uppercase mt-3 sm:text-xl text-lg font-bold text-gray-900 dark:text-white"
                        >
                            {{ page.props.tempPasswordAlert }}
                        </div>
                        <span
                            class="text-xs text-gray-500 dark:text-gray-400 mt-1 block"
                        >
                            Please check your email for the temporary password
                        </span>
                    </div>
                    <update-password-form />
                    <primary-button
                        @click="showtempPasswordAlert = false"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white py-2 px-4 rounded-lg font-medium transition items-center flex justify-center shadow-sm"
                    >
                        Finish!
                    </primary-button>
                </div>
            </modal>

            <!-- Dashboard Main Content -->
            <template v-if="!loading">
                <!-- System Statistics Cards -->
                <div
                    class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6"
                >
                    <dashboard-summary-card
                        title="Total Users"
                        to="administrator.users"
                        background-color="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-800"
                        :sum-value="systemStats.totalUsers || 0"
                    />
                    <dashboard-summary-card
                        title="Active Users"
                        to="administrator.users"
                        background-color="bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-800"
                        :sum-value="systemStats.activeUsers || 0"
                        sub-value-label="Last 7 days"
                    />
                    <dashboard-summary-card
                        title="Online Now"
                        to="administrator.users"
                        background-color="bg-gradient-to-br from-teal-500 to-teal-600 dark:from-teal-600 dark:to-teal-800"
                        :sum-value="systemStats.onlineUsers || 0"
                        :sub-value-label="
                            systemStats.onlineUsers > 1
                                ? 'System Users'
                                : 'System User'
                        "
                    />
                    <dashboard-summary-card
                        title="New Users"
                        to="administrator.users"
                        background-color="bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-800"
                        :sum-value="systemStats.recentRegistrations || 0"
                        sub-value-label="This month"
                    />
                    <dashboard-summary-card
                        title="Pending Accounts Approval"
                        to="administrator.approved-accounts"
                        background-color="bg-gradient-to-br from-amber-500 to-amber-600 dark:from-amber-600 dark:to-amber-800"
                        :sum-value="systemStats.totalNotApprovedAccounts || 0"
                        v-if="isAdmin"
                    />
                    <dashboard-summary-card
                        title="Remaining Unverified Accounts"
                        to="administrator.users"
                        background-color="bg-gradient-to-br from-orange-500 to-orange-600 dark:from-orange-600 dark:to-orange-800"
                        :sum-value="systemStats.totalUnverifiedEmails || 0"
                        v-if="isAdmin"
                    />
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Main Content Area -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Recent System Activities -->
                        <recent-activities-widget
                            :activities="systemActivities"
                            @refresh="refreshActivities"
                        />

                        <!-- Recent Users (Admin only) -->
                        <div
                            v-if="user.isAdmin && recentUsers.length > 0"
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 p-6 transition-colors duration-200"
                        >
                            <h3
                                class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center"
                            >
                                <i
                                    class="fas fa-user-plus text-blue-500 dark:text-blue-400 mr-2"
                                ></i>
                                Recent User Registrations
                            </h3>
                            <div class="space-y-2">
                                <div
                                    v-for="recentUser in recentUsers"
                                    :key="recentUser.id"
                                    class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150"
                                >
                                    <div class="flex items-center">
                                        <img
                                            :src="recentUser.profile_photo_url"
                                            :alt="recentUser.name"
                                            class="w-9 h-9 rounded-full mr-3 ring-2 ring-gray-200 dark:ring-gray-600 object-cover"
                                        />
                                        <div>
                                            <p
                                                class="font-medium text-sm text-gray-900 dark:text-gray-100 flex items-center gap-2"
                                            >
                                                {{ recentUser.name }}
                                            </p>
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{ recentUser.role }}
                                            </p>
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs text-gray-400 dark:text-gray-500"
                                        >{{
                                            new Date(
                                                recentUser.created_at,
                                            ).toLocaleDateString()
                                        }}</span
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Permissions Cards -->
                        <dashboard-card
                            class="bg-emerald-700 dark:bg-emerald-800 text-white shadow-sm border border-emerald-800 dark:border-emerald-900 rounded-xl"
                            v-if="user.userPermissionsList.length"
                        >
                            <template v-slot:title>
                                <div class="flex flex-col leading-tight">
                                    <span class="font-semibold text-white"
                                        >User Permissions</span
                                    >
                                    <span class="text-xs text-emerald-100/80"
                                        >Special permissions assigned to your
                                        account</span
                                    >
                                </div>
                            </template>
                            <template v-slot:body>
                                <div
                                    class="flex flex-row gap-5 max-h-[15rem] overflow-y-auto"
                                >
                                    <ul
                                        class="italic text-sm list-disc list-inside space-y-1 text-emerald-50"
                                    >
                                        <li
                                            v-for="permission in user.userPermissionsList"
                                            :key="permission"
                                        >
                                            {{ permission.name }}
                                        </li>
                                    </ul>
                                </div>
                            </template>
                        </dashboard-card>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Quick Actions -->
                        <quick-actions-widget :user-role="user.getRole" />

                        <!-- Admin-Only Widgets -->
                        <template v-if="user.isAdmin">
                            <online-users-widget :online-users="onlineUsers" />
                            <system-overview-widget
                                :overview="userRoleDistribution"
                            />
                        </template>
                    </div>
                </div>
            </template>
        </DashboardShell>
    </AppLayout>
</template>
