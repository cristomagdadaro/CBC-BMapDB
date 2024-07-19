<template>
    <div class="px-4 py-2 bg-gray-100 shadow-md">
        <div class="text-lg font-medium text-gray-900 flex justify-between">
            No form found
            <button class="text-sm font-medium text-blue-500" @click="$emit('close')">
                <CloseIcon class="w-7 h-auto hover:scale-110 active:scale-95 duration-100" />
            </button>
        </div>
    </div>
    <div class="flex flex-row justify-between gap-1 px-6 py-4 bg-gray-100 text-right">
        <cancel-button @click="$emit('close')">Cancel</cancel-button>
    </div>
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
    },
    mounted() {
        if (this.data) {
            // make a copy of the data, so that we can reset the form later without affecting the original data
            this.form = Object.assign({}, this.data);
        }
    }
};
</script>
