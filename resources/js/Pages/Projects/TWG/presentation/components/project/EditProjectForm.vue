<script>
import { ProjectStatus } from "@/Pages/constants.ts";
import FormMixin from "@/Pages/mixins/FormMixin.js";
import Project from "@/Pages/Projects/TWG/domain/Project";
import User from "@/Modules/core/domain/auth/User";

export default {
    mixins: [FormMixin],
    name: "EditProjectForm",
    computed: {
        User() {
            return User
        },
        ProjectStatus() {
            return ProjectStatus
        }
    },
    data() {
        return {
            model: Project
        };
    },
};
</script>

<template>
    <base-edit-form :form="form" :force-close="forceClose" @resetForm="resetForm">
        <template #formTitle>
            Update Project Details
        </template>
        <template v-slot:formDescription>
            <div v-if="data" class="grid grid-cols-2 text-sm text-gray-600">
                <span>Date created: {{ data.created_at }}</span>
                <span>Last updated: {{ data.updated_at }}</span>
            </div>
        </template>
        <template #formFields>
            <div class="flex flex-col gap-1">
                <text-field type-input="longtext" required :error="getError('title')" label="Title" v-model="form.title" />
                <text-field type-input="longtext" required :error="getError('objective')" label="Objective" v-model="form.objective" />
                <text-field type-input="longtext" required :error="getError('expected_output')" label="Expected Output" v-model="form.expected_output" />
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <select-search-field required :api-link="route('api.twg.experts.index')"  :error="getError('project_leader')" label="Project Leader" v-model="form.project_leader" />
                    <text-field required :error="getError('funding_agency')" label="Funding Agency" v-model="form.funding_agency" />
                    <text-field required :error="getError('duration')" label="Duration" v-model="form.duration" />
                    <select-field required :error="getError('status')" label="Status" v-model="form.status" :options="ProjectStatus" />
                </div>
                <select-search-field v-if="isAdmin()" required :api-link="route('api.institutes.index.public')"  :error="getError('institution')" label="Institution / Agency" v-model="form.institution" />
            </div>
        </template>
    </base-edit-form>
</template>
