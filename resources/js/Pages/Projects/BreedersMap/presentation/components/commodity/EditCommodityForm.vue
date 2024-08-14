<template>
    <base-edit-form :form="form" :force-close="forceClose" @resetForm="resetForm">
        <template v-slot:formTitle>
            Update Commodity Information
        </template>
        <template v-slot:formDescription>
            <div class="grid grid-cols-2 text-sm text-gray-600">
                <span>Date created: {{ form.created_at }}</span>
                <span>Last updated: {{ form.updated_at }}</span>
            </div>
        </template>
        <template v-slot:formFields>
            <div class="flex flex-col gap-8">
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field required :show-clear="true" :error="getError('name')" label="Commodity" v-model="form.name" />
                    <text-field required :show-clear="true" :error="getError('scientific_name')" label="Scientific Name" v-model="form.scientific_name" />
                    <select-search-field required :api-link="route('api.breeders.index')" :error="getError('breeder_id')" label="Breeder Name" v-model="form.breeder_id" />
                    <text-field required :show-clear="true" :error="getError('variety')" label="Variety" v-model="form.variety" />
                    <text-field required :show-clear="true" :error="getError('accession')" label="Accession" v-model="form.accession" />
                    <text-field required :show-clear="true" :error="getError('germplasm')" label="Germplasm" v-model="form.germplasm" />
                    <text-field required :show-clear="true" type-input="number" :error="getError('population')" label="Population" v-model="form.population" />
                    <text-field required :show-clear="true" :error="getError('maturity_period')" label="Maturity Period" v-model="form.maturity_period" />
                    <text-field required :show-clear="true" typeInput="number" :error="getError('yield')" label="Yield" v-model="form.yield" />
                    <radio-field :show-clear="true" :error="getError('status')" label="Status" v-model="form.status" :options="[{value: 'active', label: 'Active'}, {value: 'inactive', label: 'Inactive'}]" />
                </div>
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field :show-clear="true" :error="getError('latitude')" label="Latitude" v-model="form.latitude" />
                    <text-field :show-clear="true" :error="getError('longitude')" label="Longitude" v-model="form.longitude" />
                    <text-field :show-clear="true" :error="getError('address')" label="Address" v-model="form.address" />
                    <text-field :show-clear="true" :error="getError('city')" label="City" v-model="form.city" />
                    <text-field :show-clear="true" :error="getError('province')" label="Province" v-model="form.province" />
                    <text-field :show-clear="true" :error="getError('country')" label="Country" v-model="form.country" />
                    <text-field :show-clear="true" :error="getError('postal_code')" label="Postal Code" v-model="form.postal_code" />
                    <text-field :show-clear="true" :error="getError('formatted_address')" label="Formatted Address" v-model="form.formatted_address" />
                    <text-field :show-clear="true" :error="getError('place_id')" label="Place ID" v-model="form.place_id" />
                    <text-field :show-clear="true" :error="getError('image')" label="Image" v-model="form.image" />
                </div>
                <text-field :show-clear="true" typeInput="longtext" :error="getError('description')" label="Description" v-model="form.description" />
            </div>
        </template>
    </base-edit-form>
</template>
<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";

export default {
    mixins: [FormMixin],
    data() {
        return {
            form: {
                name: null,
                breeder_id: null,
                scientific_name: null,
                variety: null,
                accession: null,
                germplasm: null,
                population: null,
                maturity_period: null,
                yield: null,
                description: null,
                image: null,
                latitude: null,
                longitude: null,
                address: null,
                city: null,
                province: null,
                country: null,
                postal_code: null,
                formatted_address: null,
                place_id: null,
                status: null,
            },
        };
    },
};
</script>
