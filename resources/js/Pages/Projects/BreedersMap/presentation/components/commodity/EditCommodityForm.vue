<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity";
import Tab from "@/Components/Tab/Tab.vue";
import DateField from "@/Components/Form/DateField.vue";

export default {
    components: {DateField, Tab},
    mixins: [FormMixin],
    data() {
        return {
            model: Commodity,
            tabs: [
                {
                    name: "tab1",
                    label: "Basic Information",
                    active: true,
                    route: null,
                },
                {
                    name: "tab2",
                    label: "Characteristics",
                    active: false,
                    route: null,
                },
                {
                    name: "tab3",
                    label: "Additional Information",
                    active: false,
                    route: null,
                },
            ],
            priorityComs: [],
        };
    },
    methods: {
        getScientificName(comms) {
            this.form.scientific_name = this.priorityComs?.data?.find(item => item.label === comms)?.sName;
        }
    },
    computed: {
        isInitialzedBreeeder(){
            return this.$page.props?.breeder?.id;
        }
    },
    watch: {
        'form.name' (newVal){
            this.getScientificName(newVal);
        }
    },
    async mounted() {
        if (this.$page.props.breeder)
            this.form.breeder_id = this.$page.props.breeder.id;

        this.priorityComs = await this.getCustomSelectionOptions(route('api.breedersmap.commodities.priority.public'))
    }
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
            <tab :tabs="tabs">
                <template v-slot:tab1>
                    <div class="flex flex-col gap-8">
                        {{form.scientific_name}}
                        <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                            <select-field required :error="getError('name')" label="Commodity" v-model="form.name" :options="priorityComs?.data" />
                            <text-field required :show-clear="false" :error="getError('scientific_name')" label="Scientific Name" v-model="form.scientific_name" />
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
                <template v-slot:tab2>
                    <div class="flex flex-col gap-8">
                        <div class="flex flex-col text-gray-600 gap-5">
                            <div>
                                <label class="flex text-normal font-semibold gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    Plant Characteristics
                                </label>
                                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                                    <text-field
                                        type-input="number"
                                        :error="getError('weight')"
                                        label="Weight"
                                        v-model="form.weight"
                                    />
                                    <text-field
                                        type-input="number"
                                        :error="getError('length')"
                                        label="Length"
                                        v-model="form.length"
                                    />
                                    <text-field
                                        type-input="number"
                                        :error="getError('width')"
                                        label="Width"
                                        v-model="form.width"
                                    />
                                    <text-field
                                        :error="getError('shape')"
                                        label="Shape"
                                        v-model="form.shape"
                                    />
                                </div>
                            </div>

                            <!-- Fruit Characteristics -->
                            <div>
                                <label class="flex text-normal font-semibold gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    Fruit Characteristics
                                </label>
                                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                                    <text-field
                                        :error="getError('skin_color')"
                                        label="Skin Color"
                                        v-model="form.skin_color"
                                    />
                                    <text-field
                                        :error="getError('skin_texture')"
                                        label="Skin Texture"
                                        v-model="form.skin_texture"
                                    />
                                    <text-field
                                        :error="getError('flesh_color')"
                                        label="Flesh Color"
                                        v-model="form.flesh_color"
                                    />
                                    <text-field
                                        :error="getError('flesh_texture')"
                                        label="Flesh Texture"
                                        v-model="form.flesh_texture"
                                    />
                                    <text-field
                                        :error="getError('flesh_flavor')"
                                        label="Flesh Flavor"
                                        v-model="form.flesh_flavor"
                                    />
                                    <text-field
                                        :error="getError('aroma')"
                                        label="Aroma"
                                        v-model="form.aroma"
                                    />
                                </div>
                            </div>

                            <!-- Root Characteristics -->
                            <div>
                                <label class="flex text-normal font-semibold gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    Root Characteristics
                                </label>
                                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                                    <text-field
                                        :error="getError('root_flesh_color')"
                                        label="Root Flesh Color"
                                        v-model="form.root_flesh_color"
                                    />
                                    <text-field
                                        :error="getError('root_cortex_color')"
                                        label="Root Cortex Color"
                                        v-model="form.root_cortex_color"
                                    />
                                    <text-field
                                        :error="getError('root_skin_color')"
                                        label="Root Skin Color"
                                        v-model="form.root_skin_color"
                                    />
                                    <text-field
                                        :error="getError('root_shape')"
                                        label="Root Shape"
                                        v-model="form.root_shape"
                                    />
                                </div>
                            </div>

                            <!-- Tuber Characteristics -->
                            <div>
                                <label class="flex text-normal font-semibold gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    Tuber Characteristics
                                </label>
                                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                                    <text-field
                                        :error="getError('tuber_flesh_color')"
                                        label="Tuber Flesh Color"
                                        v-model="form.tuber_flesh_color"
                                    />
                                    <text-field
                                        :error="getError('tuber_cortex_color')"
                                        label="Tuber Cortex Color"
                                        v-model="form.tuber_cortex_color"
                                    />
                                    <text-field
                                        :error="getError('tuber_skin_color')"
                                        label="Tuber Skin Color"
                                        v-model="form.tuber_skin_color"
                                    />
                                    <text-field
                                        :error="getError('tuber_shape')"
                                        label="Tuber Shape"
                                        v-model="form.tuber_shape"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-slot:tab3>
                    <div class="flex flex-col gap-8">
                        <div class="flex flex-col text-gray-600 gap-5">
                            <div>
                                <label class="flex text-normal font-semibold gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    NSIC Registration
                                </label>
                                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                                    <text-field
                                        :error="getError('nsic_registration')"
                                        label="NSIC Registration"
                                        v-model="form.nsic_registration"
                                    />
                                    <text-field
                                        :error="getError('nsic_registration_number')"
                                        label="NSIC Registration Number"
                                        v-model="form.nsic_registration_number"
                                    />
                                    <text-field
                                        :error="getError('nsic_year_approved')"
                                        label="NSIC Year Approved"
                                        v-model="form.nsic_year_approved"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="flex text-normal font-semibold gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    Plant Variety Protection
                                </label>
                                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                                    <text-field
                                        :error="getError('pvp_certificate_number')"
                                        label="PVP Certificate Number"
                                        v-model="form.pvp_certificate_number"
                                    />
                                    <text-field
                                        :error="getError('pvp_registration_year')"
                                        label="PVP Registration Year"
                                        v-model="form.pvp_registration_year"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="flex text-normal font-semibold gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    GM Regulatory Approval: NCBP
                                </label>
                                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                                    <text-field :error="getError('ncbp_project_type')" label="NCBP Project Type"
                                                v-model="form.ncbp_project_type"/>
                                    <text-field :error="getError('ncbp_status')" label="NCBP Status"
                                                v-model="form.ncbp_status"/>
                                    <text-field :error="getError('ncbp_supervising_ibc')" label="NCBP Supervising IBC"
                                                v-model="form.ncbp_supervising_ibc"/>
                                    <text-field :error="getError('ncbp_project_leaders')" label="NCBP Project Leaders"
                                                v-model="form.ncbp_project_leaders"/>
                                    <date-field :error="getError('ncbp_date_approval')" label="NCBP Date of Approval"
                                                v-model="form.ncbp_date_approval"/>
                                    <date-field :error="getError('ncbp_date_completion')" label="NCBP Date of Completion"
                                                v-model="form.ncbp_date_completion"/>
                                </div>
                            </div>

                            <div>
                                <label class="flex text-normal font-semibold gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    GM Regulatory Approval: AO8
                                </label>
                                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                                    <text-field :error="getError('ao8_transformation_event')" label="AO8 Transformation Event"
                                                v-model="form.ao8_transformation_event"/>
                                    <text-field :error="getError('ao8_technology_developer')" label="AO8 Technology Developer"
                                                v-model="form.ao8_technology_developer"/>
                                    <text-field :error="getError('ao8_direct_use_status')" label="AO8 Direct Use Status"
                                                v-model="form.ao8_direct_use_status"/>
                                    <date-field :error="getError('ao8_direct_use_date_applied')"
                                                label="AO8 Direct Use Date Applied" v-model="form.ao8_direct_use_date_applied"/>
                                    <date-field :error="getError('ao8_direct_use_date_approved')"
                                                label="AO8 Direct Use Date Approved"
                                                v-model="form.ao8_direct_use_date_approved"/>
                                    <text-field :error="getError('ao8_field_trial_status')" label="AO8 Field Trial Status"
                                                v-model="form.ao8_field_trial_status"/>
                                    <date-field :error="getError('ao8_field_trial_date_applied')"
                                                label="AO8 Field Trial Date Applied"
                                                v-model="form.ao8_field_trial_date_applied"/>
                                    <date-field :error="getError('ao8_field_trial_date_approved')"
                                                label="AO8 Field Trial Date Approved"
                                                v-model="form.ao8_field_trial_date_approved"/>
                                    <text-field :error="getError('ao8_propagation_status')" label="AO8 Propagation Status"
                                                v-model="form.ao8_propagation_status"/>
                                    <date-field :error="getError('ao8_propagation_date_applied')"
                                                label="AO8 Propagation Date Applied"
                                                v-model="form.ao8_propagation_date_applied"/>
                                    <date-field :error="getError('ao8_propagation_date_approved')"
                                                label="AO8 Propagation Date Approved"
                                                v-model="form.ao8_propagation_date_approved"/>
                                </div>
                            </div>

                            <!--  -->
                            <div>
                                <label class="flex text-normal font-semibold gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    GM Regulatory Approval: JDC 2016
                                </label>
                                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                                    <text-field :error="getError('jdc_2016_transformation_event')"
                                                label="JDC 2016 Transformation Event"
                                                v-model="form.jdc_2016_transformation_event"/>
                                    <text-field :error="getError('jdc_2016_technology_developer')"
                                                label="JDC 2016 Technology Developer"
                                                v-model="form.jdc_2016_technology_developer"/>
                                    <text-field :error="getError('jdc_2016_direct_use_status')"
                                                label="JDC 2016 Direct Use Status" v-model="form.jdc_2016_direct_use_status"/>
                                    <date-field :error="getError('jdc_2016_direct_use_date_applied')"
                                                label="JDC 2016 Direct Use Date Applied"
                                                v-model="form.jdc_2016_direct_use_date_applied"/>
                                    <date-field :error="getError('jdc_2016_direct_use_date_approved')"
                                                label="JDC 2016 Direct Use Date Approved"
                                                v-model="form.jdc_2016_direct_use_date_approved"/>
                                    <date-field :error="getError('jdc_2016_ffp_status')" label="JDC 2016 FFP Status"
                                                v-model="form.jdc_2016_ffp_status"/>
                                    <date-field :error="getError('jdc_2016_ffp_date_applied')" label="JDC 2016 FFP Date Applied"
                                                v-model="form.jdc_2016_ffp_date_applied"/>
                                    <date-field :error="getError('jdc_2016_ffp_date_approved')"
                                                label="JDC 2016 FFP Date Approved" v-model="form.jdc_2016_ffp_date_approved"/>
                                    <date-field :error="getError('jdc_2016_field_trial_status')"
                                                label="JDC 2016 Field Trial Status" v-model="form.jdc_2016_field_trial_status"/>
                                    <date-field :error="getError('jdc_2016_field_trial_date_applied')"
                                                label="JDC 2016 Field Trial Date Applied"
                                                v-model="form.jdc_2016_field_trial_date_applied"/>
                                    <date-field :error="getError('jdc_2016_field_trial_date_approved')"
                                                label="JDC 2016 Field Trial Date Approved"
                                                v-model="form.jdc_2016_field_trial_date_approved"/>
                                    <date-field :error="getError('jdc_2016_propagation_status')"
                                                label="JDC 2016 Propagation Status" v-model="form.jdc_2016_propagation_status"/>
                                    <date-field :error="getError('jdc_2016_propagation_date_applied')"
                                                label="JDC 2016 Propagation Date Applied"
                                                v-model="form.jdc_2016_propagation_date_applied"/>
                                    <date-field :error="getError('jdc_2016_propagation_date_approved')"
                                                label="JDC 2016 Propagation Date Approved"
                                                v-model="form.jdc_2016_propagation_date_approved"/>
                                    <date-field :error="getError('jdc_2016_renewal_propagation_status')"
                                                label="JDC 2016 Renewal Propagation Status"
                                                v-model="form.jdc_2016_renewal_propagation_status"/>
                                    <date-field :error="getError('jdc_2016_renewal_propagation_date_applied')"
                                                label="JDC 2016 Renewal Propagation Date Applied"
                                                v-model="form.jdc_2016_renewal_propagation_date_applied"/>
                                    <date-field :error="getError('jdc_2016_renewal_propagation_date_approved')"
                                                label="JDC 2016 Renewal Propagation Date Approved"
                                                v-model="form.jdc_2016_renewal_propagation_date_approved"/>
                                    <date-field :error="getError('jdc_2016_deregulation_status')"
                                                label="JDC 2016 Deregulation Status"
                                                v-model="form.jdc_2016_deregulation_status"/>
                                    <date-field :error="getError('jdc_2016_deregulation_date_applied')"
                                                label="JDC 2016 Deregulation Date Applied"
                                                v-model="form.jdc_2016_deregulation_date_applied"/>
                                    <date-field :error="getError('jdc_2016_deregulation_date_approved')"
                                                label="JDC 2016 Deregulation Date Approved"
                                                v-model="form.jdc_2016_deregulation_date_approved"/>
                                </div>
                            </div>

                            <!--  -->
                            <div>
                                <label class="flex text-normal font-semibold gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    GM Regulatory Approval: JDC 2021
                                </label>
                                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                                    <text-field :error="getError('jdc_2021_transformation_event')"
                                                label="JDC 2021 Transformation Event"
                                                v-model="form.jdc_2021_transformation_event"/>
                                    <text-field :error="getError('jdc_2021_technology_developer')"
                                                label="JDC 2021 Technology Developer"
                                                v-model="form.jdc_2021_technology_developer"/>
                                    <text-field :error="getError('jdc_2021_direct_use_status')"
                                                label="JDC 2021 Direct Use Status" v-model="form.jdc_2021_direct_use_status"/>
                                    <date-field :error="getError('jdc_2021_direct_use_date_applied')"
                                                label="JDC 2021 Direct Use Date Applied"
                                                v-model="form.jdc_2021_direct_use_date_applied"/>
                                    <date-field :error="getError('jdc_2021_direct_use_date_approved')"
                                                label="JDC 2021 Direct Use Date Approved"
                                                v-model="form.jdc_2021_direct_use_date_approved"/>
                                    <date-field :error="getError('jdc_2021_field_trial_status')"
                                                label="JDC 2021 Field Trial Status" v-model="form.jdc_2021_field_trial_status"/>
                                    <date-field :error="getError('jdc_2021_field_trial_date_applied')"
                                                label="JDC 2021 Field Trial Date Applied"
                                                v-model="form.jdc_2021_field_trial_date_applied"/>
                                    <date-field :error="getError('jdc_2021_field_trial_date_approved')"
                                                label="JDC 2021 Field Trial Date Approved"
                                                v-model="form.jdc_2021_field_trial_date_approved"/>
                                    <date-field :error="getError('jdc_2021_propagation_status')"
                                                label="JDC 2021 Propagation Status" v-model="form.jdc_2021_propagation_status"/>
                                    <date-field :error="getError('jdc_2021_propagation_date_applied')"
                                                label="JDC 2021 Propagation Date Applied"
                                                v-model="form.jdc_2021_propagation_date_applied"/>
                                    <date-field :error="getError('jdc_2021_propagation_date_approved')"
                                                label="JDC 2021 Propagation Date Approved"
                                                v-model="form.jdc_2021_propagation_date_approved"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </tab>
        </template>
    </base-edit-form>
</template>

