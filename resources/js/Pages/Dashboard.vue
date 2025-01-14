<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import User from "@/Modules/core/domain/auth/User.ts";
import WelcomeUserBanner from "@/Pages/Dashboard/components/WelcomeUserBanner.vue";
import {usePage} from "@inertiajs/vue3";

const page = usePage();

const user = new User(page.props.auth.user);
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="bg-white min-h-screen">
            <welcome-user-banner>
                Welcome, {{ user.getFullName }}!
            </welcome-user-banner>
            <p v-if="user.isAdmin" class="text-center p-2">
                You have admin privileges.
            </p>
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

            {{ $page.props.teamPermissions }}
        </div>
    </AppLayout>
</template>
