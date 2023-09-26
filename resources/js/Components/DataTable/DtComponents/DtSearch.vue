<template>
    <input :value="modelValue" class="appearance-none text-sm border-none w-full h-8 text-gray-700 py-1 px-2 leading-tight focus:ring-0 focus:outline-none"
           type="text" placeholder="Search" aria-label="Search box"
           @input="$emit('update:modelValue', $event.target.value)"
            @keydown.enter="func">
    <button v-if="modelValue" @click="clearSearch" class="h-8 flex items-center flex-shrink-0 bg-gray-100 hover:bg-gray-200 border-transparent text-sm text-gray-600 py-1 px-2" type="button">
        <CloseIcon class="h-5 w-5 hover:rotate-90 duration-300" />
    </button>
    <button class="h-8 flex items-center flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-transparent text-sm text-white py-1 px-2 rounded-e-sm"
            @click="func">
        <SearchIcon class="h-4 w-4" />
    </button>
</template>
<script setup>
import SearchIcon from '@/Components/Icons/SearchIcon.vue';
import CloseIcon from '@/Components/Icons/CloseIcon.vue';
</script>
<script>
export default {
    props: {
        columns: {
            type: Array,
            required: true,
        },
        modelValue: {
            type: [String, null],
            required: true,
        },
        searchBy: {
            type: String,
            required: false,
        },
        func: {
            type: Function,
            required: true,
        },
    },
    methods: {
        clearSearch() {
            this.$emit('update:modelValue', null);
            this.func();
        },
        updateSearchBy() {
            this.$emit('update:searchBy', document.getElementById('searchBy').value );
            this.func();
        },
    },
    watch: {
        modelValue() {
           if(this.modelValue === '' || this.modelValue === null) {
               this.func();
           }
        },
    },
}
</script>
