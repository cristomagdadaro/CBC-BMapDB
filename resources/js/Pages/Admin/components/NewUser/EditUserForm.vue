<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import User from "@/Pages/Admin/domain/User";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
export default {
    components: {InputError, Checkbox, SelectSearchField},
    mixins: [FormMixin],
    name: "EditUserForm",
    data() {
        return {
            model: User,
            verify: false,
        };
    },
    watch: {
        verify(val) {
            if(!val) {
                this.form.email_verified_at = null;
            } else {
                this.form.email_verified_at = new Date();
            }
        }
    }
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
                <div class="flex flex-col border-0 p-0 bg-transparent">
                    <div class="flex justify-between items-center">
                        <label class="flex text-sm gap-0.5 items-center whitespace-nowrap">
                            Email Verification
                        </label>
                        <InputError :message="getError('email_verified_at') ? getError('email_verified_at')[0] : null" />
                    </div>
                    <div class="flex items-center gap-1 my-auto justify-center">
                        <checkbox :checked="!!form.email_verified_at" v-model="verify"/>
                        <span v-if="!form.email_verified_at" class="text-xs">Check to bypass email verification</span>
                        <span v-if="!!form.email_verified_at" class="text-xs">Uncheck to undo verified email </span>
                    </div>
                </div>
<!--                <select-search-field required :api-link="route('api.applications.index')" :error="getError('account_for')" label="Account For" v-model="form.account_for" />-->
                <text-field required :error="getError('password')" label="New Password" v-model="form.password" />
                <text-field required :error="getError('password_confirmation')" label="Confirm Password" v-model="form.password_confirmation" />
            </div>
        </template>
    </base-edit-form>
</template>

<style scoped>

</style>
