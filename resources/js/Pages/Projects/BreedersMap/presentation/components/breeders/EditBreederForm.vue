<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";
import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder";

export default {
    mixins: [FormMixin],
    name: "EditBreederForm",
    data() {
        return {
            model: Breeder,
        };
    },
};
</script>

<template>
    <base-edit-form :form="form" :force-close="forceClose" @resetForm="resetForm">
        <template v-slot:formTitle>
            Update Breeder Information
        </template>
        <template v-slot:formDescription>
            <div v-if="data" class="grid grid-cols-2 text-sm text-gray-600">
                <span>Date created: {{ data.created_at }}</span>
                <span>Last updated: {{ data.updated_at }}</span>
            </div>
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field :show-clear="true" required :error="getError('fname')" label="First Name" v-model="form.fname" />
                <text-field :show-clear="true" :error="getError('mname')" label="Middle Name" v-model="form.mname" />
                <text-field :show-clear="true" required :error="getError('lname')" label="Surname" v-model="form.lname" />
                <text-field :show-clear="true" :error="getError('suffix')" label="Suffix" v-model="form.suffix" />
                <text-field :show-clear="true" :error="getError('mobile_no')" label="Phone Number" v-model="form.mobile_no" />
                <select-search-field required :api-link="route('api.institutes.index.public')"  :error="getError('affiliation')" label="Affiliation" v-model="form.affiliation" />
                <select-search-field required :api-link="route('api.cities.index.public')"  :error="getError('geolocation')" label="Location" v-model="form.geolocation" />
                <text-field required :show-clear="true" :error="getError('email')" label="Email" v-model="form.email" />
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1 mt-1">
                <text-field required :error="getError('password')" label="New Password" v-model="form.password" />
                <text-field :required="!!form.password" :error="getError('password_confirmation')" label="Confirm Password" v-model="form.password_confirmation" />
            </div>
        </template>
    </base-edit-form>
</template>
