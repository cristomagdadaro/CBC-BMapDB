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
        <div class="relative h-screen w-full overflow-hidden bg-transparent">

            <!-- Background -->
            <hero-image-particles-background
                class="absolute inset-0 -z-10"
                particles-id="login-particles-js"
            />

            <!-- Centered Content -->
            <div class="relative z-10 flex items-center justify-center h-full px-4">

                <AuthenticationCard class="w-full max-w-4xl">

                    <div class="grid sm:grid-cols-2 items-center gap-6">

                        <!-- Left Side -->
                        <div class="flex flex-row gap-3 items-center">
                            <div class="bg-pin-green-light rounded-xl p-3">
                                <authentication-card-logo class="drop-shadow" />
                            </div>
                            <div class="font-bold lg:text-3xl md:text-2xl text-xl leading-tight text-white">
                                <span class="text-pin-lime">P</span>lant Breeders &
                                <span class="text-pin-lime">I</span>nnovators
                                <span class="text-pin-lime">N</span>etwork System
                            </div>
                        </div>

                        <!-- Login Form -->
                        <div class="flex flex-col gap-3 bg-white hover:bg-cbc-yellow duration-500 p-5 shadow-sm rounded-xl">

                            <form @submit.prevent="submit">

                                <text-field
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    label="Email"
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
                                    class="mt-4"
                                    required
                                    autocomplete="current-password"
                                    :error="form.errors.password"
                                />

                                <div class="flex justify-between items-center mt-3">

                                    <label class="flex items-center">
                                        <Checkbox v-model:checked="form.remember" name="remember" />
                                        <span class="ml-2 text-sm text-gray-700">Remember me</span>
                                    </label>

                                    <Link
                                        v-if="canResetPassword"
                                        :href="route('password.request')"
                                        class="text-sm text-pin-green hover:underline"
                                    >
                                        Forgot password?
                                    </Link>

                                </div>

                                <div class="text-gray-600 text-xs mt-3">

                                    By using our system you agree with our

                                    <Link
                                        :href="route('support.terms-of-use')"
                                        class="underline text-pin-green hover:text-pin-green-dark"
                                    >
                                        Terms of Use
                                    </Link>

                                    and

                                    <Link
                                        :href="route('support.privacy-policy')"
                                        class="underline text-pin-green hover:text-pin-green-dark"
                                    >
                                        Privacy Policy
                                    </Link>

                                </div>

                                <PrimaryButton
                                    class="w-full mt-4"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Sign in
                                </PrimaryButton>

                            </form>

                            <div class="border-b border-gray-200 my-2"></div>

                            <div class="flex md:flex-row flex-col items-center gap-2">

                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="text-white uppercase text-xs bg-pin-green hover:bg-pin-green-dark font-semibold px-4 py-2 rounded-lg w-full flex items-center justify-center"
                                >
                                    Sign Up
                                </Link>

                                <span class="text-gray-500 text-xs">OR</span>

                                <button
                                    @click="handleGoogleSignIn"
                                    class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg w-full flex items-center justify-center gap-2"
                                >

                                    <span class="font-semibold text-sm">
                                        Sign in with Google
                                    </span>

                                </button>

                            </div>

                        </div>

                    </div>

                </AuthenticationCard>

            </div>

        </div>
    </page-layout>
</template>
