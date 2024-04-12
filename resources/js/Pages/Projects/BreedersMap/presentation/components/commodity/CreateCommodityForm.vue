<template>
    <base-create-form :form="form" :force-close="forceClose">
        <template v-slot:formTitle>
            Register a new Commodity
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field :error="errors? errors['name']:{}" label="Commodity Name" v-model="form.name" />
                <text-field :error="errors? errors['scientific_name']:{}" label="Scientific Name" v-model="form.scientific_name" />
                <select-search-field :api-link="route('api.breeders.index')"  :error="errors? errors['breeder_id']:{}" label="Breeder Name" v-model="form.breeder_id" />
                <text-field :error="errors? errors['variety']:{}" label="Variety" v-model="form.variety" />
                <text-field :error="errors? errors['accession']:{}" label="Accession" v-model="form.accession" />
                <text-field :error="errors? errors['germplasm']:{}" label="Germplasm" v-model="form.germplasm" />
                <text-field type-input="number" :error="errors? errors['population']:{}" label="Breeding Population" v-model="form.population" />
                <text-field :error="errors? errors['maturity_period']:{}" label="Maturity Period" v-model="form.maturity_period" />
                <text-field type-input="number" :error="errors? errors['yield']:{}" label="Yield" v-model="form.yield" />
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
            },
        };
    },
    methods: {
        close() {
            this.form = {
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
            };

            this.$emit('close');
        },
    },
    watch: {
        forceClose() {
            this.$emit('close');
        }
    },
};
</script>
