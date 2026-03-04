<script setup>
import { nextTick, ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import HeroImageParticlesBackground from "@/Components/HeroImageParticlesBackground.vue";
import PublicPageSection from "@/Layouts/components/PublicPageSection.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
import ParticlesBackground from "@/Components/ParticlesBackground.vue";

const recovery = ref(false);

const form = useForm({
    code: '',
    recovery_code: '',
});

const recoveryCodeInput = ref(null);
const codeInput = ref(null);

const toggleRecovery = async () => {
    recovery.value ^= true;

    await nextTick();

    if (recovery.value) {
        recoveryCodeInput.value.focus();
        form.code = '';
    } else {
        codeInput.value.focus();
        form.recovery_code = '';
    }
};

const submit = () => {
    form.post(route('two-factor.login'));
};
</script>

<template>
    <Head title="Two-factor Confirmation" />
    <page-layout>
        <hero-image-particles-background class="absolute inset-0" particles-id="login-particles-js" />
        <div class="grid grid-cols-1 w-full bg-transparent">
            <public-page-section class="flex items-center justify-center">
                <AuthenticationCard class="min-h-[90vh] sm:max-w-3xl mx-auto">
                <h1 class="text-2xl sm:text-3xl font-bold font-display text-gray-900 mb-2">Two-Factor Confirmation</h1>
                <div class="mb-4 text-normal text-gray-600">
                    <template v-if="! recovery">
                        Please confirm access to your account by entering the authentication code provided by your authenticator application.
                    </template>

                    <template v-else>
                        Please confirm access to your account by entering one of your emergency recovery codes.
                    </template>
                </div>

                <form @submit.prevent="submit" class="bg-pin-gray rounded-xl p-5 border border-gray-200">
                    <div v-if="! recovery">
                        <InputLabel for="code" value="Code" />
                        <TextInput
                            id="code"
                            ref="codeInput"
                            v-model="form.code"
                            type="text"
                            inputmode="numeric"
                            class="mt-1 block w-full rounded-lg border-gray-200 focus:border-pin-green focus:ring-pin-green/20"
                            autofocus
                            autocomplete="one-time-code"
                        />
                        <InputError class="mt-2" :message="form.errors.code" />
                    </div>

                    <div v-else>
                        <InputLabel for="recovery_code" value="Recovery Code" />
                        <TextInput
                            id="recovery_code"
                            ref="recoveryCodeInput"
                            v-model="form.recovery_code"
                            type="text"
                            class="mt-1 block w-full rounded-lg border-gray-200 focus:border-pin-green focus:ring-pin-green/20"
                            autocomplete="one-time-code"
                        />
                        <InputError class="mt-2" :message="form.errors.recovery_code" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="button" class="text-sm text-pin-green hover:underline cursor-pointer rounded-md focus-ring" @click.prevent="toggleRecovery">
                            <template v-if="! recovery">
                                Use a recovery code
                            </template>

                            <template v-else>
                                Use an authentication code
                            </template>
                        </button>

                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Log in
                        </PrimaryButton>
                    </div>
                </form>
                </AuthenticationCard>
            </public-page-section>
        </div>
    </page-layout>
</template>
