<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import User from "@/Modules/core/domain/auth/User.ts";
import WelcomeUserBanner from "@/Pages/Dashboard/components/WelcomeUserBanner.vue";
import {usePage} from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import {ref, onMounted} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Logo from "@/Components/Icons/Logo.vue";

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
        <div class="bg-white min-h-screen">
            <welcome-user-banner>
                Welcome, {{ user.getFullName }}!
            </welcome-user-banner>
            <p v-if="user.isAdmin" class="text-center p-2">
                You have admin privileges
            </p>

            <modal title="Feedback" :show="showNote" @close="showNote = false">
                <div class="p-10 text-justify flex flex-col gap-3">
                    <div class="sm:text-xl text-lg text-center font-bold text-gray-900">
                        <h3 class="text-normal font-normal">
                            Welcome to the
                        </h3>
                        <div>
                            <logo class="w-auto h-20 mx-auto" />
                            <h3>
                                {{ $page.props.appName }} (PIN)
                            </h3>
                        </div>
                    </div>
                    <p>
                        An integrated platform designed to centralize and manage all databases for <span class="whitespace-nowrap">{{$page.props.companyName}}</span>. This system serves as a foundational tool in streamlining data access and management across the organization.
                    </p>
                    <p>
                        Please note that the platform is currently in the early stages of development, and while we are actively refining and enhancing its features, some errors or inconsistencies may arise during this phase.
                    </p>
                    <p>
                        We appreciate your patience and understanding as we continue to improve and evolve the system to meet the highest standards of reliability and efficiency. Your feedback is invaluable in helping us identify and address any issues, ensuring that the <span class="whitespace-nowrap">{{$page.props.appName}}</span> becomes an indispensable resource for <span class="whitespace-nowrap">{{$page.props.companyName}}’s</span> operations.
                    </p>
                    <p>
                        Thank you for your support as we work to deliver a robust and dependable solution.
                    </p>
                    <p class="text-gray-400">
                        You may contact us via email at <a href="mailto:{{$page.props.supportEmail}}" class="text-cbc-dark-green">{{$page.props.supportEmail}}</a> for any concerns or inquiries.
                    </p>
                    <primary-button @click="showNote = false" class="bg-cbc-dark-green hover:bg-cbc-dark-green text-white py-2 px-4 rounded items-center flex justify-center">
                        Got it!
                    </primary-button>
                </div>
            </modal>

            <div class="grid grid-cols-2">
                <div class="p-3">
                    <p class="font-bold">
                        You have the following accounts:
                    </p>
                    <ul>
                        <li v-for="account in user.accountsList">
                            {{ account }}
                        </li>
                    </ul>
                </div>
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
            </div>
        </div>
    </AppLayout>
</template>
