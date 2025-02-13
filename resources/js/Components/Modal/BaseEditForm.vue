<template>
    <form @submit.prevent="submitForm"  @keydown="handleKeydown">
        <div class="flex flex-col gap-3 px-6 py-10 bg-gray-100 shadow-md">
            <div class="flex flex-col">
                <div class="text-xl font-semibold text-gray-900 flex justify-between py-1 border-b border-gray-400">
                    <slot name="formTitle" />
                    <button class="text-sm font-medium text-blue-500" @click="$emit('close')">
                        <CloseIcon class="w-7 h-auto hover:scale-110 active:scale-95 duration-100" />
                    </button>
                </div>
                <div class="text-sm text-gray-700 leading-tight p-2">
                    <slot name="formDescription" />
                </div>
            </div>
            <div class="text-sm text-gray-600">
                <slot name="formFields" />
            </div>
            <div class="text-sm text-gray-700 leading-tight p-2">
                <slot name="timestamps" />
            </div>
            <div class="flex flex-row justify-between gap-1 bg-gray-100 text-right">
                <div class="flex items-center gap-2">
                    <cancel-button @click="$emit('close')">Cancel</cancel-button>
                    <button class="bg-red-200 text-white px-4 py-2 rounded-md hover:bg-red-500 active:bg-red-600 duration-200" type="button" @click="resetForm">Reset</button>
                </div>
                <button :disabled="processing" :class="{'cursor-progress opacity-50' : processing}" class="bg-edit text-white px-4 py-2 rounded-md hover:bg-edit active:bg-edit duration-200" type="submit">
                        <span v-if="processing">
                            Updating
                        </span>
                    <span v-else>
                            Update
                        </span>
                </button>
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
        processing: {
            type: Boolean,
            default: false
        }
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
    beforeDestroy() {
        this.form = null;
    }
};
</script>
