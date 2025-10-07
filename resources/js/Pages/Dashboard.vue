<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import User from "@/Modules/core/domain/auth/User.ts";
import WelcomeUserBanner from "@/Pages/Dashboard/components/WelcomeUserBanner.vue";
import {usePage} from "@inertiajs/vue3";
import router from '@/router.js';
import Modal from "@/Components/Modal.vue";
import {ref, onMounted, computed} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Logo from "@/Components/Icons/Logo.vue";
import DashboardCard from "@/Components/DashboardCard.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import StatisticsCard from "@/Pages/Dashboard/components/StatisticsCard.vue";
import OnlineUsersWidget from "@/Pages/Dashboard/components/OnlineUsersWidget.vue";
import RecentActivitiesWidget from "@/Pages/Dashboard/components/RecentActivitiesWidget.vue";
import SystemOverviewWidget from "@/Pages/Dashboard/components/SystemOverviewWidget.vue";
import QuickActionsWidget from "@/Pages/Dashboard/components/QuickActionsWidget.vue";
import DashboardService from '@/Services/DashboardService.js';

const page = usePage();

const user = new User(page.props.auth.user);
const showNote = ref(true);
const showtempPasswordAlert = ref(false);

// Dashboard data fetched from API
const systemStats = ref({});
const onlineUsers = ref([]);
const recentUsers = ref([]);
const systemActivities = ref([]);
const userRoleDistribution = ref({});
const loading = ref(true);

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

    // Track user activity
    trackActivity();
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
                userRoleDistribution.value = await DashboardService.getUserRoleDistribution();
            } catch (error) {
                console.error('Error fetching admin data:', error);
            }
        }
    } catch (error) {
        console.error('Error fetching dashboard data:', error);
    } finally {
        loading.value = false;
    }
};

const trackActivity = async () => {
    try {
        await DashboardService.updateActivity();
    } catch (error) {
        console.error('Failed to track activity:', error);
    }
};

