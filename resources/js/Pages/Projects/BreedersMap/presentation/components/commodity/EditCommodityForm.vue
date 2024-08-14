<template>
    <base-edit-form :form="form" :force-close="forceClose">
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
                    <text-field :show-clear="true" :error="errors? errors['name']:{}" label="Commodity" v-model="form.name" />
                    <text-field :show-clear="true" :error="errors? errors['scientific_name']:{}" label="Scientific Name" v-model="form.scientific_name" />
                    <select-search-field :api-link="route('api.breeders.index')" :error="errors? errors['breeder_id']:{}" label="Breeder Name" v-model="form.breeder_id" />
                    <text-field :show-clear="true" :error="errors? errors['variety']:{}" label="Variety" v-model="form.variety" />
                    <text-field :show-clear="true" :error="errors? errors['accession']:{}" label="Accession" v-model="form.accession" />
                    <text-field :show-clear="true" :error="errors? errors['germplasm']:{}" label="Germplasm" v-model="form.germplasm" />
                    <text-field :show-clear="true" type-input="number" :error="errors? errors['population']:{}" label="Population" v-model="form.population" />
                    <text-field :show-clear="true" :error="errors? errors['maturity_period']:{}" label="Maturity Period" v-model="form.maturity_period" />
                    <text-field :show-clear="true" typeInput="number" :error="errors? errors['yield']:{}" label="Yield" v-model="form.yield" />
                    <radio-field :show-clear="true" :error="errors? errors['status']:{}" label="Status" v-model="form.status" :options="[{value: 'active', label: 'Active'}, {value: 'inactive', label: 'Inactive'}]" />
                </div>
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field :show-clear="true" :error="errors? errors['latitude']:{}" label="Latitude" v-model="form.latitude" />
                    <text-field :show-clear="true" :error="errors? errors['longitude']:{}" label="Longitude" v-model="form.longitude" />
                    <text-field :show-clear="true" :error="errors? errors['address']:{}" label="Address" v-model="form.address" />
                    <text-field :show-clear="true" :error="errors? errors['city']:{}" label="City" v-model="form.city" />
                    <text-field :show-clear="true" :error="errors? errors['province']:{}" label="Province" v-model="form.province" />
                    <text-field :show-clear="true" :error="errors? errors['country']:{}" label="Country" v-model="form.country" />
                    <text-field :show-clear="true" :error="errors? errors['postal_code']:{}" label="Postal Code" v-model="form.postal_code" />
                    <text-field :show-clear="true" :error="errors? errors['formatted_address']:{}" label="Formatted Address" v-model="form.formatted_address" />
                    <text-field :show-clear="true" :error="errors? errors['place_id']:{}" label="Place ID" v-model="form.place_id" />
                    <text-field :show-clear="true" :error="errors? errors['image']:{}" label="Image" v-model="form.image" />
                </div>
                <text-field :show-clear="true" typeInput="longtext" :error="errors? errors['description']:{}" label="Description" v-model="form.description" />
            </div>
        </template>
    </base-edit-form>
</template>
<script>
import TextField from "@/Components/Form/TextField.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import BaseEditForm from "@/Components/Modal/BaseEditForm.vue";
import SelectField from "@/Components/Form/SelectField.vue";
import RadioField from "@/Components/Form/RadioField.vue";

export default {
    components: {
        RadioField,
        SelectField,
        BaseEditForm,
        SelectSearchField,
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
        },
        data: {
            type: Object,
            default: null
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
        resetForm() {
            this.form = Object.assign({}, this.data);
            this.$emit('close');
        }
    },
    watch: {
        forceClose() {
            this.resetForm();
        },
        data() {
            this.form = Object.assign({}, this.data);
        }
    },
};
</script>
