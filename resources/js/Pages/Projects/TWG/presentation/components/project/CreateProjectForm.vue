<script>
import { ProjectStatus } from "@/Pages/constants.ts";
import FormMixin from "@/Pages/mixins/FormMixin.js";
import Product from "@/Pages/Projects/TWG/domain/Product";

export default {
    mixins: [FormMixin],
    name: "CreateProjectForm",
    computed: {
        ProjectStatus() {
            return ProjectStatus
        }
    },
    data() {
        return {
            model: Product
        };
    },
};
</script>
<template>
   <base-create-form :form="form" :force-close="forceClose">
       <template #formTitle>
           Register a new Project
       </template>
       <template #formFields>
           <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
               <text-field required :error="getError('title')" label="Title" v-model="form.title" />
               <select-search-field required :api-link="route('api.twg.experts.index')"  :error="getError('twg_expert_id')" label="TWG Expert" v-model="form.twg_expert_id" />
               <text-field required :error="getError('objective')" label="Objective" v-model="form.objective" />
               <text-field required :error="getError('expected_output')" label="Expected Output" v-model="form.expected_output" />
               <text-field required :error="getError('project_leader')" label="Project Leader" v-model="form.project_leader" />
               <text-field required :error="getError('funding_agency')" label="Funding Agency" v-model="form.funding_agency" />
               <text-field required :error="getError('duration')" label="Duration" v-model="form.duration" />
               <select-field required :error="getError('status')" label="Status" v-model="form.status" :options="ProjectStatus" />
           </div>
       </template>
    </base-create-form>
</template>