const refreshActivities = async () => {
    try {
        systemActivities.value = await DashboardService.getSystemActivities();
    } catch (error) {
        console.error('Error refreshing activities:', error);
    }
};
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="bg-gray-50 min-h-screen p-4">
            <welcome-user-banner>
                Welcome, {{ user.getFullName }}!
                <p v-if="user.isAdmin" class="text-center">
                    You have admin privileges
                </p>
            </welcome-user-banner>
            <welcome-user-banner v-show="page.props.acceptedBreederRole">
               {{ page.props.acceptedBreederRole }}
            </welcome-user-banner>

            <modal :show="showNote" @close="showNote = false">
                <div class="p-10 text-justify flex flex-col gap-3">
                    <div class="sm:text-xl text-lg text-center font-bold text-gray-900">
                        <logo class="w-auto h-20 mx-auto" />
                        <div class="leading-tight">
                            <h3 class="text-sm font-normal">
                                Welcome to
                            </h3>
                            <h3>
                                {{ $appName }}
                            </h3>
                        </div>
                    </div>
                    <p>
                        An integrated platform designed to centralize and manage all databases for <span class="whitespace-nowrap">{{$companyName}}</span>. This system serves as a foundational tool in streamlining data access and management across the country.
                    </p>
                    <p>
                        We appreciate your patience and understanding as we continue to improve and evolve the system to meet the highest standards of reliability and efficiency. Your feedback is invaluable in helping us identify and address any issues, ensuring that the <span class="whitespace-nowrap">{{ $appName }}</span> becomes an indispensable resource for <span class="whitespace-nowrap">{{$companyName}}'s</span> operations.
                    </p>
                    <p>
                        Thank you for your support as we work to deliver a robust and dependable solution.
                    </p>
                    <p class="bg-red-700 text-white p-2 text-sm leading-tight">
                        Please note that the platform is currently in the early stages of development, and while we are actively refining and enhancing its features, some errors or inconsistencies may arise during this phase.
                    </p>
                    <p>
                        You may contact us via email at <a href="mailto:pin.dacbc@gmail.com" class="text-cbc-dark-green">pin.dacbc@gmail.com</a> for any concerns or inquiries.
                    </p>
                    <primary-button @click="showNote = false" class="bg-cbc-dark-green hover:bg-cbc-dark-green text-white py-2 px-4 rounded items-center flex justify-center">
                        Got it!
                    </primary-button>
                </div>
            </modal>

            <modal :show="showtempPasswordAlert" @close="showtempPasswordAlert = false">
                <div class="p-10 text-justify flex flex-col gap-3">
                    <div class="text-gray-900 text-center ">
                        <logo class="w-auto h-20 mx-auto" />
                        <div class="leading-tight uppercase mt-3 sm:text-xl text-lg font-bold ">
                            {{ page.props.tempPasswordAlert }}
                        </div>
                        <span>
                            Please check your email for the temporary password
                        </span>
                    </div>
                    <update-password-form />
                    <primary-button @click="showtempPasswordAlert = false; " class="bg-cbc-dark-green hover:bg-cbc-dark-green text-white py-2 px-4 rounded items-center flex justify-center">
                        Finish!
                    </primary-button>
                </div>
            </modal>

            <!-- Loading State -->
            <div v-if="loading" class="flex justify-center items-center py-20">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin text-4xl text-cbc-dark-green mb-4"></i>
                    <p class="text-gray-600">Loading dashboard...</p>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div v-else>
                <!-- System Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <statistics-card
                        title="Total Users"
                        :value="systemStats.totalUsers || 0"
                        icon="fas fa-users"
                        bg-color="from-blue-500 to-blue-600"
                    />
                    <statistics-card
                        title="Active Users"
                        :value="systemStats.activeUsers || 0"
                        subtitle="Last 7 days"
                        icon="fas fa-user-check"
                        bg-color="from-green-500 to-green-600"
                    />
                    <statistics-card
                        title="Online Now"
                        :value="systemStats.onlineUsers || 0"
                        icon="fas fa-circle"
                        bg-color="from-emerald-500 to-emerald-600"
                    />
                    <statistics-card
                        title="New Users"
                        :value="systemStats.recentRegistrations || 0"
                        subtitle="This month"
                        icon="fas fa-user-plus"
                        bg-color="from-purple-500 to-purple-600"
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
                        <div v-if="user.isAdmin && recentUsers.length > 0" class="bg-white rounded-lg shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-user-plus text-blue-500 mr-2"></i>
                                Recent User Registrations
                            </h3>
                            <div class="space-y-2">
                                <div
                                    v-for="recentUser in recentUsers"
                                    :key="recentUser.id"
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition"
                                >
                                    <div class="flex items-center">
                                        <img
                                            :src="recentUser.profile_photo_url"
                                            :alt="recentUser.name"
                                            class="w-10 h-10 rounded-full mr-3"
                                        />
                                        <div>
                                            <p class="font-medium text-gray-900">{{ recentUser.name }}</p>
                                            <p class="text-xs text-gray-500">{{ recentUser.role }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ new Date(recentUser.created_at).toLocaleDateString() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions Cards (existing) -->
                        <dashboard-card class="bg-green-600 text-white" v-if="user.userPermissionsList.length">
                            <template v-slot:title>
                                <div class="flex flex-col leading-[1]">
                                    <span>User Permissions</span>
                                    <span class="text-[0.7rem] text-gray-200">Special permissions given to you</span>
                                </div>
                            </template>
                            <template v-slot:body>
                                <div class="flex flex-row gap-5 max-h-[15rem] overflow-hidden overflow-y-auto">
                                    <ul class="italic list-disc list-inside">
                                        <li v-for="permission in user.userPermissionsList" :key="permission">
                                            {{ permission.name }}
                                        </li>
                                    </ul>
                                </div>
                            </template>
                        </dashboard-card>

                        <dashboard-card class="bg-cbc-yellow text-dark" v-if="user.rolePermissionsList.length">
                            <template v-slot:title>
                                <div class="flex flex-col leading-[1]">
                                    <span>Role permission</span>
                                    <span class="text-[0.7rem]">Inherited permissions by your role</span>
                                </div>
                            </template>
                            <template v-slot:body>
                                <div class="flex flex-row gap-5 max-h-[15rem] overflow-hidden overflow-y-auto">
                                    <ul class="italic list-disc list-inside">
                                        <li v-for="permission in user.rolePermissionsList" :key="permission">
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
                            <system-overview-widget :overview="userRoleDistribution" />
                        </template>

                        <!-- Upcoming Features -->
                        <dashboard-card class="bg-blue-600 text-white">
                            <template v-slot:title>
                                <div class="flex flex-col leading-[1]">
                                    <span>Upcoming Features</span>
                                    <span class="text-[0.7rem] text-gray-300">We are working hard to further improve the system</span>
                                </div>
                            </template>
                            <template v-slot:body>
                                <div class="flex flex-col gap-1">
                                    <div class="text-cbc-brown bg-gray-100 px-3 py-2 rounded leading-tight">
                                        <p class="font-bold">Executive Dashboards</p>
                                        <p class="text-sm">System can generate and publish comprehensive summary of information</p>
                                    </div>
                                    <div class="text-cbc-brown bg-gray-100 px-3 py-2 rounded leading-tight">
                                        <p class="font-bold">Application Programming Interface (API) Service</p>
                                        <p class="text-sm">Provide real-time and secure data access to other systems</p>
                                    </div>
                                    <div class="text-cbc-brown bg-gray-100 px-3 py-2 rounded leading-tight">
                                        <p class="font-bold">Chat Room</p>
                                        <p class="text-sm">Built-in messaging platform to allow users to interact and collaborate within PIN system</p>
                                    </div>
                                    <div class="text-cbc-brown bg-gray-100 px-3 py-2 rounded leading-tight">
                                        <p class="font-bold">Data View Customization</p>
                                        <p class="text-sm">Users can customize the view of data based on their preferences</p>
                                    </div>
                                </div>
                            </template>
                        </dashboard-card>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
