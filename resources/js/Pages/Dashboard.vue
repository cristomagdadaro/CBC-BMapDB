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
        <div class="bg-white">
            <welcome-user-banner>
                Welcome, {{ user.getFullName }}!
            </welcome-user-banner>
            <p v-if="user.isAdmin" class="text-center p-2">
                You have admin privileges.
            </p>
            <div class="grid grid-cols-2">
                <div class="p-3">
                    <h3>
                        Accounts
                    </h3>
                    <div>
                        <p>
                            You have the following accounts:
                        </p>
                        <ul>
                            <li v-for="account in user.accountsList">
                                {{ account }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="p-3">
                    <h3>
                        Permissions
                    </h3>
                    <div>
                        <p>
                            You have the following permissions:
                        </p>
                        <ul>
                            <li v-for="permission in user.permissionsList" :key="permission">
                                {{ permission }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
