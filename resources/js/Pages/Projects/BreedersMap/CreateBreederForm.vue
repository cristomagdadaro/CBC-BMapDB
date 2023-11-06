<template>
    <form @submit.prevent="$emit('submitForm', form)">
        <div class="px-4 py-2 bg-gray-100 shadow-md">
            <div class="text-lg font-medium text-gray-900 flex justify-between">
                Add new Information
                <button class="text-sm font-medium text-blue-500" @click="$emit('close')">
                    <CloseIcon class="w-7 h-auto hover:scale-110 active:scale-95 duration-100" />
                </button>
            </div>

            <div class="mt-4 text-sm text-gray-600">
                <div class="grid grid-cols-2 text-sm text-gray-600 gap-1">
                    <text-field :error="errors? errors['name']:{}" label="Name" v-model="form.name" />
                    <text-field :error="errors? errors['phone']:{}" label="Phone Number" v-model="form.phone" />
                    <text-field :error="errors? errors['email']:{}" label="Email" v-model="form.email" />
                    <text-field :error="errors? errors['agency']:{}" label="Agency" v-model="form.agency" />
                    <text-field :error="errors? errors['address']:{}" label="Address" v-model="form.address" />
                </div>
            </div>
        </div>

        <div class="flex flex-row justify-end gap-1 px-6 py-4 bg-gray-100 text-right">
            <button class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 active:bg-red-700 duration-200" type="submit">Save</button>
            <cancel-button @click="$emit('close')">Cancel</cancel-button>
        </div>
    </form>
</template>
<script>
import CancelButton from "@/Components/CRCMDatatable/Components/CancelButton.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import TextField from "@/Components/Form/TextField.vue";

export default {
    components: {
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
                name: null,
                phone: null,
                email: null,
                agency: null,
                address: null,
            },
        };
    },
    watch: {
        forceClose() {
            this.$emit('close');
        }
    }
};
</script>
