<script>
import { TWGPages } from "@/Pages/Projects/TWG/components/components.js";
import FormMixin from "@/Pages/mixins/FormMixin.js";
import Expert from "@/Pages/Projects/TWG/domain/Expert";
import User from "../../../../../../Modules/core/domain/auth/User";

export default {
    mixins: [FormMixin],
    name: "CreateExpertForm",
    computed: {
        User() {
            return User
        },
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
    <base-create-form :form="form" :force-close="forceClose">
        <template #formTitle>
            Register a new Expert
        </template>
        <template #formFields>
            <div class="flex flex-col gap-1">
                <text-field required :error="getError('name')" label="Name" v-model="form.name" />
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field required :error="getError('position')" label="Position" v-model="form.position" />
                    <select-field required :error="getError('educ_level')" label="Education Level" v-model="form.educ_level" :options="TWGPages.educLevelOptions" />
                    <text-field required :error="getError('mobile')" label="Mobile" v-model="form.mobile" />
                    <text-field required :error="getError('email')" label="Email" v-model="form.email" />
                </div>
                <text-field required :error="getError('expertise')" label="Expertise" v-model="form.expertise" />
                <text-field required :error="getError('research_interest')" label="Research Interest" v-model="form.research_interest" />
                <select-search-field v-if="isAdmin()" required :api-link="route('api.institutes.index.public')"  :error="getError('institution')" label="Institution / Agency" v-model="form.institution" />
            </div>
        </template>
    </base-create-form>
</template>
