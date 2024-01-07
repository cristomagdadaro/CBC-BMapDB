<!--
options Array format

{
    label: 'Sample',
    value: 'sample',
}
-->
<script>
import { onMounted, ref } from 'vue';
import TextField from "@/Components/Form/TextField.vue";
export default {
    components: {TextField},
    emits: ['update:modelValue'],
    props:{
        modelValue: String,
        id: String,
        label: String,
        options: Array,
        required: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            showDropdown: false,
            input: null,
        };
    },
    methods: {
        toggleDropdown() {
            this.showDropdown = !this.showDropdown;
        },
        closeDropdown() {
            this.showDropdown = false;
        },
    },
    expose: ['focus'],
}
</script>

<template>
    <div class="flex flex-col border-0 p-0 bg-transparent" @mouseleave="closeDropdown()">
        <label :for="id" class="text-xs text-gray-600">{{ label }} <span v-if="required" class="text-red-500 font-bold">*</span></label>
        <div class="flex flex-col gap-1">
            <text-field
                :id="id"
                ref="input"
                :error="$attrs.error"
                :required="required"
                :value="modelValue"
                @click="toggleDropdown()"
                @input="$emit('update:modelValue', $event.target.value)"
            />
            <div v-show="showDropdown" class="fixed inset-0 z-40" @click="closeDropdown()" />
            <div v-show="showDropdown">
                <div
                    ref="input"
                    class="fixed border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-md bg-white p-2"
                    @input="$emit('update:modelValue', $event.target.value)"
                >
                    <div v-for="option in options" :key="option.value" class="hover:bg-gray-200 px-1 select-none rounded-sm">{{ option.label }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
