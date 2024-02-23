<script>
import CancelButton from "@/Components/CRCMDatatable/Components/CancelButton.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import TextField from "@/Components/Form/TextField.vue";
import { TWGPages } from "@/Pages/Projects/TWG/components/components.js";
import SelectField from "@/Components/Form/SelectField.vue";

export default {
    name: "EditExpertForm",
    computed: {
        TWGPages() {
            return TWGPages
        }
    },
    components: {
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
        },
        data: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            form: {
                user_id: null,
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
        resetForm() {
            this.form = Object.assign({}, this.data);
        }
    },
    watch: {
        forceClose() {
            this.$emit('close');
        },
        data() {
            this.form = Object.assign({}, this.data);
        }
    }
};
</script>

<template>
    <form @submit.prevent="$emit('submitForm', form)">
        <div class="px-4 py-2 bg-gray-100 shadow-md">
            <div class="text-lg font-medium text-gray-900 flex justify-between">
                Update Expert Information
                <button class="text-sm font-medium text-blue-500" @click="$emit('close')">
                    <CloseIcon class="w-7 h-auto hover:scale-110 active:scale-95 duration-100" />
                </button>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field :error="errors? errors['name']:{}" label="Name" v-model="form.name" />
                    <text-field :error="errors? errors['position']:{}" label="Position" v-model="form.position" />
                    <select-field required :error="errors? errors['educ_level']:{}" label="Education Level" v-model="form.educ_level" :options="TWGPages.educLevelOptions" />
                    <text-field :error="errors? errors['expertise']:{}" label="Expertise" v-model="form.expertise" />
                    <text-field :error="errors? errors['research_interest']:{}" label="Research Interest" v-model="form.research_interest" />
                    <text-field :error="errors? errors['mobile']:{}" label="Mobile" v-model="form.mobile" />
                    <text-field :error="errors? errors['email']:{}" label="Email" v-model="form.email" />
                </div>
            </div>
        </div>
        <div class="flex flex-row justify-between gap-1 px-6 py-4 bg-gray-100 text-right">
            <div class="flex items-center gap-1">
                <cancel-button @click="$emit('close')">Cancel</cancel-button>
                <button class="bg-red-200 text-white px-4 py-2 rounded-md hover:bg-red-500 active:bg-red-600 duration-200" type="button" @click="resetForm">Reset</button>
            </div>
            <button class="bg-edit text-white px-4 py-2 rounded-md hover:bg-edit active:bg-edit duration-200" type="submit">Update</button>
        </div>
    </form>
</template>
