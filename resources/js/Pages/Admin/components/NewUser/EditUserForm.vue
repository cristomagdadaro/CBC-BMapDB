<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import User from "@/Pages/Admin/domain/User";
export default {
    components: {SelectSearchField},
    mixins: [FormMixin],
    name: "EditUserForm",
    data() {
        return {
            model: User
        };
    },
}
</script>

<template>
    <base-edit-form :form="form" :forceClose="forceClose" @resetForm="resetForm">
        <template v-slot:formTitle>
            Update User Details
        </template>
        <template v-slot:formDescription>
            <div v-if="data" class="grid grid-cols-2 text-sm text-gray-600">
                <span>Date created: {{ data.created_at }}</span>
                <span>Last updated: {{ data.updated_at }}</span>
            </div>
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :error="getError('fname')" label="First Name" v-model="form.fname" />
                <text-field :error="getError('mname')" label="Middle Name" v-model="form.mname" />
                <text-field required :error="getError('lname')" label="Surname" v-model="form.lname" />
                <text-field :error="getError('suffix')" label="Suffix" v-model="form.suffix" />
                <text-field :error="getError('mobile_no')" label="Mobile No." v-model="form.mobile_no" />
                <text-field required :error="getError('email')" label="Email" v-model="form.email" />
                <select-search-field required :api-link="route('api.institutes.index.public')"  :error="getError('affiliation')" label="Agency/Institution/Office" v-model="form.affiliation" />
                <select-search-field required :api-link="route('api.applications.index')" :error="getError('account_for')" label="Account For" v-model="form.account_for" />
                <text-field required :error="getError('password')" label="Password" v-model="form.password" />
                <text-field required :error="getError('password_confirmation')" label="Confirm Password" v-model="form.password_confirmation" />
            </div>
        </template>
    </base-edit-form>
</template>

<style scoped>

</style>
