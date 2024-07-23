<script>
import RadioField from "@/Components/Form/RadioField.vue";
import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";
import TextField from "@/Components/Form/TextField.vue";

export default {
    name: "EditUserForm",
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
            Update User Details
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
                <text-field :error="errors? errors['password']:{}" label="New Password" v-model="form.password" />
            </div>
        </template>
    </base-create-form>
</template>

<style scoped>

</style>
