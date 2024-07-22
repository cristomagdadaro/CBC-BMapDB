<script>
import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";
import TextField from "@/Components/Form/TextField.vue";
import RadioField from "@/Components/Form/RadioField.vue";

export default {
    name: "EditApplicationForm",
    components: {
        RadioField,
        BaseCreateForm,
        TextField,
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
                name: null,
                description: null,
                url: null,
                icon: null,
                status: null,
            },
        };
    },
    methods: {
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
    <base-create-form :form="form" :forceClose="forceClose">
        <template v-slot:formTitle>
            Update Details for Database Application
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :error="errors? errors['name']:{}" label="Name" v-model="form.name" />
                <text-field required :error="errors? errors['description']:{}" label="Description" v-model="form.description" />
                <text-field :error="errors? errors['url']:{}" label="URL" v-model="form.url" />
                <text-field :error="errors? errors['icon']:{}" label="Icon" v-model="form.icon" />
                <radio-field required :error="errors? errors['status']:{}" label="Status" v-model="form.status" :options="[{label: 'Active', value: 'true'}, {label: 'Inactive', value: 'false'}]" />
            </div>
        </template>
    </base-create-form>
</template>
