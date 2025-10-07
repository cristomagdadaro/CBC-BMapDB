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
import axios from 'axios';

const page = usePage();

const user = new User(page.props.auth.user);
const showNote = ref(true);
const showtempPasswordAlert = ref(false);

// Dashboard data from backend
const statistics = computed(() => page.props.statistics || {});
const recentActivities = computed(() => page.props.recentActivities || []);
const onlineUsers = computed(() => page.props.onlineUsers || []);
const systemOverview = computed(() => page.props.systemOverview || {});
const breederStats = computed(() => page.props.breederStats || null);
const focalPersonStats = computed(() => page.props.focalPersonStats || null);
const researcherStats = computed(() => page.props.researcherStats || null);

onMounted(() => {
    const hasSeenNote = localStorage.getItem("hasSeenNote");
    if (!hasSeenNote) {
        showNote.value = true;
        localStorage.setItem("hasSeenNote", "true");
    } else {
        showNote.value = false;
    }

    showtempPasswordAlert.value = !!page.props.tempPasswordAlert;

    // Track user activity
    trackActivity();
    setInterval(trackActivity, 60000); // Update every minute
});

const trackActivity = async () => {
    try {
        await axios.post('/dashboard/activity');
    } catch (error) {
        console.error('Failed to track activity:', error);
    }
};

const refreshActivities = () => {
    router.reload({ only: ['recentActivities'] });
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

            <!-- Statistics Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <statistics-card
                    title="Total Breeders"
                    :value="statistics.totalBreeders || 0"
                    icon="fas fa-user-tie"
                    bg-color="from-green-500 to-green-600"
                />
                <statistics-card
                    title="Total Commodities"
                    :value="statistics.totalCommodities || 0"
                    icon="fas fa-seedling"
                    bg-color="from-blue-500 to-blue-600"
                />
                <statistics-card
                    title="TWG Experts"
                    :value="statistics.totalTWGExperts || 0"
                    icon="fas fa-user-graduate"
                    bg-color="from-purple-500 to-purple-600"
                />
                <statistics-card
                    title="TWG Projects"
                    :value="statistics.totalTWGProjects || 0"
                    icon="fas fa-project-diagram"
                    bg-color="from-indigo-500 to-indigo-600"
                />
            </div>

            <!-- Role-Specific Statistics -->
            <div v-if="breederStats" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <statistics-card
                    title="My Commodities"
                    :value="statistics.myCommodities || 0"
                    subtitle="Commodities you've registered"
                    icon="fas fa-leaf"
                    bg-color="from-emerald-500 to-emerald-600"
                />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Main Content Area -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Recent Activities -->
                    <recent-activities-widget
                        :activities="recentActivities"
                        @refresh="refreshActivities"
                    />

                    <!-- Breeder Stats -->
                    <div v-if="breederStats && breederStats.recentCommodities?.length" class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-seedling text-green-500 mr-2"></i>
                            My Recent Commodities
                        </h3>
                        <div class="space-y-2">
                            <div
                                v-for="commodity in breederStats.recentCommodities"
                                :key="commodity.id"
                                class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition"
                            >
                                <div>
                                    <p class="font-medium text-gray-900">{{ commodity.name }}</p>
                                    <p class="text-sm text-gray-500">{{ commodity.variety }}</p>
                                </div>
                                <span class="text-xs text-gray-400">{{ new Date(commodity.updated_at).toLocaleDateString() }}</span>
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
                        <system-overview-widget :overview="systemOverview" />
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
    </AppLayout>
</template>
