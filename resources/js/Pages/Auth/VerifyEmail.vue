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
        <AuthenticationCard>
            <template #logo>
                <AuthenticationCardLogo />
            </template>
            <new-account-progress-view />
            <div class="mb-4 text-lg text-gray-600 font-bold">
                You only have limited access. Please verify your email address to continue.
            </div>
            <div class="mb-4 text-sm text-gray-600">
                Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
            </div>

            <div class="mb-4 text-sm text-gray-600">
                Note: After you have verified your email address, you will have to wait for the administrator to approve your account. This might take a some time, please be patient.
            </div>
            <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-green-600">
                A new verification link has been sent to the email address you provided in your profile settings.
            </div>

            <form @submit.prevent="submit">
                <div class="mt-4 flex items-center justify-between">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Resend Verification Email
                    </PrimaryButton>

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
            </form>
        </AuthenticationCard>
    </page-layout>
</template>
