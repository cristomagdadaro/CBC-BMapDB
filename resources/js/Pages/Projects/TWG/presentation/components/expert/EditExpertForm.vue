<template>
    <base-edit-form :data="form" :force-close="forceClose">
        <template #formTitle>
            Update Expert Details
        </template>
        <template #formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                {{ form }}
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

<script>
import TextField from "@/Components/Form/TextField.vue";
import { TWGPages } from "@/Pages/Projects/TWG/components/components.js";
import SelectField from "@/Components/Form/SelectField.vue";
import BaseEditForm from "@/Components/Modal/BaseEditForm.vue";
import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";

export default {
    name: "EditExpertForm",
    computed: {
        TWGPages() {
            return TWGPages
        }
    },
    components: {
        BaseCreateForm,
        SelectField,
        TextField,
        BaseEditForm
    },
    props: {
        errors: {
            type: Object,
            default: () => ({})
        },
        forceClose: {
            type: Boolean,
            default: false
        },
        data: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            form: {
                user_id: null,
                name: null,
                position: null,
                educ_level: null,
                expertise: null,
                research_interest: null,
                mobile: null,
                email: null,
            },
        };
    },
    methods: {
        getError(field) {
            return this.errors[field] ? this.errors[field] : [];
        },
    },
    watch: {
        data() {
            this.form = Object.assign({}, this.data);
        }
    }
};
</script>
