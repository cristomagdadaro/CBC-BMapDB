<script setup>
import PageLayout from "@/Layouts/PageLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import HeroImageParticlesBackground from "@/Components/HeroImageParticlesBackground.vue";
import NewAccountProgressView from "@/Pages/Auth/NewAccountProgressView.vue";
import PublicPageSection from "@/Layouts/components/PublicPageSection.vue";
import AddAccount from "@/Pages/Profile/Partials/AddAccount.vue";
</script>

<template>
    <Head title="Wait for Admin Approval" />
    <page-layout>
        <hero-image-particles-background class="absolute inset-0" particles-id="login-particles-js" />
        <div class="grid grid-cols-1 w-full bg-transparent">
            <public-page-section class="flex items-center justify-center">
                <AuthenticationCard class="min-h-[90vh] sm:max-w-4xl mx-auto">
                    <new-account-progress-view />
                    <h2 class="font-semibold text-2xl sm:text-3xl text-center text-gray-900 font-display">Please Wait for Admin Approval</h2>
                    <div class="mb-3 text-center pt-5">
                        <p class="text-md text-gray-600">{{ $page.props.message }}</p>
                    </div>

                    <add-account class="border rounded-xl border-gray-200 p-5 bg-pin-gray" />

                    <div class="mt-4 flex items-center justify-end">
                        <div class="flex items-center gap-3">
                            <Link
                                :href="route('profile.show')"
                                class="text-sm text-pin-green hover:underline rounded-md focus-ring"
                            >
                                Edit Profile</Link>

                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="text-sm text-pin-green hover:underline rounded-md focus-ring"
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
