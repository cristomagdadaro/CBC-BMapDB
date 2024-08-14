<template>
    <base-create-form :form="form" :force-close="forceClose">
        <template v-slot:formTitle>
            Register a new Commodity
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field :error="errors? errors['name']:{}" label="Commodity" v-model="form.name" />
                <text-field :error="errors? errors['scientific_name']:{}" label="Scientific Name" v-model="form.scientific_name" />
                <select-search-field :api-link="route('api.breeders.index')"  :error="errors? errors['breeder_id']:{}" label="Breeder Name" v-model="form.breeder_id" />
                <text-field :error="errors? errors['variety']:{}" label="Variety" v-model="form.variety" />
                <text-field :error="errors? errors['accession']:{}" label="Accession" v-model="form.accession" />
                <text-field :error="errors? errors['germplasm']:{}" label="Germplasm" v-model="form.germplasm" />
                <text-field type-input="number" :error="errors? errors['population']:{}" label="Breeding Population" v-model="form.population" />
                <text-field :error="errors? errors['maturity_period']:{}" label="Maturity Period" v-model="form.maturity_period" />
                <text-field type-input="number" :error="errors? errors['yield']:{}" label="Yield" v-model="form.yield" />
                <text-field :error="errors? errors['latitude']:{}" label="Latitude" v-model="form.latitude" />
                <text-field :error="errors? errors['longitude']:{}" label="Longitude" v-model="form.longitude" />
                <text-field :error="errors? errors['address']:{}" label="Address" v-model="form.address" />
                <text-field :error="errors? errors['city']:{}" label="City" v-model="form.city" />
                <text-field :error="errors? errors['province']:{}" label="Province" v-model="form.province" />
                <text-field :error="errors? errors['country']:{}" label="Country" v-model="form.country" />
                <text-field :error="errors? errors['postal_code']:{}" label="Postal Code" v-model="form.postal_code" />
                <text-field :error="errors? errors['formatted_address']:{}" label="Formatted Address" v-model="form.formatted_address" />
                <text-field :error="errors? errors['place_id']:{}" label="Place ID" v-model="form.place_id" />
                <select-field :error="errors? errors['status']:{}" label="Status" v-model="form.status" :options="[{value: 'active', label: 'Active'}, {value: 'inactive', label: 'Inactive'}]" />
                <text-field :error="errors? errors['image']:{}" label="Image" v-model="form.image" />
                <text-field type-input="longtext" :error="errors? errors['description']:{}" label="Other Description" v-model="form.description" />
            </div>
        </template>
    </base-create-form>
</template>
<script>
import TextField from "@/Components/Form/TextField.vue";
import SelectField from "@/Components/Form/SelectField.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import BreedersMapApiService from "@/Pages/Projects/BreedersMap/intrastructure/BreedersMapApiService.js";
import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";

export default {
    components: {
        BaseCreateForm,
        SelectSearchField,
        SelectField,
        TextField,
    },
    props: {
        errors: {
            type: Object,
            default: () => ({})
        },
        forceClose: {
            type: Boolean,
            default: false
        }
    },
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
    methods: {
        close() {
            this.form = Object.assign({}, this.data);
        },
    },
    watch: {
        forceClose() {
            this.$emit('close');
        }
    },
};
</script>
