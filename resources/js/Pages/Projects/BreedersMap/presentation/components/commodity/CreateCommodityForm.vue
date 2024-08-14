<template>
    <base-create-form :form="form" :force-close="forceClose">
        <template v-slot:formTitle>
            Register a new Commodity
        </template>
        <template v-slot:formFields>
            <div class="flex flex-col gap-8">
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field required :error="getError('name')" label="Commodity" v-model="form.name" />
                    <text-field required :error="getError('scientific_name')" label="Scientific Name" v-model="form.scientific_name" />
                    <select-search-field required :api-link="route('api.breeders.index')"  :error="getError('breeder_id')" label="Breeder Name" v-model="form.breeder_id" />
                    <text-field required :error="getError('variety')" label="Variety" v-model="form.variety" />
                    <text-field required :error="getError('accession')" label="Accession" v-model="form.accession" />
                    <text-field required :error="getError('germplasm')" label="Germplasm" v-model="form.germplasm" />
                    <text-field required type-input="number" :error="getError('population')" label="Breeding Population" v-model="form.population" />
                    <text-field required :error="getError('maturity_period')" label="Maturity Period" v-model="form.maturity_period" />
                    <text-field required type-input="number" :error="getError('yield')" label="Yield" v-model="form.yield" />
                    <select-field :error="getError('status')" label="Status" v-model="form.status" :options="[{value: 'active', label: 'Active'}, {value: 'inactive', label: 'Inactive'}]" />
                </div>
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field :error="getError('latitude')" label="Latitude" v-model="form.latitude" />
                    <text-field :error="getError('longitude')" label="Longitude" v-model="form.longitude" />
                    <text-field :error="getError('address')" label="Address" v-model="form.address" />
                    <text-field :error="getError('city')" label="City" v-model="form.city" />
                    <text-field :error="getError('province')" label="Province" v-model="form.province" />
                    <text-field :error="getError('country')" label="Country" v-model="form.country" />
                    <text-field :error="getError('postal_code')" label="Postal Code" v-model="form.postal_code" />
                    <text-field :error="getError('formatted_address')" label="Formatted Address" v-model="form.formatted_address" />
                    <text-field :error="getError('place_id')" label="Place ID" v-model="form.place_id" />
                    <text-field :error="getError('image')" label="Image" v-model="form.image" />
                </div>
                <text-field type-input="longtext" :error="getError('description')" label="Other Description" v-model="form.description" />
            </div>
        </template>
    </base-create-form>
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
