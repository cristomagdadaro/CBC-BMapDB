<script setup>
import { onMounted, ref } from 'vue';
import InputError from "@/Components/InputError.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";

defineProps({
    modelValue: [String, Number],
    id: String,
    label: String,
    error: {
        type: Array,
        default: () => [],
    },
    typeInput: {
        type: String,
        default: 'text',
    },
    required: {
        type: Boolean,
        default: false,
    },
    showClear: {
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
    <div class="flex flex-col border-0 p-0 bg-transparent">
        <div class="flex justify-between items-center">
            <label :for="id" class="text-xs text-gray-600">{{ label }} <span v-if="required" class="text-red-500 font-bold">*</span></label>
            <InputError v-for="msg in error" :message="msg" />
        </div>
        <div class="flex rounded-md shadow-sm bg-white hover:ring-1 active:ring-1" v-if="typeInput !== 'longtext'" :class="error.length? 'border-red-300 focus:border-red-500 focus:ring-red-500':'border-gray-300 focus:border-indigo-500 overflow-ellipsis focus:ring-indigo-500'">
            <input :id="id"
                   :type="typeInput"
                   ref="input"
                   @change="error=[]"
                   class="border-0 w-full rounded-md focus:ring-0"
                   :value="modelValue"
                   @input="$emit('update:modelValue', $event.target.value)"
            >
            <div title="clear field" v-if="modelValue && showClear" @click="input = null; $emit('update:modelValue', null)" class="text-cbc-dark-green bg-transparent flex items-center border-gray-300 focus:border-indigo-500 overflow-ellipsis rounded-r-md pr-2">
                <close-icon class="w-4 h-4 hover:scale-110 duration-200" />
            </div>
        </div>
        <textarea v-else
        :id="id"
        id="id"
        ref="input"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)">

        </textarea>
    </div>
</template>
