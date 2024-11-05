<script>
import { TWGPages } from "@/Pages/Projects/TWG/components/components.js";
import FormMixin from "@/Pages/mixins/FormMixin.js";
import Expert from "@/Pages/Projects/TWG/domain/Expert";

export default {
    mixins: [FormMixin],
    name: "EditExpertForm",
    computed: {
        TWGPages() {
            return TWGPages
        }
    },
    data() {
        return {
            model: Expert
        };
    },
};
</script>

<template>
    <base-edit-form :data="form" :force-close="forceClose" @resetForm="resetForm">
        <template #formTitle>
            Update Expert Details
        </template>
        <template #formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :error="getError('name')" label="Name" v-model="form.name" />
                <text-field required :error="getError('position')" label="Position" v-model="form.position" />
                <select-field required :error="getError('educ_level')" label="Education Level" v-model="form.educ_level" :options="TWGPages.educLevelOptions" />
                <text-field required :error="getError('expertise')" label="Expertise" v-model="form.expertise" />
                <text-field required :error="getError('research_interest')" label="Research Interest" v-model="form.research_interest" />
                <text-field required :error="getError('mobile')" label="Mobile" v-model="form.mobile" />
                <text-field required :error="getError('email')" label="Email" v-model="form.email" />
            </div>
        </template>
    </base-edit-form>
</template>
