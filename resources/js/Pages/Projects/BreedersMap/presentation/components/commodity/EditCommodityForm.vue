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
<template>
    <base-edit-form :form="form" :force-close="forceClose" @resetForm="resetForm">
        <template v-slot:formTitle>
            Update Commodity Information
        </template>
        <template v-slot:formDescription>
            <div v-if="data" class="grid grid-cols-2 text-sm text-gray-600">
                <span>Date created: {{ data.created_at }}</span>
                <span>Last updated: {{ data.updated_at }}</span>
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
                    <radio-field required :show-clear="true" :error="getError('status')" label="Status" v-model="form.status" :options="[{value: 'active', label: 'Active'}, {value: 'inactive', label: 'Inactive'}]" />
                </div>
                <select-search-field required :api-link="route('api.cities.index.public')"  :error="getError('geolocation')" label="Location" v-model="form.geolocation" />
                <text-field :show-clear="true" typeInput="longtext" :error="getError('description')" label="Description" v-model="form.description" />
            </div>
        </template>
    </base-edit-form>
</template>

