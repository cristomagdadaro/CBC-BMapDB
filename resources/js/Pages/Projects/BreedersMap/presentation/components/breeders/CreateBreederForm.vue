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
    <base-create-form :form="form" :forceClose="forceClose">
        <template v-slot:formTitle>
            Register a new Breeder
        </template>
        <template v-slot:formFields>
            {{ form }}
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :error="getError('name')" label="Name" v-model="form.name" />
                <text-field :error="getError('phone')" label="Phone Number" v-model="form.phone" />
                <select-search-field required :api-link="route('api.institutes.index.public')"  :error="getError('affiliation')" label="Affiliation" v-model="form.affiliation" />
                <text-field required :error="getError('email')" label="Email" v-model="form.email" />
                <text-field required :error="getError('password')" label="New Password" v-model="form.password" />
                <text-field required :error="getError('password_confirmation')" label="Confirm Password" v-model="form.password_confirmation" />
            </div>
            <div class="mt-1">
                <select-search-field required :api-link="route('api.cities.index.public')"  :error="getError('geolocation')" label="Location" v-model="form.geolocation" />
            </div>
        </template>
    </base-create-form>
</template>
