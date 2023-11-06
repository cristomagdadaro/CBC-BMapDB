<script setup>
import Modal from './Modal.vue';
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import {watch} from "vue";
import SpinnerIcon from "@/Components/Icons/SpinnerIcon.vue";
import DoubleArrowLoaderIcon from "@/Components/Icons/DoubleArrowLoaderIcon.vue";
const emit = defineEmits(['close']);

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    forceClose: {
        type: Boolean,
        default: false,
    },
    processing: {
        type: Boolean,
        default: false,
    },
});

watch(() => props.forceClose, (value) => {
    if (value) {
        close();
    }
});

const close = () => {
    emit('close');
};
</script>

<template>
    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <div class="px-4 py-2 bg-gray-100 shadow-md">
            <div class="text-lg font-medium text-gray-900 flex justify-between">
                <slot name="title" />
                <button class="text-sm font-medium text-blue-500" @click="$emit('close')">
                    <CloseIcon class="w-7 h-auto hover:scale-110 active:scale-95 duration-100" />
                </button>
            </div>

            <div class="mt-4 text-sm text-gray-600">
                <slot name="content"/>
            </div>
        </div>
        <span v-if="processing" class="flex bg-gray-100 justify-center items-center gap-1">
            <double-arrow-loader-icon :class="{'animate-spin':processing}" class="w-5 h-auto" />
            executing action, please wait...
            </span>
        <div class="flex flex-row justify-end gap-1 px-6 py-4 bg-gray-100 text-right">
            <slot name="footer" />
        </div>
    </Modal>
</template>
