<script setup>
import { onMounted, ref } from 'vue';
import InputError from "@/Components/InputError.vue";

defineProps({
    modelValue: String,
    id: String,
    label: String,
    error: String,
    typeInput: {
        type: String,
        default: 'text',
    },
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
        <div class="flex justify-between items-center">
            <label :for="id" class="text-xs text-gray-600">{{ label }} <span v-if="required" class="text-red-500 font-bold">*</span></label>
            <InputError :message="error" />
        </div>
        <input v-if="typeInput !== 'longtext'"
        :id="id"
        :type="typeInput"
        :required="required"
        ref="input"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        >
        <textarea v-else
        :id="id"
        id="id"
        :required="required"
        ref="input"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)">

        </textarea>
    </div>
</template>
