<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";
import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder";
import FileField from "@/Components/Form/FileField.vue";
import {TWGPages} from "@/Pages/Projects/TWG/components/components";

export default {
    computed: {
        TWGPages() {
            return TWGPages
        }
    },
    components: {FileField},
    mixins: [FormMixin],
    name: "CreateBreederForm",
    data() {
        return {
            model: Breeder,
        };
    },
};
</script>

<template>
    <base-create-form :form="form" :forceClose="forceClose" :processing="processing">
        <template v-slot:formTitle>
            Register a new Breeder
        </template>
        <template v-slot:formDescription>
            Please complete all required fields. Once the breeder is successfully registered, an email notification will be sent to the provided email address.
        </template>
        <template v-slot:formFields>
            <div class="grid grid-cols-1 gap-2">
                <div class="grid lg:grid-cols-4 sm:grid-cols-2 gap-2">
                    <text-field required :error="getError('fname')" :title="getTitle('fname')" label="First Name" v-model="form.fname" />
                    <text-field :error="getError('mname')" :title="getTitle('mname')" label="Middle Name" v-model="form.mname" />
                    <text-field required :error="getError('lname')" :title="getTitle('lname')" label="Surname" v-model="form.lname" />
                    <text-field :error="getError('suffix')" :title="getTitle('suffix')" label="Suffix" v-model="form.suffix" />
                </div>
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm gap-2">
                    <text-field :error="getError('mobile_no')" :title="getTitle('mobile_no')" label="Phone Number" v-model="form.mobile_no" />
                    <select-search-field required :api-link="route('api.cities.index.public')" :title="getTitle('geolocation')" :error="getError('geolocation')" label="Location" v-model="form.geolocation" />
                    <select-search-field required :api-link="route('api.institutes.index.public')" :title="getTitle('affiliation')" :error="getError('affiliation')" label="Affiliation" v-model="form.affiliation" />
                    <select-field required :error="getError('breeder_type')" :title="getTitle('breeder_type')" label="Funding Type" v-model="form.breeder_type" :options="[{value: 'Public', label: 'Public'}, {value: 'Private', label: 'Private'}]" />
                    <text-field required :error="getError('position')" :title="getTitle('position')" label="Position" v-model="form.position" />
                    <select-field required :error="getError('educ_level')" :title="getTitle('educ_level')" label="Education Level" v-model="form.educ_level" :options="TWGPages.educLevelOptions" />
                    <text-field required :error="getError('email')" :title="getTitle('email')" label="Email" v-model="form.email" />
                    <file-field :error="getError('photo')" :title="getTitle('photo')" accept="image/png, image/jpeg, image/jpg, image/heic" label="Profile Photo" v-model="form.photo"  />
                </div>
                <div class="grid grid-cols-1 text-sm gap-2">
                    <text-field :error="getError('expertise')" :title="getTitle('expertise')" label="Specialization" v-model="form.expertise" />
                    <text-field :error="getError('research_interest')" :title="getTitle('research_interest')" label="Research Interest" v-model="form.research_interest" />
                </div>
            </div>
        </template>
    </base-create-form>
</template>
