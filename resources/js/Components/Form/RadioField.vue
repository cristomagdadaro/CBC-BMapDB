<script>
import InputError from "@/Components/InputError.vue";

export default {
    components: {InputError},
    props: {
        modelValue: {
            type: String,
            default: '',
        },
        id: String,
        label: String,
        options: {
            type: Array,
            default: () => [],
        },
        error: {
            type: Array,
            default: () => [],
        },
        required: {
            type: Boolean,
            default: false,
        },
    },
    emits: ['update:modelValue'],
};
</script>

<template>
    <div class="flex flex-col relative">
        <label :for="id" class="text-gray-600">
            <label :for="id" class="text-gray-600 flex gap-0.5 items-center">
                {{ label }}
                <span v-if="required" class="text-red-500 font-bold text-xs">*</span>
            </label>
            <InputError v-for="msg in error" :message="msg" />
        </label>
        <div class="flex gap-3 justify-center items-center h-full rounded-md shadow-sm border bg-white hover:ring-1 active:ring-1" :class="error.length? 'border-red-300 focus:border-red-500 focus:ring-red-500':'border-gray-300 focus:border-indigo-500 overflow-ellipsis focus:ring-indigo-500'">
            <div v-for="option in options" :key="option.value" class="flex gap-0.5 items-center">
                <input
                    type="radio"
                    :id="`${id}-${option.value}`"
                    :name="id"
                    :value="option.value"
                    :checked="modelValue === option.value"
                    @change="$emit('update:modelValue', option.value)"
                />
                <label :for="`${id}-${option.value}`">{{ option.label }}</label>
            </div>
        </div>
    </div>
</template>
