<template>
    <form @submit.prevent="$emit('submitForm', form)">
        <div class="px-4 py-2 bg-gray-100 shadow-md">
            <div class="text-lg font-medium text-gray-900 flex justify-between">
                Register a new Expert
                <button class="text-sm font-medium text-blue-500" @click="$emit('close')">
                    <CloseIcon class="w-7 h-auto hover:scale-110 active:scale-95 duration-100" />
                </button>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field required :error="errors? errors['name']:{}" label="Name" v-model="form.name" />
                    <text-field required :error="errors? errors['position']:{}" label="Position" v-model="form.position" />
                    <select-field required :error="errors? errors['educ_level']:{}" label="Education Level" v-model="form.educ_level" :options="TWGPages.educLevelOptions" />
                    <text-field required :error="errors? errors['expertise']:{}" label="Expertise" v-model="form.expertise" />
                    <text-field required :error="errors? errors['research_interest']:{}" label="Research Interest" v-model="form.research_interest" />
                    <text-field required :error="errors? errors['mobile']:{}" label="Mobile" v-model="form.mobile" />
                    <text-field required :error="errors? errors['email']:{}" label="Email" v-model="form.email" />
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
import SelectField from "@/Components/Form/SelectField.vue";
import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
import { TWGPages } from "@/Pages/Projects/TWG/components/components.js";
import CaretDown from "@/Components/Icons/CaretDown.vue";

export default {
    name: "CreateExpertForm",
    computed: {
        TWGPages() {
            return TWGPages
        }
    },
    components: {
        SelectField,
        CaretDown,
        CustomDropdown,
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
            form: {
                user_id: this.$page.props.auth.user.id,
                name: null,
                position: null,
                educ_level: null,
                expertise: null,
                research_interest: null,
                mobile: null,
                email: null,
            },
        };
    },
    methods: {
        close() {
            this.form = {
                name: null,
                position: null,
                educ_level: null,
                expertise: null,
                research_interest: null,
                mobile: null,
                email: null,
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
