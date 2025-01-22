
<script setup>
import {onMounted, ref} from 'vue';
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
        <div class="flex justify-between items-center">
            <label :for="id" class="flex text-sm gap-0.5 items-center">
                {{ label }}
                <span v-if="required" class="text-red-500 font-bold text-xs">*</span>
            </label>
            <InputError :message="Array.isArray(error) ? error[0] : error" />
        </div>
        <input
            :id="id"
            ref="input"
            type="date"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            :value="modelValue"
            :disabled="disabled"
            @input="$emit('update:modelValue', $event.target.value)"
        />
    </div>
</template>
