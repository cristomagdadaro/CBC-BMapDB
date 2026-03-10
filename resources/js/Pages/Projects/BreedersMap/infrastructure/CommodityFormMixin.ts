import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity";
import {route} from "ziggy-js";
import Tab from "@/Components/Tab/Tab.vue";
import DateField from "@/Components/Form/DateField.vue";
import FileField from "@/Components/Form/FileField.vue";
import Checkbox from "@/Components/Checkbox.vue";
import {BaseButton} from "@/Components/CRCMDatatable/Components/index.js";
import AddIcon from "@/Components/Icons/AddIcon.vue";

export default {
    components: {AddIcon, BaseButton, FileField, Checkbox, DateField, Tab},
    data() {
        return {
            model: Commodity,
            tabs: [
                {name: 'tab2', label: 'Basic Information', active: true, route: null},
                {name: 'tab1', label: 'Characteristics', active: false, route: null},
                {name: 'tab3', label: 'Additional Information', active: false, route: null},
            ],
            priorityComs: [],
            stress_resilience_options: {
                Biotic: {
                    conditions: ['Disease', 'Pest'],
                    reactions: ['Susceptible', 'Intermediate', 'Tolerant', 'Resistant'],
                },
                Abiotic: {
                    conditions: ['Drought', 'Salinity', 'Submergence', 'Temperature', 'Nutrient Deficiency'],
                    reactions: ['Susceptible', 'Intermediate', 'Tolerant', 'Resistant'],
                },
            },
        };
    },
    computed: {
        isInitialzedBreeeder() {
            return this.$page?.props?.breeder?.id;
        },
    },
    methods: {
        ensureRepeatables() {
            if (!Array.isArray(this.form?.regulations) || this.form.regulations.length === 0) {
                this.form.regulations = [{regulatory_body: null, registration_no: null, registration_date: null}];
            }
            if (!Array.isArray(this.form?.stress_resilience) || this.form.stress_resilience.length === 0) {
                this.form.stress_resilience = [{type: null, stress: null, reaction: null}];
            }
        },
        getScientificName(selectedValueOrLabel) {
            const list = this.priorityComs?.data?.data || [];
            let match = null;

            match = list.find((item) => String(item.value) === String(selectedValueOrLabel));

            if (!match) {
                match = list.find((item) => String(item.label) === String(selectedValueOrLabel));
            }

            this.form.scientific_name = match?.sName || match?.scientific_name || null;
            return this.form.scientific_name;
        },
        onCommodityChange(option) {
            if (option && typeof option === 'object') {
                this.form.scientific_name = option.sName || option.scientific_name || null;
            } else {
                this.getScientificName(this.form.name);
            }
        },
        addRegulation() {
            if (!Array.isArray(this.form.regulations)) {
                this.form.regulations = [];
            }
            this.form.regulations.push({regulatory_body: null, registration_no: null, registration_date: null});
        },
        removeRegulation(index) {
            if (Array.isArray(this.form.regulations)) {
                this.form.regulations.splice(index, 1);
            }
        },
        addStressResilience() {
            if (!Array.isArray(this.form.stress_resilience)) {
                this.form.stress_resilience = [];
            }
            this.form.stress_resilience.push({type: null, stress: null, stress_agent: null, reaction: null});
        },
        removeStressResilience(index) {
            if (Array.isArray(this.form.stress_resilience)) {
                this.form.stress_resilience.splice(index, 1);
            }
        },
    },
    watch: {
        form: {
            handler() {
                this.ensureRepeatables();
            },
            deep: false,
        },
        'form.name': {
            handler(newVal) {
                if (!newVal) {
                    this.form.scientific_name = null;
                    return;
                }
                this.getScientificName(newVal);
            },
            immediate: true,
        },
    },
    async mounted() {
        if (this.$page?.props?.breeder) {
            this.form.breeder_id = this.$page.props.breeder.id;
        }
        this.ensureRepeatables();
        try {
            this.priorityComs = await this.getCustomSelectionOptions(route('api.breedersmap.commodities.priority.public'));
        } catch (e) {
            this.priorityComs = [];
        }
        if (this.form?.name) {
            this.getScientificName(this.form.name);
        }
    },
};

