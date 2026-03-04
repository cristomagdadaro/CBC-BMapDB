<script setup >
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PageLayout from '@/Layouts/PageLayout.vue';
import TextField from "@/Components/Form/TextField.vue";
import HeroImageParticlesBackground from "@/Components/HeroImageParticlesBackground.vue";
import PublicPageSection from "@/Layouts/components/PublicPageSection.vue";
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

const handleGoogleSignIn = () => {
    window.location.href = "/auth/google";
}
</script>
<template>
    <Head title="Log in" />
    <page-layout>
        <div class="relative grid grid-cols-1 w-full bg-transparent min-h-screen">
            <hero-image-particles-background class="absolute inset-0" particles-id="login-particles-js" />
            <public-page-section class="relative z-10 flex items-center justify-center">
                <AuthenticationCard class="min-h-[90vh] sm:max-w-4xl mx-auto">
                    <div class="relative grid sm:grid-cols-2 grid-rows-1 items-center">
                        <div>
                            <div class="drop-shadow-lg text-gray-900 flex flex-col w-full z-50 text-left mb-5">
                                <div class="flex flex-row gap-3 items-center">
                                    <div class="bg-pin-green-light h-full rounded-xl p-3">
                                        <authentication-card-logo class="drop-shadow" />
                                    </div>
                                    <div class="font-bold lg:text-3xl md:text-2xl text-xl leading-[1.2rem] lg:leading-[1.8rem] sm:text-left text-center text-white">
                                        <span class="text-pin-lime">P</span>lant&nbsp;Breeders&nbsp;&
                                        <span class="text-pin-lime">I</span>nnovators
                                        <span class="text-pin-lime">N</span>etwork&nbsp;System
                                    </div>
                                </div>
                                <div v-if="status" class="m-2 font-medium text-sm text-center">
                                    {{ status }}
                                </div>
                            </div>
                            <span class="absolute bottom-0 text-gray-500 drop-shadow text-xs p-1">
                                {{ $appVersion }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-2 bg-white hover:bg-cbc-yellow duration-500 sm:p-6 p-4 shadow-sm rounded-xl sm:min-w-[15rem] min-w-full">
                            <form @submit.prevent="submit">
                                <text-field
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    label="Email"
                                    class="mt-1 block w-full"
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
                                    class="mt-4 block w-full"
                                    required
                                    autocomplete="current-password"
                                    :error="form.errors.password"
                                />

                                <div class="flex justify-between w-full">
                                    <label class="flex items-center">
                                        <Checkbox v-model:checked="form.remember" name="remember" />
                                        <span class="ml-2 text-sm text-gray-700">Remember me</span>
                                    </label>
                                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-pin-green my-3 hover:underline text-right rounded-md focus-ring">
                                        Forgot your password?
                                    </Link>
                                </div>
                                <div class="text-gray-600 text-xs">
                                    By using our system you agree with our
                                    <Link :href="route('support.terms-of-use')" class="underline text-pin-green hover:text-pin-green-dark rounded-md focus-ring">Terms of Use</Link> and
                                    <Link :href="route('support.privacy-policy')" class="underline text-pin-green hover:text-pin-green-dark rounded-md focus-ring">Privacy Policy</Link>
                                </div>
                                <div class="flex flex-col text-xs mt-4 sm:gap-5 gap-3">
                                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Sign in
                                    </PrimaryButton>
                                </div>
                            </form>
                            <div class="border-b border-gray-200 my-1"></div>
                            <div class="flex md:flex-row flex-col items-center gap-2">
                                <Link v-if="canRegister" :href="route('register')" class="text-white uppercase text-xs drop-shadow bg-pin-green hover:bg-pin-green-dark active:scale-95 duration-300 font-semibold px-4 py-2 rounded-lg w-full flex items-center justify-center gap-1 focus-ring">
                                    Sign Up
                                </Link>
                                <span class="text-gray-500 text-xs my-1">OR</span>
                                <button @click="handleGoogleSignIn" class="disabled:opacity-25 disabled:cursor-not-allowed bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 duration-300 px-4 py-2 rounded-lg w-full flex items-center justify-center gap-1 focus-ring">
                                    <label class="flex flex-col leading-[1rem]">
                                        <span class="font-semibold">Sign in with</span>
                                        <span class="hidden text-xs">Available Soon</span>
                                    </label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="p-0 m-0" viewBox="0 0 16 16">
                                        <path d="M15.545 6.558a9.4 9.4 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.7 7.7 0 0 1 5.352 2.082l-2.284 2.284A4.35 4.35 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.8 4.8 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.7 3.7 0 0 0 1.599-2.431H8v-3.08z"/>
                                    </svg>
                                </button>

                            </div>
                        </div>
                    </div>
                </AuthenticationCard>
            </public-page-section>
        </div>
    </page-layout>
</template>
