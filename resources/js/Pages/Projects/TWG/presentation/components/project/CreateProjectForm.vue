<template>
    <form @submit.prevent="$emit('submitForm', form)">
        <div class="px-4 py-4 bg-gray-100 shadow-md select-none">
            <div class="text-lg font-medium text-gray-900 flex justify-between">
                Register a new Project
                <button class="text-sm font-medium text-blue-500" @click="$emit('close')">
                    <CloseIcon class="w-7 h-auto hover:scale-110 active:scale-95 duration-100" />
                </button>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field :error="errors? errors['title']:{}" type-input="longtext" label="Title" v-model="form.title" required />
                    <text-field :error="errors? errors['objective']:{}" type-input="longtext" label="Objective" v-model="form.objective" longtext required />
                    <select-search-field :api-link="route('api.twg.experts.index')" :error="errors? errors['twg_expert_id']:{}" label="TWG Expert" v-model="form.twg_expert_id" required />
                    <text-field :error="errors? errors['expected_output']:{}" label="Expected Output" v-model="form.expected_output" required />
                    <text-field :error="errors? errors['project_leader']:{}" label="Project Leader" v-model="form.project_leader" required />
                    <text-field :error="errors? errors['funding_agency']:{}" label="Funding Agency" v-model="form.funding_agency" required />
                    <text-field :error="errors? errors['duration']:{}" label="Duration (years)" v-model="form.duration" required />
                    <select-field :error="errors? errors['status']:{}" label="Status" v-model="form.status" required :options="ProjectStatus" />
                </div>
            </div>
        </div>
        <div class="flex flex-row justify-between gap-1 px-6 py-4 bg-gray-100 text-right">
            <cancel-button @click="$emit('close')">Cancel</cancel-button>
            <button class="bg-add text-white px-4 py-2 rounded-md hover:bg-red-600 active:bg-red-700 duration-200" type="submit">Save</button>
        </div>
    </form>
</template>
<script>
import CancelButton from "@/Components/CRCMDatatable/Components/CancelButton.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import TextField from "@/Components/Form/TextField.vue";
import SelectField from "@/Components/Form/SelectField.vue";
import { ProjectStatus } from "@/Pages/constants.ts";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";

export default {
    name: "CreateProjectForm",
    computed: {
        ProjectStatus() {
            return ProjectStatus
        }
    },
    components: {
        SelectSearchField,
        CancelButton,
        CloseIcon,
        TextField,
        SelectField,
    },
    props: {
        errors: {
            type: Object,
            default: () => ({})
        },
        forceClose: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            form: {
                title: null,
                twg_expert_id: null,
                objective: null,
                expected_output: null,
                project_leader: null,
                funding_agency: null,
                duration: null,
                status: null,
            },
        };
    },
    methods: {
        close() {
            this.form = {
                title: null,
                twg_expert_id: null,
                objective: null,
                expected_output: null,
                project_leader: null,
                funding_agency: null,
                duration: null,
                status: null,
            };

            this.$emit('close');
        }
    },
    watch: {
        forceClose() {
            this.$emit('close');
        },
    },
};
</script>
