<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";
import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder";
import FileField from "@/Components/Form/FileField.vue";
import {TWGPages} from "@/Pages/Projects/TWG/components/components";
import MapLocationPicker from "@/Components/Map/MapLocationPicker.vue";

export default {
    computed: {
        TWGPages() {
            return TWGPages
        }
    },
    components: {FileField, MapLocationPicker},
    mixins: [FormMixin],
    name: "EditBreederForm",
    data() {
        return {
            model: Breeder,
        };
    },
    watch: {
        'form.photo': {
            handler: function (newVal) {
                if (!newVal) return;

                const reader = new FileReader();

                reader.onload = (e) => {
                    this.form.photo = e.target.result;
                };

                if (newVal instanceof File) {
                    reader.readAsDataURL(newVal);
                }
            },
            deep: true
        }
    }
};
</script>

<template>
    <base-edit-form :form="form" :force-close="forceClose" @resetForm="resetForm" :processing="processing">
        <template v-slot:formTitle>
            Update Breeder Information
        </template>
        <template v-slot:formDescription>
                Please complete all required fields. Breeders has their own user account, updating a breeder information doesn't directly reflect to its user account.
        </template>
        <template v-slot:formFields>
            <div class="flex flex-col gap-6">
                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3 border-b pb-1">Personal Information</h3>
                    <div class="grid lg:grid-cols-4 sm:grid-cols-2 gap-3 mb-3">
                        <text-field :show-clear="true" required :error="getError('fname')" :title="getTitle('fname')" label="First Name" v-model="form.fname" />
                        <text-field :show-clear="true" :error="getError('mname')" :title="getTitle('mname')" label="Middle Name" v-model="form.mname" />
                        <text-field :show-clear="true" required :error="getError('lname')" :title="getTitle('lname')" label="Surname" v-model="form.lname" />
                        <text-field :show-clear="true" :error="getError('suffix')" :title="getTitle('suffix')" label="Suffix" v-model="form.suffix" />
                    </div>
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-3">
                        <file-field :error="getError('photo')" :title="getTitle('photo')" accept="image/png, image/jpeg, image/jpg, image/heic" label="Profile Photo" v-model="form.photo" />
                    </div>
                </div>

                <!-- Professional Details -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3 border-b pb-1">Professional Details</h3>
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-3 mb-3">
                        <select-search-field required :api-link="route('api.institutes.options.public')" :title="getTitle('affiliation')" :error="getError('affiliation')" label="Affiliation" v-model="form.affiliation" />
                        <select-field required :error="getError('breeder_type')" :title="getTitle('breeder_type')" label="Sector" v-model="form.breeder_type" :options="[{value: 'Public', label: 'Public'}, {value: 'Private', label: 'Private'}]" />
                        <text-field required :error="getError('position')" :title="getTitle('position')" label="Position" v-model="form.position" />
                        <select-field :error="getError('educ_level')" :title="getTitle('educ_level')" label="Education Level" v-model="form.educ_level" :options="TWGPages.educLevelOptions" />
                    </div>
                    <div class="grid grid-cols-1 gap-3">
                        <text-field :error="getError('expertise')" :title="getTitle('expertise')" label="Specialization" v-model="form.expertise" />
                        <text-field :error="getError('research_interest')" :title="getTitle('research_interest')" label="Research Interest" v-model="form.research_interest" />
                    </div>
                </div>

                <!-- Contact & Location -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3 border-b pb-1">Contact & Location</h3>
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-4">
                        <div class="flex flex-col gap-3">
                            <text-field :error="getError('email')" :title="getTitle('email')" label="Email" v-model="form.email" />
                            <text-field :error="getError('mobile_no')" :title="getTitle('mobile_no')" label="Phone Number" v-model="form.mobile_no" />
                        </div>
                        <div class="flex flex-col">
                            <map-location-picker required :api-link="route('api.cities.options.public')" :title="getTitle('geolocation')" :error="getError('geolocation')" label="Location" v-model="form.geolocation" />
                            <span class="text-xs text-gray-500 mt-1">Note: This location will be used to visualize the breeder on the public map.</span>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:timestamps>
            <div v-if="data" class="grid grid-cols-2">
                <span>Date created: {{ data?.created_at }}</span>
                <span>Last updated: {{ data?.updated_at }}</span>
            </div>
        </template>
    </base-edit-form>
</template>
