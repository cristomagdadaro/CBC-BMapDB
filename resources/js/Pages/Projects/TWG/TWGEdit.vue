<script setup>
import Form from "@/Components/Form/Form.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
import {Head, useForm } from "@inertiajs/vue3";
import { defineProps, onMounted } from "vue";
import TextField from '@/Components/Form/TextField.vue';
import SelectField from '@/Components/Form/SelectField.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import { EducLevel } from "@/Pages/constants.ts";

const props = defineProps({
    twg_expert: {
        type: Object,
    }
});

const isWideDisplay = false;
const formPersonal = useForm({
    fname: '',
    mname: '',
    lname: '',
    suffix: '',
});

const formAccount = useForm({
    email: '',
    mobile_no: '',
    password: '',
    password_confirmation: '',
});

const formBackground = useForm({
    position: '',
    educ_level: '',
    expertise: '',
});

onMounted(() => {
    formPersonal.fname = props.twg_expert.fname;
    formPersonal.mname = props.twg_expert.mname;
    formPersonal.lname = props.twg_expert.lname;
    formPersonal.suffix = props.twg_expert.suffix;
    formAccount.email = props.twg_expert.email;
    formAccount.mobile_no = props.twg_expert.mobile_no;
    formBackground.position =props.twg_expert.position;
    formBackground.educ_level = props.twg_expert.educ_level;
    formBackground.expertise = props.twg_expert.expertise;
});

const DateFormat = (date) => {
    const d = new Date(date);
    const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
    const mo = new Intl.DateTimeFormat('en', { month: 'short' }).format(d);
    const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
    const h = new Intl.DateTimeFormat('en', { hour: 'numeric', hour12: false }).format(d);
    const m = new Intl.DateTimeFormat('en', { minute: 'numeric' }).format(d);
    //const s = new Intl.DateTimeFormat('en', { second: 'numeric' }).format(d);
    return `${mo}. ${da}, ${ye} - ${h}:${m}`;
};

const savePersonalDetails = () => {
    formPersonal.post(route('twgexpert.personal.update', props.twg_expert.id), {
        errorBag: 'updatePersonalDetails',
        preserveScroll: true,
        onSuccess: (response) => console.log(response),
    });
};

const saveBackgroundDetails = () => {
    formBackground.post(route('twgexpert.background.update', props.twg_expert.id), {
        errorBag: 'updateBackgroundDetails',
        preserveScroll: true,
        onSuccess: (response) => console.log(response),
    });
};

const saveAccountDetails = () => {
    formAccount.post(route('twgexpert.account.update', props.twg_expert.id), {
        errorBag: 'updateAccountDetails',
        preserveScroll: true,
        onSuccess: (response) => console.log(response),
    });
};


</script>
<template>
    <Head title="TWG Database" />
    <PageLayout :is-wide-display="isWideDisplay">
        <div class="flex flex-col gap-4 border-gray-200">
            <div class="mt-5">
                <div class="flex justify-between"><h1 class="font-meduim">Update User Account</h1><h1 class="text-gray-500 text-sm">User ID: {{ twg_expert.id }}</h1></div>
                <div class="flex justify-between"><p class="text-xs text-gray-400">Fields marked with asterisk (<span class="text-red-500">*</span>) are required fields.</p><h1 class="text-gray-500 text-xs">Last modified: {{ DateFormat(twg_expert.updated_at) }}</h1></div>
            </div>
            <Form @submitted="savePersonalDetails">
                <template #title>Personal Details</template>
                <template #form>
                    <div class="grid sm:grid-cols-2 md:grid-cols-4 grid-cols-1 gap-2">
                        <TextField label="First Name" name="first_name" v-model="formPersonal.fname" :required="true" />
                        <TextField label="Middle Name" name="middle_name" v-model="formPersonal.mname" />
                        <TextField label="Last Name" name="last_name" v-model="formPersonal.lname" :required="true" />
                        <TextField label="Suffix" name="suffix" v-model="formPersonal.suffix" />
                    </div>
                </template>
                <template #actions>
                    <ActionMessage :on="formPersonal.recentlySuccessful" class="mr-3">
                        Saved
                    </ActionMessage>

                    <PrimaryButton class="mt-2" :class="{ 'opacity-25': formPersonal.processing }" :disabled="formPersonal.processing">
                        Save
                    </PrimaryButton>
                </template>
            </Form>
            <Form @submitted="saveBackgroundDetails">
                <template #title>Background Details</template>
                <template #form>
                    <div class="grid md:grid-cols-3 grid-cols-1 gap-2">
                        <TextField label="Current Position" name="position" v-model="formBackground.position" :required="true" />
                        <SelectField label="Education Level" name="educ_level" v-model="formBackground.educ_level" :required="true" :options="EducLevel" />
                        <TextField label="Expertise" name="expertise" v-model="formBackground.expertise" :required="true" />
                    </div>
                </template>
                <template #actions>
                    <ActionMessage :on="formBackground.recentlySuccessful" class="mr-3">
                        Saved
                    </ActionMessage>

                    <PrimaryButton class="mt-2" :class="{ 'opacity-25': formBackground.processing }" :disabled="formBackground.processing">
                        Save
                    </PrimaryButton>
                </template>
            </Form>
            <Form @submitted="saveAccountDetails">
                <template #title>Account Details</template>
                <template #form>
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-2">
                        <TextField label="Email" name="email" v-model="formAccount.email" :required="true" />
                        <TextField label="Mobile Number" name="mobile_no" v-model="formAccount.mobile_no" :required="true" />
                        <TextField label="New Password" name="password" v-model="formAccount.password" />
                        <TextField label="Confirm Password" name="password_confirmation" v-model="formAccount.password_confirmation" :required="!!formAccount.password" />
                    </div>
                </template>
                <template #actions>
                    <ActionMessage :on="formAccount.recentlySuccessful" class="mr-3">
                        Saved
                    </ActionMessage>

                    <PrimaryButton class="mt-2" :class="{ 'opacity-25': formAccount.processing }" :disabled="formAccount.processing">
                        Save
                    </PrimaryButton>
                </template>
            </Form>
            <Form>
                <template #title>Projects</template>
                <template #form>
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-2">
                        <div v-for="project in twg_expert.twg_projects" class="text-xs text-justify p-2 bg-white hover:bg-gray-200 hover:rounded-md hover:shadow-md duration-500">
                            <div><b>Title:</b>{{ project.title}}</div>
                            <div><b>Objective:</b>{{ project.objective}}</div>
                            <div><b>Expected Output:</b>{{ project.expected_output}}</div>
                            <div><b>Project Leader:</b>{{ project.project_leader}}</div>
                            <div><b>Funding Agency:</b>{{ project.funding_agency}}</div>
                            <div><b>Duration:</b>{{ project.duration}}</div>
                            <div><b>Status:</b>{{ project.status}}</div>
                        </div>
                    </div>
                </template>
            </Form>
        </div>
    </PageLayout>
</template>
