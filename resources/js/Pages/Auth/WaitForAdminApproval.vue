<script setup>
import PageLayout from "@/Layouts/PageLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import GreenWaves from "@/Components/GreenWaves.vue";
import NewAccountProgressView from "@/Pages/Auth/NewAccountProgressView.vue";
import PublicPageSection from "@/Layouts/components/PublicPageSection.vue";
import AddAccount from "@/Pages/Profile/Partials/AddAccount.vue";
</script>

<template>
    <Head title="Wait for Admin Approval" />
    <page-layout>
        <green-waves />
        <div class="grid grid-cols-1 w-full bg-transparent">
            <public-page-section class="flex items-center justify-center">
                <AuthenticationCard class="min-h-[90vh]">
                    <new-account-progress-view />
                    <h2 class="font-semibold text-2xl text-center text-gray-800">Please Wait for Admin Approval</h2>
                    <div class="mb-3 text-center pt-5">
                        <p class="text-md text-gray-600">{{ $page.props.message }}</p>
                    </div>

                    <add-account class="border rounded-lg border-gray-200 p-5" />

                    <div class="mt-4 flex items-center justify-end">
                        <div>
                            <Link
                                :href="route('profile.show')"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Edit Profile</Link>

                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ml-2"
                            >
                                Log Out
                            </Link>
                        </div>
                    </div>
                </AuthenticationCard>
            </public-page-section>
        </div>
    </page-layout>
</template>

<style scoped>

</style>
