<!--
options Array format

{
    label: 'Sample',
    value: 'sample',
}
-->
<script setup>
import { onMounted, ref } from 'vue';
import InputError from "@/Components/InputError.vue";

defineProps({
    modelValue: String,
    id: String,
    label: String,
    options: Array,
    error: {
        type: [String, Array],
        default: null,
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled : {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: null,
    }
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="flex flex-col border-0 p-0 bg-transparent">
        <div class="flex justify-between items-center px-1 gap-2">
            <label :for="id" class="flex gap-0.5 items-center whitespace-nowrap justify-between px-1">
                {{ label }}
                <span v-if="required" class="text-red-500 font-bold text-normal">*</span>
            </label>
            <InputError v-if="error" :message="Array.isArray(error) ? error[0] : error" />
            <span v-else-if="title" :title="title" class="text-gray-600 opacity-50 font-bold text-[0.6rem] text-right leading-none border border-gray-800 rounded-full p-0.5 px-1 hover:bg-cbc-dark-green hover:text-white hover:border-cbc-dark-green" title="Can't find your commodity?">
                ?
            </span>
        </div>
        <select
            :id="id"
            ref="input"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            :value="modelValue"
            :disabled="disabled"
            @input="$emit('update:modelValue', $event.target.value)"
        >
            <option v-for="option in options" :key="option.value" :value="option.value">{{ option.label }}</option>
        </select>
    </div>
</template>
