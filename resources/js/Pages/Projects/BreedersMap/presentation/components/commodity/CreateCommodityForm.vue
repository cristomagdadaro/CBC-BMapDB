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
                    <select-field required :error="getError('status')" label="Status" v-model="form.status" :options="[{value: 'active', label: 'Active'}, {value: 'inactive', label: 'Inactive'}]" />
                </div>
                <select-search-field required :api-link="route('api.cities.index.public')"  :error="getError('geolocation')" label="Location" v-model="form.geolocation" />
                <text-field type-input="longtext" :error="getError('description')" label="Other Description" v-model="form.description" />
            </div>
        </template>
    </base-create-form>
</template>
<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity";

export default {
    mixins: [FormMixin],
    data() {
        return {
            model: Commodity
        };
    },
};
</script>
