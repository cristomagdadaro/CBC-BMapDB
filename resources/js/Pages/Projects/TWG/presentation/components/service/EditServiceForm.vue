<script>
import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";
import TextField from "@/Components/Form/TextField.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";

export default {
    name: "CreateServiceForm",
    components: {SelectSearchField, TextField, BaseCreateForm},
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
                twg_expert_id: null,
                type: null,
                purpose: null,
                direct_beneficiaries: null,
                indirect_beneficiaries: null,
                officer_in_charge: null,
                cost: null,
            },
        };
    },
    methods: {
        getError(field) {
            return this.errors[field] ? this.errors[field] : [];
        },
        resetForm() {
            this.form = Object.assign({}, this.data);
        }
    },
    watch: {
        forceClose() {
            this.resetForm();
            this.$emit('close');
        },
        data() {
            this.form = Object.assign({}, this.data);
        }
    },
}
</script>

<template>
    <base-create-form :form="form" :force-close="forceClose">
        <template #formTitle>
            Register a new Service/Protocol
        </template>
        <template #formFields>
            <div class="flex flex-col gap-1">
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field required :error="getError('name')" label="Type of service" v-model="form.type" />
                    <text-field required :error="getError('purpose')" label="Direct Beneficiaries" v-model="form.direct_beneficiaries" />
                    <select-search-field :api-link="route('api.twg.experts.index')"  :error="errors? errors['twg_expert_id']:{}" label="Expert" v-model="form.twg_expert_id" />
                    <text-field required :error="getError('cost')" label="Indirect Beneficiaries" v-model="form.indirect_beneficiaries" />
                    <text-field required :error="getError('cost')" label="Officer In-charge" v-model="form.officer_in_charge" />
                    <text-field required :error="getError('cost')" label="Cost" v-model="form.cost" />
                </div>
                <text-field type-input="longtext" required :error="getError('brand')" label="Purpose" v-model="form.purpose" />
            </div>
        </template>
    </base-create-form>
</template>

<style scoped>

</style>
