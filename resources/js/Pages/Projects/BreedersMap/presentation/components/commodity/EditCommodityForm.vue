<template>
    <form @submit.prevent="$emit('submitForm', form)">
        <div class="px-4 py-2 bg-gray-100 shadow-md select-none">
            <div class="text-lg font-medium text-gray-900 flex justify-between">
                Update Commodity Information
                <button class="text-sm font-medium text-blue-500" @click="$emit('close')">
                    <CloseIcon class="w-7 h-auto hover:scale-110 active:scale-95 duration-100" />
                </button>
            </div>
            {{ form }}
            <input type="text" name="dsad" id="dsada" v-model="form.breeder_id" placeholder="rfds" />
            <div class="mt-4 text-sm text-gray-600">
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field :error="errors? errors['name']:{}" label="Name" v-model="form.name" />
                    <text-field :error="errors? errors['scientific_name']:{}" label="Scientific Name" v-model="form.scientific_name" />
                    <select-search-field :api-link="route('api.breeders.index')" :error="errors? errors['breeder_id']:{}" label="Breeder Name" v-model="form.breeder_id" />
                    <text-field :error="errors? errors['variety']:{}" label="Variety" v-model="form.variety" />
                    <text-field :error="errors? errors['accession']:{}" label="Accession" v-model="form.accession" />
                    <text-field :error="errors? errors['germplasm']:{}" label="Germplasm" v-model="form.germplasm" />
                    <text-field type-input="number" :error="errors? errors['population']:{}" label="Population" v-model="form.population" />
                    <text-field :error="errors? errors['maturity_period']:{}" label="Maturity Period" v-model="form.maturity_period" />
                    <text-field typeInput="number" :error="errors? errors['yield']:{}" label="Yield" v-model="form.yield" />
                    <text-field typeInput="longtext" :error="errors? errors['description']:{}" label="Description" v-model="form.description" />
                </div>
            </div>
        </div>

        <div class="flex flex-row justify-between gap-1 px-6 py-4 bg-gray-100 text-right">
            <div class="flex items-center gap-1">
                <cancel-button @click="$emit('close')">Cancel</cancel-button>
                <reset-button @click="resetForm">Reset</reset-button>
            </div>
            <update-button>Update</update-button>
        </div>
    </form>
</template>
<script>
import CancelButton from "@/Components/CRCMDatatable/Components/CancelButton.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import TextField from "@/Components/Form/TextField.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import UpdateButton from "@/Components/CRCMDatatable/Components/UpdateButton.vue";
import ResetButton from "@/Components/CRCMDatatable/Components/ResetButton.vue";

export default {
    components: {
        ResetButton,
        UpdateButton,
        SelectSearchField,
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
            },
        };
    },
    methods: {
        resetForm() {
            this.form = Object.assign({}, this.data);
        }
    },
    watch: {
        forceClose() {
            this.$emit('close');
        },
    },
    mounted() {
        if (this.data) {
            // make a copy of the data, so that we can reset the form later without affecting the original data
            this.form = Object.assign({}, this.data);
        }
    }
};
</script>
