<script>
import TextField from "@/Components/Form/TextField.vue";
import BaseEditForm from "@/Components/Modal/BaseEditForm.vue";

export default {
    name: "EditBreederForm",
    components: {
        BaseEditForm,
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
                user_id: this.$page.props.auth.user.id,
                name: null,
                phone: null,
                email: null,
                agency: null,
                address: null,
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
    }
};
</script>

<template>
    <base-edit-form :form="form" :force-close="forceClose">
        <template v-slot:formTitle>
            Update Breeder Information
        </template>
        <template v-slot:formDescription>
            <div class="grid grid-cols-2 text-sm text-gray-600">
                <span>Date created: {{ form.created_at }}</span>
                <span>Last updated: {{ form.updated_at }}</span>
            </div>
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :show-clear="true" :error="errors? errors['name']:{}" label="Name" v-model="form.name" />
                <text-field :show-clear="true" :error="errors? errors['phone']:{}" label="Phone Number" v-model="form.phone" />
                <text-field required :show-clear="true" :error="errors? errors['email']:{}" label="Email" v-model="form.email" />
                <text-field required :show-clear="true" :error="errors? errors['agency']:{}" label="Agency" v-model="form.agency" />
            </div>
            <div class="mt-1">
                <text-field :show-clear="true" :error="errors? errors['address']:{}" label="Address" v-model="form.address" />
            </div>
        </template>
    </base-edit-form>
</template>
