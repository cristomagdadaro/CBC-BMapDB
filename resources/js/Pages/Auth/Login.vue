<script setup >
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PageLayout from '@/Layouts/PageLayout.vue';
import TextField from "@/Components/Form/TextField.vue";
import GreenWaves from "@/Components/GreenWaves.vue";
import PublicPageSection from "@/Layouts/components/PublicPageSection.vue";
defineProps({
    canResetPassword: Boolean,
    status: String,
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
</script>
<script>
function onSignIn(googleUser) {
    var profile = googleUser.getBasicProfile();
    console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
    console.log('Name: ' + profile.getName());
    console.log('Image URL: ' + profile.getImageUrl());
    console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
}
</script>
<template>
    <Head title="Log in" />
    <page-layout>
        <green-waves />
        <div class="grid grid-cols-1 w-full bg-transparent">
            <public-page-section class="flex items-center justify-center">
                <AuthenticationCard class="min-h-[90vh]">
                    <div class="relative grid sm:grid-cols-2 grid-rows-1 items-center">
                        <div class="drop-shadow-lg select-none text-gray-50 flex flex-col w-full z-50 sm:text-left text-center">
                        <span class="text-lg leading-relaxed">
                            Login to
                        </span>
                            <span class="font-bold sm:text-3xl text-2xl leading-tight">
                            <span class="text-cbc-dark-green">P</span>lant&nbsp;Breeders
                            <span class="text-cbc-dark-green">I</span>nnovation
                             <span class="text-cbc-dark-green">N</span>etwork&nbsp;System
                        </span>
                        </div>
                        <div class="bg-cbc-dark-green sm:p-3 sm:px-5 p-4 shadow-lg rounded-md sm:min-w-[15rem] min-w-full">
                            <div v-if="status" class="mb-4 font-medium text-sm">
                                {{ status }}
                            </div>

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
                                    <Link v-if="canResetPassword" :href="route('password.request')" class="underline opacity-50 text-gray-100 hover:opacity-100 text-right rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Forgot your password?
                                    </Link>
                                </div>
                                <div class="g-signin2 text-gray-100 text-xs my-2" data-onsuccess="onSignIn">Sign in with Google</div>
                            </form>
                        </div>
                    </div>
                </AuthenticationCard>
            </public-page-section>
        </div>
    </page-layout>
</template>
