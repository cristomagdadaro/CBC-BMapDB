<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PageLayout from '@/Layouts/PageLayout.vue';
import TextField from "@/Components/Form/TextField.vue";
import SelectField from "@/Components/Form/SelectField.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService.ts";
import {onBeforeMount, ref, watch} from "vue";
import NewAccountProgressView from "@/Pages/Auth/NewAccountProgressView.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import GreenWaves from "@/Components/GreenWaves.vue";
import PublicPageSection from "@/Layouts/components/PublicPageSection.vue";

const form = useForm({
    fname: '',
    mname: '',
    lname: '',
    suffix: '',
    account_for: '',
    role: '',
    email: '',
    affiliation: '',
    mobile_no: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const applications = ref([]);
const roles = ref([]);

const submit = () => {
    form.post(route('register'), {
        onFinish: (response) => function (){
            form.reset('password', 'password_confirmation');
        },
    });
};

const filteredRoles = ref([]); // To hold the roles specific to the selected application
const selectedApplication = ref(''); // To track the currently selected application.
const applicationRolesMap = {
    "1": ['TWG Manager', 'Researcher'], // TWG Db
    "2": ['Focal Person','Researcher', 'Breeder'], // Plant Breeders Map
};

async function getListOfApplications() {
    const api = new ApiService(route('api.applications.index.public'));
    await api.get().then(response => {
        applications.value = response.data;
    });
}

async function getListOfRoles() {
    const api = new ApiService(route('api.roles.index.public'));
    await api.get().then(response => {
        roles.value = response.data;
        filterRolesByApplication(); // Filter roles after fetching them
    });
}

// Function to filter roles based on the selected application
function filterRolesByApplication() {
    const allowedRoles = applicationRolesMap[selectedApplication.value] || [];
    filteredRoles.value = roles.value.filter(role =>
        allowedRoles.includes(role.label) // Assuming roles have a 'name' property
    );
}

// Watch for changes in the selected application to update the filtered roles
watch(selectedApplication, () => {
    form.account_for = selectedApplication.value;
    filterRolesByApplication();
});

onBeforeMount(async () => {
    await getListOfApplications();
    await getListOfRoles();

    applications.value = applications.value.map((application) => {
        return {
            id: application.id,
            value: application.id,
            label: application.name,
        };
    });

    roles.value = roles.value.map((role) => {
        return {
            id: role.id,
            value: role.id,
            label: role.name,
            permissions: role.permissions,
        };
    });
});
</script>

<template>
    <Head title="Register" />
    <page-layout>
        <green-waves />
        <div class="grid grid-cols-1 w-full bg-transparent">
            <public-page-section class="flex items-center justify-center">
                <AuthenticationCard class="min-h-[90vh]">
                <new-account-progress-view />
                <div class="border-b pb-1 mb-2">
                    <h1 class="font-medium text-lg">Registration Form</h1>
                    <p class="text-sm text-gray-600">Fill in all the required(<span class="text-red-600">*</span>) fields.</p>
                </div>
                <form @submit.prevent="submit" class="flex flex-col gap-2">
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-2">
                        <SelectField v-if="applications" id="account_for" label="Account For" v-model="selectedApplication" type="text" required autofocus autocomplete="name" :error="form.errors.account_for" :options="applications" />
                        <SelectField v-if="roles" id="role" label="Access Level" :disabled="!selectedApplication" v-model="form.role" type="text" required autofocus autocomplete="role" :error="form.errors.role" :options="filteredRoles" />
                    </div>
                    <div class="grid sm:grid-cols-4 grid-cols-1 gap-2">
                        <TextField id="fname" label="First Name" v-model="form.fname" type="text" required autofocus autocomplete="name" :error="form.errors.fname" />
                        <TextField id="mname" label="Middle Name" v-model="form.mname" type="text" autofocus autocomplete="name" :error="form.errors.mname" />
                        <TextField id="lname" label="Surname" v-model="form.lname" type="text" required autofocus autocomplete="name" :error="form.errors.lname" />
                        <TextField id="suffix" label="Suffix" v-model="form.suffix" type="text" autofocus autocomplete="name" :error="form.errors.suffix" />
                    </div>
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-2">
                        <TextField id="mobile_no" label="Mobile No." v-model="form.mobile_no" type="text" autofocus autocomplete="name" :error="form.errors.mobile_no" />
                        <TextField id="email" label="Email" v-model="form.email" type="email" required autocomplete="email" :error="form.errors.email" />
                    </div>
                    <select-search-field required :api-link="route('api.institutes.index.public')"  :error="form.errors.affiliation" label="Agency/Institution/Office" v-model="form.affiliation" />
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-2">
                        <TextField id="password" label="Password" v-model="form.password" typeInput="password" required autocomplete="new-password" :error="form.errors.password" />
                        <TextField id="password_confirmation" label="Confirm Password" v-model="form.password_confirmation" typeInput="password" required autocomplete="new-password" :error="form.errors.password_confirmation" />
                    </div>
                    <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                        <InputLabel for="terms">
                            <div class="flex items-center">
                                <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

                                <div class="ml-2">
                                    I agree to the <a target="_blank" :href="route('support.terms-of-use')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Terms of Service</a> and <a target="_blank" :href="route('support.privacy-policy')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Privacy Policy</a>
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
            </public-page-section>
        </div>
    </page-layout>
</template>
