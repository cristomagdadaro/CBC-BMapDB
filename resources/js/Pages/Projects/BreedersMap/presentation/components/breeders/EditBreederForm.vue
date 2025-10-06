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
            <div class="grid grid-cols-1 gap-2">
                <div class="grid lg:grid-cols-4 sm:grid-cols-2 gap-1">
                    <text-field :show-clear="true" required :title="getTitle('fname')" :error="getError('fname')" label="First Name" v-model="form.fname" />
                    <text-field :show-clear="true" :title="getTitle('mname')" :error="getError('mname')" label="Middle Name" v-model="form.mname" />
                    <text-field :show-clear="true" required :title="getTitle('lname')" :error="getError('lname')" label="Surname" v-model="form.lname" />
                    <text-field :show-clear="true" :title="getTitle('suffix')" :error="getError('suffix')" label="Suffix" v-model="form.suffix" />
                </div>
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm gap-2">
                    <text-field :error="getError('mobile_no')" :title="getTitle('mobile_no')" label="Phone Number" v-model="form.mobile_no" />
                    <select-search-field required :api-link="route('api.cities.options.public')" :title="getTitle('geolocation')" :error="getError('geolocation')" label="Location" v-model="form.geolocation" />
                    <select-search-field required :api-link="route('api.institutes.options.public')" :title="getTitle('affiliation')" :error="getError('affiliation')" label="Affiliation" v-model="form.affiliation" />
                    <select-field required :error="getError('breeder_type')" :title="getTitle('breeder_type')" label="Funding Type" v-model="form.breeder_type" :options="[{value: 'Public', label: 'Public'}, {value: 'Private', label: 'Private'}]" />
                    <text-field required :error="getError('position')" :title="getTitle('position')" label="Position" v-model="form.position" />
                    <select-field :error="getError('educ_level')" :title="getTitle('educ_level')" label="Education Level" v-model="form.educ_level" :options="TWGPages.educLevelOptions" />
                    <text-field required :error="getError('email')" :title="getTitle('email')" label="Email" v-model="form.email" />
                    <file-field :error="getError('photo')" :title="getTitle('photo')" accept="image/png, image/jpeg, image/jpg, image/heic" label="Profile Photo" v-model="form.photo"  />
                </div>
                <div class="grid grid-cols-1 text-sm gap-2">
                    <text-field :error="getError('expertise')" :title="getTitle('expertise')" label="Specialization" v-model="form.expertise" />
                    <text-field :error="getError('research_interest')" :title="getTitle('research_interest')" label="Research Interest" v-model="form.research_interest" />
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
