<template>
    <form @submit.prevent="submitForm"  @keydown="handleKeydown">
        <div class="flex flex-col gap-5 px-6 py-10 bg-gray-100 shadow-md">
            <div class="flex flex-col">
                <div class="text-lg font-medium text-gray-900 flex justify-between">
                    <slot name="formTitle" />
                    <button class="text-sm font-medium text-blue-500" @click="$emit('close')">
                        <CloseIcon class="w-7 h-auto hover:scale-110 active:scale-95 duration-100" />
                    </button>
                </div>
                <div>
                    <slot name="formDescription" />
                </div>
            </div>
            {{form}}
            <div class="text-sm text-gray-600">
                <slot name="formFields" />
            </div>
            <div class="flex flex-row justify-between gap-1 bg-gray-100 text-right">
                <div class="flex items-center gap-1">
                    <cancel-button @click="$emit('close')">Cancel</cancel-button>
                    <button class="bg-red-200 text-white px-4 py-2 rounded-md hover:bg-red-500 active:bg-red-600 duration-200" type="button" @click="resetForm">Reset</button>
                </div>
                <button class="bg-edit text-white px-4 py-2 rounded-md hover:bg-edit active:bg-edit duration-200" type="submit">Update</button>
            </div>
        </div>
    </form>
</template>

<script>
import CancelButton from "@/Components/CRCMDatatable/Components/CancelButton.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";

export default {
    name: "BaseEditForm",
    components: {
        CancelButton,
        CloseIcon,
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
        form: {
            type: Object,
            default: () => ({})
        },
    },
    methods: {
        resetForm() {
            this.$emit('resetForm');
        },
        close() {
            this.resetForm();
            this.$emit('close');
        },
        submitForm() {
            this.$emit('submitForm', this.form);
        },
        handleKeydown(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                this.submitForm();
            }
        },
    },
    watch: {
        forceClose() {
            this.close();
        },
    },
};
</script>
