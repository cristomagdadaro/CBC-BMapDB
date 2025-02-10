<script setup >
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PageLayout from '@/Layouts/PageLayout.vue';
import TextField from "@/Components/Form/TextField.vue";
import GreenWaves from "@/Components/GreenWaves.vue";
import PublicPageSection from "@/Layouts/components/PublicPageSection.vue";
import TabLink from "@/Components/Header/TabLink.vue";
import {GoogleLogin} from "vue3-google-login";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
defineProps({
    canResetPassword: Boolean,
    status: String,
    canRegister: Boolean,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
const googleClientId = "347920378124-l0mnce2uk9lcqfhcm8hkfv2tttnnti14.apps.googleusercontent.com";
const handleGoogleSignIn = async (response) => {
    window.location.href = "/auth/google";
}
</script>
<template>
    <Head title="Log in" />
    <page-layout>
        <green-waves />
        <div class="grid grid-cols-1 w-full bg-transparent">
            <public-page-section class="flex items-center justify-center">
                <AuthenticationCard class="min-h-[90vh] sm:max-w-3xl mx-auto">
                    <div class="relative grid sm:grid-cols-2 grid-rows-1 items-center">
                        <div class="drop-shadow-lg select-none text-gray-50 flex flex-col w-full z-50 sm:text-left text-center">
                            <span class="text-normal leading-relaxed">Login to</span>
                            <div class="flex flex-row gap-2 items-center">
                                <authentication-card-logo class="drop-shadow" />
                                <span class="font-bold sm:text-3xl text-2xl leading-tight">
                                    <span class="text-cbc-dark-green">P</span>lant&nbsp;Breeders
                                    <span class="text-cbc-dark-green">I</span>nnovation
                                    <span class="text-cbc-dark-green">N</span>etwork&nbsp;System
                                </span>
                            </div>
                            <div v-if="status" class="m-2 font-medium text-sm text-center">
                                {{ status }}
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 bg-cbc-dark-green sm:p-3 sm:px-5 p-4 shadow-lg rounded-md sm:min-w-[15rem] min-w-full">
                            <form @submit.prevent="submit">
                                <text-field
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    label="Email"
                                    class="mt-1 block w-full text-white"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    :error="form.errors.email"
                                />
                                <text-field
                                    id="password"
                                    v-model="form.password"
                                    type-input="password"
                                    label="Password"
                                    class="mt-4 block w-full text-white"
                                    required
                                    autocomplete="current-password"
                                    :error="form.errors.password"
                                />

                                <div class="block mt-4">
                                    <label class="flex items-center">
                                        <Checkbox v-model:checked="form.remember" name="remember" />
                                        <span class="ml-2 text-xs text-gray-100">Remember me</span>
                                    </label>
                                </div>
                                <div class="flex flex-col text-xs mt-4 sm:gap-5 gap-3">
                                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Log in
                                    </PrimaryButton>
                                </div>
                            </form>
                            <div class="border-b my-1"></div>
                            <div class="flex flex-col items-center gap-1">
                                <GoogleLogin :clientId="googleClientId" :callback="handleGoogleSignIn">
                                    <button class="bg-red-500 text-white px-4 py-2 rounded w-full flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                                            <path d="M15.545 6.558a9.4 9.4 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.7 7.7 0 0 1 5.352 2.082l-2.284 2.284A4.35 4.35 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.8 4.8 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.7 3.7 0 0 0 1.599-2.431H8v-3.08z"/>
                                        </svg>
                                       Sign up with Google
                                    </button>
                                </GoogleLogin>
                                <span class="text-white text-xs my-1">OR</span>
                                <Link v-if="canRegister" :href="route('register')" class="text-gray-100 hover:text-cbc-yellow text-center hover:underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Create a new account
                                </Link>
                                <Link v-if="canResetPassword" :href="route('password.request')" class="underline opacity-50 text-gray-100 my-3 hover:opacity-100 text-right rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Forgot your password?
                                </Link>
                            </div>

                        </div>
                    </div>
                </AuthenticationCard>
            </public-page-section>
        </div>
    </page-layout>
</template>
