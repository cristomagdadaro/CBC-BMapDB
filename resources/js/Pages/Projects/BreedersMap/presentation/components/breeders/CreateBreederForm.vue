<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";
import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder";

export default {
    mixins: [FormMixin],
    name: "CreateBreederForm",
    data() {
        return {
            model: Breeder,
        };
    },
};
</script>

<template>
    <base-create-form :form="form" :forceClose="forceClose" :processing="processing">
        <template v-slot:formTitle>
            Register a new Breeder
        </template>
        <template v-slot:formDescription>
            Please complete all required fields. Once the breeder is successfully registered, an email notification will be sent to the provided email address.
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :error="getError('fname')" label="First Name" v-model="form.fname" />
                <text-field :error="getError('mname')" label="Middle Name" v-model="form.mname" />
                <text-field required :error="getError('lname')" label="Surname" v-model="form.lname" />
                <text-field :error="getError('suffix')" label="Suffix" v-model="form.suffix" />
                <text-field :error="getError('mobile_no')" label="Phone Number" v-model="form.mobile_no" />
                <select-search-field required :api-link="route('api.institutes.index.public')"  :error="getError('affiliation')" label="Affiliation" v-model="form.affiliation" />
                <select-search-field required :api-link="route('api.cities.index.public')"  :error="getError('geolocation')" label="Location" v-model="form.geolocation" />
                <text-field required :error="getError('email')" label="Email" v-model="form.email" />
            </div>
        </template>
    </base-create-form>
</template>
