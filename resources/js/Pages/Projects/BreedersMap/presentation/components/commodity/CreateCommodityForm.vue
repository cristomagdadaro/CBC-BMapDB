<template>
    <form @submit.prevent="$emit('submitForm', form)">
        <div class="px-4 py-2 bg-gray-100 shadow-md">
            <div class="text-lg font-medium text-gray-900 flex justify-between">
                Register a new Commodity
                <button class="text-sm font-medium text-blue-500" @click="$emit('close')">
                    <CloseIcon class="w-7 h-auto hover:scale-110 active:scale-95 duration-100" />
                </button>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field :error="errors? errors['name']:{}" label="Name" v-model="form.name" />
                    <select-search-field :error="errors? errors['breeder_id']:{}" label="Breeder" v-model="form.breeder_id" :options="breeders" />
                    <text-field :error="errors? errors['breeder_id']:{}" label="Breeder" v-model="form.breeder_id" />
                    <text-field :error="errors? errors['scientific_name']:{}" label="Scientific Name" v-model="form.scientific_name" />
                    <text-field :error="errors? errors['variety']:{}" label="Variety" v-model="form.variety" />
                    <text-field :error="errors? errors['accession']:{}" label="Accession" v-model="form.accession" />
                    <text-field :error="errors? errors['germplasm']:{}" label="Germplasm" v-model="form.germplasm" />
                    <text-field type-input="number" :error="errors? errors['population']:{}" label="Population" v-model="form.population" />
                    <text-field :error="errors? errors['maturity_period']:{}" label="Maturity Period" v-model="form.maturity_period" />
                    <text-field type-input="number" :error="errors? errors['yield']:{}" label="Yield" v-model="form.yield" />
                    <text-field :error="errors? errors['description']:{}" label="Description" v-model="form.description" />
                </div>
            </div>
        </div>

        <div class="flex flex-row justify-between gap-1 px-6 py-4 bg-gray-100 text-right">
            <cancel-button @click="$emit('close')">Cancel</cancel-button>
            <button class="bg-add text-white px-4 py-2 rounded-md hover:bg-red-600 active:bg-red-700 duration-200" type="submit">Save</button>
        </div>
    </form>
</template>
<script>
import CancelButton from "@/Components/CRCMDatatable/Components/CancelButton.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import TextField from "@/Components/Form/TextField.vue";
import {useRemember} from "@inertiajs/vue3";
import SelectField from "@/Components/Form/SelectField.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";

export default {
    components: {
        SelectSearchField,
        SelectField,
        CancelButton,
        CloseIcon,
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
            breeders: [
                {value: 1, label: 'Breeder 1'},
                {value: 2, label: 'Breeder 2'},
                {value: 3, label: 'Breeder 3'},
            ],
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
        }
    },
    watch: {
        forceClose() {
            this.$emit('close');
        }
    },
};
</script>
