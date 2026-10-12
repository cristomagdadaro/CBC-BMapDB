<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import Checkbox from "@/Components/Checkbox.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
import TextField from "@/Components/Form/TextField.vue";
import HeroImageParticlesBackground from "@/Components/HeroImageParticlesBackground.vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
    canRegister: Boolean,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? "on" : "",
    })).post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};

const handleGoogleSignIn = () => {
    window.location.href = "/auth/google";
};
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
            <div
                class="relative z-10 flex items-center justify-start h-full px-4"
            >
                <AuthenticationCard class="w-full max-w-2xl">
                    <div class="items-center gap-6">
                        <!-- Login Form -->
                        <div
                            class="flex flex-col gap-3 bg-white duration-500 p-5 shadow-sm rounded-xl"
                        >
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

                                <div
                                    class="flex justify-between items-center mt-3"
                                >
                                    <label class="flex items-center">
                                        <Checkbox
                                            v-model:checked="form.remember"
                                            name="remember"
                                        />
                                        <span class="ml-2 text-sm text-gray-700"
                                            >Remember me</span
                                        >
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

                            <div
                                class="flex md:flex-row flex-col items-center gap-2"
                            >
                                <Link
                                    v-if="route().has('register')"
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
                                    <svg
                                        class="w-5 h-5"
                                        viewBox="0 0 48 48"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            fill="#EA4335"
                                            d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"
                                        ></path>
                                        <path
                                            fill="#4285F4"
                                            d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"
                                        ></path>
                                        <path
                                            fill="#FBBC05"
                                            d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"
                                        ></path>
                                        <path
                                            fill="#34A853"
                                            d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"
                                        ></path>
                                    </svg>
                                    <span
                                        class="font-semibold text-sm whitespace-nowrap"
                                    >
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
