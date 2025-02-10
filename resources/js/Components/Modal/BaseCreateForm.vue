<template>
    <form @submit.prevent="submitForm" @keydown="handleKeydown">
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
            <div class="text-sm text-gray-600">
                <slot name="formFields" />
            </div>
            <div class="flex flex-row justify-between gap-1 text-right">
                <cancel-button @click="close">Cancel</cancel-button>
                <button v-if="form" :disabled="processing" :class="{'cursor-progress opacity-50' : processing}" class="bg-add text-white px-4 py-2 flex item-center rounded-md hover:bg-red-600 active:bg-red-700 duration-200" type="submit">
                    <span v-if="processing">
                        Saving
                    </span>
                    <span v-else>
                        Save
                    </span>
                </button>
            </div>
        </div>
    </form>
</template>
<script>
import CancelButton from "@/Components/CRCMDatatable/Components/CancelButton.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";

export default {
    name: "BaseCreateForm",
    components: {
        LoaderIcon,
        CancelButton,
        CloseIcon,
    },
    props: {
        form: {
            type: Object,
            default: null,
        },
        forceClose: {
            type: Boolean,
            default: false
        },
        processing: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        close() {
            this.$emit('close');
        },
        handleKeydown(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                this.submitForm();
            }
        },
        submitForm() {
            if (this.form)
                this.$emit('submitForm', this.form);
        }
    },
    watch: {
        forceClose() {
            this.close();
        }
    },
};
</script>
