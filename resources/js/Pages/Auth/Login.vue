<script setup >
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import PageLayout from '@/Layouts/PageLayout.vue';
import Logo from "@/Components/Icons/Logo.vue";
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
    <PageLayout :is-wide-display="true">
        <AuthenticationCard>
            <div class="flex sm:flex-row flex-col items-center">
                <div class="flex justify-between z-0">
                    <div class="text-gray-700 flex flex-col sm:w-full w-1/2">
                        <span class="text-sm leading-5">
                            Department&nbsp;of&nbsp;Agriculture
                        </span>
                        <span class="font-bold sm:text-4xl text-xl sm:leading-8 leading-4">
                            Crop Biotechnology Center
                        </span>
                            <span class="text-xs opacity-50">
                            For Authorized Personnel Only
                        </span>
                    </div>
                    <div class="flex justify-center h-auto sm:w-1/2 w-auto">
                        <img src="../../../../public/img/cbc_statue_cartoon.png" class="sm:h-[27rem] h-28 w-auto sm:absolute bottom-0 drop-shadow-lg" alt="DA-CBC Statue" />
                    </div>
                </div>
                <div class="bg-cbc-dark-green sm:p-2 p-4 rounded-md sm:min-w-[15rem] min-w-full">
                    <div v-if="status" class="mb-4 font-medium text-sm">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit">
                        <div>
                            <InputLabel for="email" value="Email" class="text-gray-100" />
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="mt-1 block w-full"
                                required
                                autofocus
                                autocomplete="username"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="password" value="Password" class="text-gray-100" />
                            <TextInput
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="mt-1 block w-full"
                                required
                                autocomplete="current-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="block mt-4">
                            <label class="flex items-center">
                                <Checkbox v-model:checked="form.remember" name="remember" />
                                <span class="ml-2 text-xs text-gray-100">Remember me</span>
                            </label>
                        </div>

                        <div class="flex flex-col text-xs mt-4">
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
    </PageLayout>
</template>
