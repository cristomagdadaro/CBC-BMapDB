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
            {{ form }}
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :show-clear="true" :error="getError('name')" label="Name" v-model="form.name" />
                <text-field :show-clear="true" :error="getError('phone')" label="Phone Number" v-model="form.phone" />
                <select-search-field required :api-link="route('api.institutes.index.public')"  :error="getError('affiliation')" label="Affiliation" v-model="form.affiliation" />
                <text-field required :show-clear="true" :error="getError('email')" label="Email" v-model="form.email" />
                <text-field :error="getError('password')" label="Change Password" v-model="form.password" />
                <text-field :required="!!form.password" :error="getError('password_confirmation')" label="Confirm Password" v-model="form.password_confirmation" />
            </div>
            <div class="mt-1">
                <select-search-field required :api-link="route('api.cities.index.public')"  :error="getError('geolocation')" label="Location" v-model="form.geolocation" />
            </div>
        </template>
    </base-edit-form>
</template>
