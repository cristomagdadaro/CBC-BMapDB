<script>
import RadioField from "@/Components/Form/RadioField.vue";
import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";
import TextField from "@/Components/Form/TextField.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";

export default {
    name: "CreateUserForm",
    components: {
        SelectSearchField,
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
        }
    },
    data() {
        return {
            form: {
                fname: null,
                mname: null,
                lname: null,
                suffix: null,
                mobile_no: null,
                email: null,
                affiliation: null,
                account_for: null,
                password: null,
                password_confirmation: null,
                terms: null,
            },
        };
    },
    methods: {
        close() {
            this.form = {
                fname: null,
                mname: null,
                lname: null,
                suffix: null,
                mobile_no: null,
                email: null,
                affiliation: null,
                account_for: null,
                password: null,
                password_confirmation: null,
                terms: null,
            };

            this.$emit('close');
        }
    },
    watch: {
        forceClose() {
            this.$emit('close');
        },
    },
}
</script>

<template>
    <base-create-form :form="form" :forceClose="forceClose">
        <template v-slot:formTitle>
            Create new User
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :error="errors? errors['fname']:{}" label="First Name" v-model="form.fname" />
                <text-field :error="errors? errors['mname']:{}" label="Middle Name" v-model="form.mname" />
                <text-field required :error="errors? errors['lname']:{}" label="Surname" v-model="form.lname" />
                <text-field :error="errors? errors['suffix']:{}" label="Suffix" v-model="form.suffix" />
                <text-field :error="errors? errors['mobile_no']:{}" label="Mobile No." v-model="form.mobile_no" />
                <text-field required :error="errors? errors['email']:{}" label="Email" v-model="form.email" />
                <text-field required :error="errors? errors['affiliation']:{}" label="Agency/Institution/Office" v-model="form.affiliation" />
                <select-search-field :api-link="route('api.applications.index')" :error="errors? errors['account_for']:{}" label="Account For" v-model="form.account_for" required />
                <text-field required :error="errors? errors['password']:{}" label="Password" v-model="form.password" />
                <text-field required :error="errors? errors['password_confirmation']:{}" label="Confirm Password" v-model="form.password_confirmation" />
            </div>
        </template>
    </base-create-form>
</template>

<style scoped>

</style>
