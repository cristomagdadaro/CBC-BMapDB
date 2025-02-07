<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import User from "@/Modules/core/domain/auth/User.ts";
import WelcomeUserBanner from "@/Pages/Dashboard/components/WelcomeUserBanner.vue";
import {usePage} from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import {ref, onMounted} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Logo from "@/Components/Icons/Logo.vue";
import BreedersMapCard from "@/Pages/Projects/BreedersMap/presentation/BreedersMapCard.vue";
import DashboardCard from "@/Components/DashboardCard.vue";

const page = usePage();

const user = new User(page.props.auth.user);
const showNote = ref(true);

onMounted(() => {
    const hasSeenNote = localStorage.getItem("hasSeenNote");
    if (!hasSeenNote) {
        showNote.value = true;
        localStorage.setItem("hasSeenNote", "true");
    } else {
        showNote.value = false;
    }
});

</script>

<template>
    <AppLayout title="Dashboard">
        <div class="bg-white min-h-screen p-4">
            <welcome-user-banner>
                Welcome, {{ user.getFullName }}!
                <p v-if="user.isAdmin" class="text-center">
                    You have admin privileges
                </p>
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
                                {{ $page.props.appName }}
                            </h3>
                        </div>
                    </div>
                    <p>
                        An integrated platform designed to centralize and manage all databases for <span class="whitespace-nowrap">{{$page.props.companyName}}</span>. This system serves as a foundational tool in streamlining data access and management across the country.
                    </p>
                    <p>
                        We appreciate your patience and understanding as we continue to improve and evolve the system to meet the highest standards of reliability and efficiency. Your feedback is invaluable in helping us identify and address any issues, ensuring that the <span class="whitespace-nowrap">{{$page.props.appName}}</span> becomes an indispensable resource for <span class="whitespace-nowrap">{{$page.props.companyName}}’s</span> operations.
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
<!--            <div class="grid grid-cols-2">
                <div class="p-3">
                    <div class="flex flex-col gap-5">
                        <div v-if="user.userPermissionsList.length">
                            <p class="font-bold">
                                Special Permissions:
                            </p>
                            <ul>
                                <li v-for="permission in user.userPermissionsList" :key="permission">
                                    {{ permission.name }}
                                </li>
                            </ul>
                        </div>

                        <div v-if="user.rolePermissionsList.length">
                            <p class="font-bold">
                                Permissions according to your role:
                            </p>
                            <ul>
                                <li v-for="permission in user.rolePermissionsList" :key="permission">
                                    {{ permission.name }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>-->
            <div class="flex flex-wrap justify-evenly drop-shadow w-full gap-2">

                <dashboard-card class="bg-blue-600 text-white">
                    <template v-slot:title>
                        <div class="flex flex-col leading-[1]">
                            <span>
                                Announcements
                            </span>
                            <span class="text-[0.7rem] text-gray-300">
                                {{ (new Date()).toISOString().slice(0, 16).replace('T', ' ') }}
                            </span>
                        </div>
                    </template>
                    <template v-slot:body>
                        <div class="flex flex-col gap-1">
                            <div class="text-cbc-brown bg-gray-100 px-3 py-1 rounded">
                                We will be having system maintenance on February 26, 2025. The system will be temporarily unavailable until February 31, 2025. Thank you.
                            </div>
                            <div class="text-cbc-brown bg-gray-100 px-3 py-1 rounded">
                                4053 new varieties across 35 commodities
                            </div>
                            <div class="text-cbc-brown bg-gray-100 px-3 py-1 rounded">
                                6 new institutions joined our initiative
                            </div>
                            <div class="text-cbc-brown bg-gray-100 px-3 py-1 rounded">
                                Researchers can now request more information directly to the breeders
                            </div>
                        </div>
                    </template>
                </dashboard-card>
                <dashboard-card class="bg-green-600 text-white" v-if="user.userPermissionsList.length">
                    <template v-slot:title>
                        <div class="flex flex-col leading-[1]">
                            <span>
                                User Permissions
                            </span>
                            <span class="text-[0.7rem] text-gray-200">
                                Special permissions given to you
                            </span>
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
                            <span>
                                Role permission
                            </span>
                            <span class="text-[0.7rem]">
                                Inherited permissions by your role
                            </span>
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
                <breeders-map-card />
                <dashboard-card class="bg-blue-600 text-white">
                    <template v-slot:title>
                        <div class="flex flex-col leading-[1]">
                            <span>
                                Upcoming Features
                            </span>
                            <span class="text-[0.7rem] text-gray-300">
                                We are working hard to further improve the system
                            </span>
                        </div>
                    </template>
                    <template v-slot:body>
                        <div class="flex flex-col gap-1">
                            <div class="text-cbc-brown bg-gray-100 px-3 py-2 rounded leading-tight">
                                <p class="font-bold">
                                    Executive Dashboards
                                </p>
                                <p class="text-sm">
                                    System can generate and publish comprehensive summary of information
                                </p>
                            </div>
                            <div class="text-cbc-brown bg-gray-100 px-3 py-2 rounded leading-tight">
                                <p class="font-bold">
                                    Application Programming Interface (API) Service
                                </p>
                                <p class="text-sm">
                                    Provide real-time and secure data access to other systems
                                </p>
                            </div>
                            <div class="text-cbc-brown bg-gray-100 px-3 py-2 rounded leading-tight">
                                <p class="font-bold">
                                    Chat Room
                                </p>
                                <p class="text-sm">
                                    Built-in messaging platform to allow users to interact and collaborate within PIN system
                                </p>
                            </div>

                        </div>
                    </template>
                </dashboard-card>
            </div>
        </div>
    </AppLayout>
</template>
