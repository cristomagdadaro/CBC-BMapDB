<script>
import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";
import TextField from "@/Components/Form/TextField.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";

export default {
    name: "CreateProductForm",
    components: {SelectSearchField, TextField, BaseCreateForm},
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
                twg_expert_id: null,
                name: null,
                brand: null,
                purpose: null,
                cost: null,
            },
        };
    },
    methods: {
        getError(field) {
            return this.errors[field] ? this.errors[field] : [];
        },
    },
}
</script>

<template>
    <base-create-form :form="form" :force-close="forceClose">
        <template #formTitle>
            Register a new Product
        </template>
        <template #formFields>
            <div class="flex flex-col gap-1">
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field required :error="getError('name')" label="Name" v-model="form.name" />
                    <text-field required :error="getError('brand')" label="Brand" v-model="form.brand" />
                    <text-field required :error="getError('purpose')" label="Purpose" v-model="form.purpose" />
                    <text-field required :error="getError('cost')" label="Cost" v-model="form.cost" />
                </div>
                <select-search-field :api-link="route('api.twg.experts.index')"  :error="errors? errors['twg_expert_id']:{}" label="Expert" v-model="form.twg_expert_id" />
            </div>
        </template>
    </base-create-form>
</template>
