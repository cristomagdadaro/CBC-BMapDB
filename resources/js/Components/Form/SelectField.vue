<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
    id: String,
    label: String,
    options: Array,
    required: {
        type: Boolean,
        default: false,
    },
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
    <div class="flex flex-col border-0 p-0">
        <label :for="id" class="text-xs text-gray-600">{{ label }} <span v-if="required" class="text-red-500 font-bold">*</span></label>
        <select
            :id="id"
            :required="required"
            ref="input"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        >
            <option v-for="option in options" :key="option.value" :value="option.value">{{ option.label }}</option>
        </select>
    </div>
</template>
