<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    fname: props.user.fname,
    mname: props.user.mname,
    lname: props.user.lname,
    suffix: props.user.suffix,
    mobile_no: props.user.mobile_no,
    affiliation: props.user.affiliation,
    email: props.user.email,
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (! photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title>
            Profile Information
        </template>

        <template #description>
            Update your account's profile information and email address.
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input
                    id="photo"
                    ref="photoInput"
                    type="file"
                    class="hidden"
                    @change="updatePhotoPreview"
                >

                <InputLabel for="photo" value="Photo" />

                <!-- Current Profile Photo -->
                <div v-show="! photoPreview" class="mt-2">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="mt-2">
                    <span
                        class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'"
                    />
                </div>

                <SecondaryButton class="mt-2 me-2" type="button" @click.prevent="selectNewPhoto">
                    Select A New Photo
                </SecondaryButton>

                <SecondaryButton
                    v-if="user.profile_photo_path"
                    type="button"
                    class="mt-2"
                    @click.prevent="deletePhoto"
                >
                    Remove Photo
                </SecondaryButton>

                <InputError :message="form.errors.photo" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <div>
                    <InputLabel for="affiliation" value="Affiliation" />
                    <select-search-field id="affiliation"
                                         :api-link="route('api.institutes.index.public')"
                                         v-model="form.affiliation"
                                         :class="!form.affiliation?'opacity-30':''"
                                         class="mt-1 block w-full"
                                         autocomplete="affiliation"/>
                    <InputError :message="form.errors.affiliation" class="mt-2" />
                </div>
            </div>
            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="name" value="Name" />
                <div class="grid sm:grid-cols-4 sm:gap-2 gap-1">
                    <div>
                        <TextInput
                            id="fname"
                            v-model="form.fname"
                            type="text"
                            class="mt-1 block w-full"
                            :class="!form.fname?'opacity-30':''"
                            placeholder="First"
                            autocomplete="fname"
                        />
                        <InputError :message="form.errors.fname" class="mt-2" />
                    </div>
                    <div>
                        <TextInput
                            id="mname"
                            v-model="form.mname"
                            type="text"
                            class="mt-1 block w-full"
                            :class="!form.mname?'opacity-30':''"
                            placeholder="Middle"
                            autocomplete="mname"
                        />
                        <InputError :message="form.errors.mname" class="mt-2" />
                    </div>
                    <div>
                        <TextInput
                            id="lname"
                            v-model="form.lname"
                            type="text"
                            class="mt-1 block w-full"
                            :class="!form.lname?'opacity-30':''"
                            placeholder="Last"
                            autocomplete="lname"
                        />
                        <InputError :message="form.errors.lname" class="mt-2" />
                    </div>
                    <div>
                        <TextInput
                            id="suffix"
                            v-model="form.suffix"
                            type="text"
                            :class="!form.suffix?'opacity-30':''"
                            class="mt-1 block w-full"
                            placeholder="Suffix"
                            autocomplete="suffix"
                        />
                        <InputError :message="form.errors.suffix" class="mt-2" />
                    </div>
                </div>
            </div>
            <!-- Mobile and Email -->
            <div class="col-span-6 sm:col-span-4">
                <div class="grid sm:grid-cols-2 sm:gap-2 gap-1">
                    <div>
                        <InputLabel for="email" value="Email" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            :class="!form.email?'opacity-30':''"
                            placeholder="sample@sample.com"
                            class="mt-1 block w-full"
                            autocomplete="username"
                        />
                        <InputError :message="form.errors.email" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="email" value="Mobile No." />
                        <TextInput
                            id="mobile_no"
                            v-model="form.mobile_no"
                            type="tel"
                            :class="!form.mobile_no?'opacity-30':''"
                            placeholder="(+63)"
                            class="mt-1 block w-full"
                            autocomplete="mobile_no"
                        />
                        <InputError :message="form.errors.mobile_no" class="mt-2" />
                    </div>
                </div>
                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                    <p class="text-sm mt-2">
                        Your email address is unverified.

                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            @click.prevent="sendEmailVerification"
                        >
                            Click here to re-send the verification email.
                        </Link>
                    </p>

                    <div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                        A new verification link has been sent to your email address.
                    </div>
                </div>
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </FormSection>
</template>
