<script setup>
import { onMounted, ref } from 'vue';
import InputError from "@/Components/InputError.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import ViewIcon from "@/Components/Icons/ViewIcon.vue";
import UnviewIcon from "@/Components/Icons/UnviewIcon.vue";

defineProps({
    modelValue: [String, Number],
    id: String,
    label: String,
    error: {
        type: [String, Array],
        default: null,
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
        default: true,
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);
const showPassword = ref(false);

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
            <label :for="id" class="flex text-sm gap-0.5 items-center whitespace-nowrap">
                {{ label }}
                <span v-if="required" class="text-red-500 font-bold text-xs">*</span>
            </label>
            <InputError :message="error" />
        </div>
        <div v-if="typeInput === 'password'" class="flex relative rounded-md shadow-sm border bg-white hover:ring-1 active:ring-1" :class="error && error.length? 'border-red-300 focus:border-red-500 focus:ring-red-500':'border-gray-300 focus:border-indigo-500 overflow-ellipsis focus:ring-indigo-500'">
            <input :id="id"
                   :type="showPassword? 'text':'password'"
                   ref="input"
                   @change="error=null"
                   class="border-0 w-full rounded-md text-gray-900 focus:ring-0 overflow-ellipsis"
                   :value="modelValue"
                   @input="$emit('update:modelValue', $event.target.value)"
            >
            <div v-if="modelValue" title="Toggle view password" @click="showPassword = !showPassword" class="text-cbc-dark-green bg-transparent flex items-center border-gray-300 focus:border-indigo-500 overflow-ellipsis rounded-r-md pr-2">
                <view-icon v-if="!showPassword" class="w-4 h-4 hover:scale-125 duration-200" />
                <unview-icon v-else class="w-4 h-4 hover:scale-125 duration-200" />
            </div>
        </div>
        <div v-else-if="typeInput !== 'longtext'" class="flex relative rounded-md shadow-sm border bg-white hover:ring-1 active:ring-1" :class="error && error.length? 'border-red-300 focus:border-red-500 focus:ring-red-500':'border-gray-300 focus:border-indigo-500 overflow-ellipsis focus:ring-indigo-500'">
            <input :id="id"
                   :type="typeInput"
                   ref="input"
                   @change="error=null"
                   class="border-0 w-full rounded-md text-gray-900 focus:ring-0 overflow-ellipsis"
                   :value="modelValue"
                   @input="$emit('update:modelValue', $event.target.value)"
            >
            <div title="clear field" v-if="modelValue && showClear" @click="input = null; $emit('update:modelValue', null)" class="text-cbc-dark-green bg-transparent flex items-center border-gray-300 focus:border-indigo-500 overflow-ellipsis rounded-r-md pr-2">
                <close-icon class="w-4 h-4 hover:scale-125 duration-200" />
            </div>
        </div>
        <textarea v-else
        :id="id"
        id="id"
        ref="input"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-fit-content"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)">

        </textarea>
    </div>
</template>
