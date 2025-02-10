<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PageLayout from "@/Layouts/PageLayout.vue";
import GreenWaves from "@/Components/GreenWaves.vue";
import NewAccountProgressView from "@/Pages/Auth/NewAccountProgressView.vue";
import CheckallIcon from "@/Components/Icons/CheckallIcon.vue";
import PublicPageSection from "@/Layouts/components/PublicPageSection.vue";
import ParticlesBackground from "@/Components/ParticlesBackground.vue";

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <Head title="Email Verification" />
    <page-layout>
        <green-waves />
        <particles-background />
        <div class="grid grid-cols-1 w-full bg-transparent">
            <public-page-section class="flex items-center justify-center">
                <AuthenticationCard class="min-h-[90vh] sm:max-w-3xl mx-auto">
                    <template #logo>
                        <AuthenticationCardLogo />
                    </template>
                    <new-account-progress-view />
                    <div class="mb-4 text-lg text-gray-600 font-bold">
                        You only have limited access. Please verify your email address to continue.
                    </div>
                    <div class="mb-4 text-sm text-gray-600">
                        Before continuing, could you verify your email address by clicking on the link we just emailed? If you didn't receive the email, we will gladly send you another.
                    </div>

<!--                    <div class="mb-4 text-sm text-gray-600">
                        Note: After you have verified your email address, you will have to wait for the administrator to approve your account. This might take a some time, please be patient.
                    </div>-->
                    <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-cbc-dark-green">
                        A new verification link has been sent to the email address you provided in your profile
                        settings.
                    </div>

                    <form @submit.prevent="submit">
                        <div class="mt-4 flex flex-wrap items-center justify-between gap-0.5">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" class="w-full rounded-b-none" :disabled="form.processing">
                                Resend Verification Email
                            </PrimaryButton>

                            <div class="flex items-center gap-0.5 w-full">
                                <Link
                                    :href="route('profile.show')"
                                    class="inline-flex items-center w-full justify-center px-4 py-2 whitespace-nowrap bg-gray-800 border border-transparent rounded-bl-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Edit Profile</Link>

                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="inline-flex items-center w-full justify-center px-4 py-2 whitespace-nowrap bg-gray-800 border border-transparent rounded-br-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Log Out
                                </Link>
                            </div>
                        </div>
                    </form>
                </AuthenticationCard>
            </public-page-section>
        </div>
    </page-layout>
</template>
