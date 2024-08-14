<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";

export default {
    mixins: [FormMixin],
    name: "EditUserForm",
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
}
</script>

<template>
    <base-edit-form :form="form" :forceClose="forceClose" @resetForm="resetForm">
        <template v-slot:formTitle>
            Update User Details
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :error="getError('fname')" label="First Name" v-model="form.fname" />
                <text-field :error="getError('mname')" label="Middle Name" v-model="form.mname" />
                <text-field required :error="getError('lname')" label="Surname" v-model="form.lname" />
                <text-field :error="getError('suffix')" label="Suffix" v-model="form.suffix" />
                <text-field :error="getError('mobile_no')" label="Mobile No." v-model="form.mobile_no" />
                <text-field required :error="getError('email')" label="Email" v-model="form.email" />
                <text-field required :error="getError('affiliation')" label="Agency/Institution/Office" v-model="form.affiliation" />
                <select-search-field :api-link="route('api.applications.index')" :error="getError('account_for')" label="Account For" v-model="form.account_for" required />
                <text-field required :error="getError('password')" label="Password" v-model="form.password" />
            </div>
        </template>
    </base-edit-form>
</template>

<style scoped>

</style>
