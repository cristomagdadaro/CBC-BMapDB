<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PageLayout from '@/Layouts/PageLayout.vue';
import Logo from "@/Components/Icons/Logo.vue";
import TextField from "@/Components/Form/TextField.vue";
import SelectField from "@/Components/Form/SelectField.vue";
import { Projects } from "@/Pages/constants.ts";

const form = useForm({
    fname: '',
    mname: '',
    lname: '',
    suffix: '',
    account_for: '',
    email: '',
    mobile_no: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onBefore: () => console.log(form),
        onFinish: (response) => function (){
            console.log(response);
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <Head title="Register" />
    <page-layout>
        <AuthenticationCard>
            <template #logo>
                <Logo classes="sm:h-24 h-14" />
            </template>
            <div class="border-b pb-1 mb-2">
                <h1 class="font-medium">Registration Form</h1>
                <p class="text-xs text-gray-600">Fill in all the required(<span class="text-red-600">*</span>) fields.</p>
            </div>
            <form @submit.prevent="submit" class="grid sm:grid-cols-2 grid-cols-1 gap-2">
                <TextField id="fname" label="First Name" v-model="form.fname" type="text" required autofocus autocomplete="name" :error="form.errors.fname" />
                <TextField id="mname" label="Middle Name" v-model="form.mname" type="text" autofocus autocomplete="name" :error="form.errors.mname" />
                <TextField id="lname" label="Last Name" v-model="form.lname" type="text" required autofocus autocomplete="name" :error="form.errors.lname" />
                <TextField id="suffix" label="Suffix" v-model="form.suffix" type="text" autofocus autocomplete="name" :error="form.errors.suffix" />
                <SelectField id="account_for" label="Account For" v-model="form.account_for" type="text" required autofocus autocomplete="name" :error="form.errors.account_for" :options="Projects" />
                <TextField id="mobile_no" label="Mobile No." v-model="form.mobile_no" type="text" autofocus autocomplete="name" :error="form.errors.mobile_no" />
                <TextField id="email" label="Email" v-model="form.email" type="email" required autocomplete="email" :error="form.errors.email" />
                <TextField id="password" label="Password" v-model="form.password" typeInput="password" required autocomplete="new-password" :error="form.errors.password" />
                <TextField id="password_confirmation" label="Confirm Password" v-model="form.password_confirmation" typeInput="password" required autocomplete="new-password" :error="form.errors.password_confirmation" />

                <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                    <InputLabel for="terms">
                        <div class="flex items-center">
                            <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

                            <div class="ml-2">
                                I agree to the <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Privacy Policy</a>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.terms" />
                    </InputLabel>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Already registered?
                    </Link>

                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Register
                    </PrimaryButton>
                </div>
            </form>
        </AuthenticationCard>
    </page-layout>
</template>
