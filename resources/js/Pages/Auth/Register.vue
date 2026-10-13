<script>
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PageLayout from '@/Layouts/PageLayout.vue';
import TextField from "@/Components/Form/TextField.vue";
import SelectField from "@/Components/Form/SelectField.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService";
import NewAccountProgressView from "@/Pages/Auth/NewAccountProgressView.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import HeroImageParticlesBackground from "@/Components/HeroImageParticlesBackground.vue";
import PublicPageSection from "@/Layouts/components/PublicPageSection.vue";
import axios from "axios";

export default {
    name: 'Register',
    components: {
        AuthenticationCard,
        Head,
        Link,
        Checkbox,
        InputError,
        InputLabel,
        PrimaryButton,
        PageLayout,
        TextField,
        SelectField,
        ApiService,
        NewAccountProgressView,
        SelectSearchField,
        PublicPageSection,
        HeroImageParticlesBackground,
    },
    props: {
        applications: {
            type: Array,
            default: () => [],
        },
        roles: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            selectedApplication: 2, // Default to Plant Breeders Map (id: 2)
            filteredRoles: [],
            appOptions: [],
            roleOptions: [],
            applicationRolesMap: {
                "1": ['TWG Manager', 'Researcher'],
                "2": ['Focal Person', 'Researcher', 'Breeder'],
            },
        };
    },
    computed: {
        effectiveApplications() {
            if (this.applications && this.applications.length > 0) {
                return this.applications;
            }
            return this.appOptions;
        },
        effectiveRoles() {
            if (this.roles && this.roles.length > 0) {
                return this.roles;
            }
            return this.roleOptions;
        },
    },
    async mounted() {
        await this.ensureOptionsLoaded();
        this.selectedApplication = 2;
        this.form.account_for = 2;
        this.filterRolesByApplication();
    },
    beforeMount() {
        this.form = useForm({
            fname: '',
            mname: '',
            lname: '',
            suffix: '',
            account_for: 2,
            role: '',
            email: '',
            affiliation: '',
            mobile_no: '',
            password: '',
            password_confirmation: '',
            terms: false,
        });
    },
    methods: {
        async ensureOptionsLoaded() {
            if (!this.applications || this.applications.length === 0) {
                try {
                    const res = await axios.get('/api/applications/options');
                    this.appOptions = res.data?.data || res.data || [];
                } catch (e) {
                    this.appOptions = [
                        { value: 1, label: 'Biotech TWG Database' },
                        { value: 2, label: 'Plant Breeders Map' },
                    ];
                }
            }
            if (!this.roles || this.roles.length === 0) {
                try {
                    const res = await axios.get('/api/roles/options');
                    this.roleOptions = res.data?.data || res.data || [];
                } catch (e) {}
            }
        },
        filterRolesByApplication() {
            const allowedRoles = this.applicationRolesMap[String(this.selectedApplication)] || ['Researcher'];
            const allRoles = this.effectiveRoles;
            this.filteredRoles = allRoles.filter(role =>
                allowedRoles.includes(role.label)
            );

            // Default to Researcher role if available
            const researcher = this.filteredRoles.find(r => r.label === 'Researcher');
            if (researcher) {
                this.form.role = researcher.value;
            } else if (this.filteredRoles.length > 0) {
                this.form.role = this.filteredRoles[0].value;
            }
        },
        submit() {
            this.form.post(route('register'), {
                onFinish: () => {
                    this.form?.reset('password', 'password_confirmation');
                },
            });
        },
    },
    watch: {
        selectedApplication(newVal) {
            this.form.account_for = newVal;
            this.filterRolesByApplication();
        },
        effectiveRoles: {
            immediate: true,
            handler() {
                this.filterRolesByApplication();
            },
        },
    },
};
</script>

<template>
    <Head title="Register" />
    <page-layout>
        <hero-image-particles-background class="absolute inset-0" particles-id="login-particles-js" />
        <div class="grid grid-cols-1 w-full bg-transparent">
            <public-page-section class="flex items-center justify-center">
                <AuthenticationCard class="min-h-[90vh] sm:max-w-3xl mx-auto">
                <new-account-progress-view />
                <div class="border-b border-gray-200 pb-2 mb-3">
                    <h1 class="font-bold text-2xl sm:text-3xl text-gray-900 font-display">Registration Form</h1>
                    <p class="text-sm text-gray-600">Fill in all the required(<span class="text-red-600">*</span>) fields.</p>
                </div>
                <form @submit.prevent="submit" class="flex flex-col gap-2 bg-pin-gray rounded-xl border border-gray-200 p-4 sm:p-5">
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-2">
                        <SelectField v-if="effectiveApplications && effectiveApplications.length" id="account_for" label="Account For" v-model="selectedApplication" type="text" required autofocus autocomplete="name" :error="form?.errors.account_for" :options="effectiveApplications" />
                        <SelectField v-if="filteredRoles && filteredRoles.length" id="role" label="Access Level" :disabled="!selectedApplication" v-model="form.role" type="text" required autofocus autocomplete="role" :error="form?.errors.role" :options="filteredRoles" />
                    </div>
                    <div class="grid sm:grid-cols-4 grid-cols-1 gap-2">
                        <TextField id="fname" label="First Name" v-model="form.fname" type="text" required autofocus autocomplete="name" :error="form?.errors.fname" />
                        <TextField id="mname" label="Middle Name" v-model="form.mname" type="text" autofocus autocomplete="name" :error="form?.errors.mname" />
                        <TextField id="lname" label="Surname" v-model="form.lname" type="text" required autofocus autocomplete="name" :error="form?.errors.lname" />
                        <TextField id="suffix" label="Suffix" v-model="form.suffix" type="text" autofocus autocomplete="name" :error="form?.errors.suffix" />
                    </div>
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-2">
                        <TextField id="mobile_no" label="Mobile No." v-model="form.mobile_no" type="text" autofocus autocomplete="name" :error="form?.errors.mobile_no" />
                        <TextField id="email" label="Email" v-model="form.email" type="email" required autocomplete="email" :error="form?.errors.email" />
                    </div>
                    <select-search-field required :api-link="route('api.institutes.options.public')"  :error="form?.errors.affiliation" label="Agency/Institution/Office" v-model="form.affiliation" />
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-2">
                        <TextField id="password" label="Password" v-model="form.password" typeInput="password" required autocomplete="new-password" :error="form?.errors.password" />
                        <TextField id="password_confirmation" label="Confirm Password" v-model="form.password_confirmation" typeInput="password" required autocomplete="new-password" :error="form?.errors.password_confirmation" />
                    </div>
                    <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                        <InputLabel for="terms">
                            <div class="flex items-center">
                                <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

                                <div class="ml-2">
                                    I agree to the <Link :href="route('support.terms-of-use')" class="underline text-sm text-pin-green hover:text-pin-green-dark rounded-md focus-ring">Terms of Service</Link> and <Link :href="route('support.privacy-policy')" class="underline text-sm text-pin-green hover:text-pin-green-dark rounded-md focus-ring">Privacy Policy</Link>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="form?.errors.terms" />
                        </InputLabel>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <Link :href="route('login')" class="underline text-sm text-pin-green hover:text-pin-green-dark rounded-md focus-ring">
                            Already registered?
                        </Link>

                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form?.processing }" :disabled="form?.processing">
                            Register
                        </PrimaryButton>
                    </div>
                </form>
                </AuthenticationCard>
            </public-page-section>
        </div>
    </page-layout>
</template>
