<template>
    <div class="flex flex-col gap-0.5 w-full">
        <div v-if="label" class="text-xs text-gray-500 flex items-center justify-between">
            <span class="flex gap-0.5 whitespace-nowrap">{{ label }}</span>
        </div>
        <div>
            <div class="w-full focus-within:ring-1 flex gap-1 justify-between items-center bg-white rounded px-4 py-2 border-gray-200 border" @click.prevent="toggle">
                <div v-if="!searchable" class="text-gray-600 whitespace-nowrap overflow-hidden overflow-ellipsis">{{ selected? selected.label : placeholder }}</div>
                <input v-else type="text" @keydown.esc="search = null" @keydown="filterOptions()" v-model="search" class="w-full text-gray-600 border-none focus:outline-none focus:border-transparent focus:ring-0 p-0" :placeholder="selected? selected.label : placeholder" />
                <div class="flex gap-2 items-center">
                    <close-icon class="h-5 w-5" v-if="selected && showClear" @click.prevent="select(null)" />
                    <slot name="icon" :class="open?'rotate-180':'rotate-360'" class="h-4 w-4 duration-300" />
                </div>
            </div>
            <div v-show="open" class="fixed inset-0 z-48" @click.prevent="open = false" />
            <transition-container>
                <div
                    v-show="open"
                    class="z-50 absolute border border-gray-300 shadow rounded bg-white mt-1 py-2 max-h-[30vh] overflow-hidden overflow-y-auto py-2"
                >
                    <div v-if="filteredOptions" class="hidden text-xs text-gray-700 px-2 shadow-lg">Options</div>
                    <dropdown-option v-if="!filteredOptions.length">No options available</dropdown-option>
                    <template v-else>
                        <dropdown-option v-if="withAllOption" @click.prevent="select({name:null, label:'All fields'})" :selected="selected && selected.name === defaultOption.name">All fields</dropdown-option>
                        <dropdown-option v-for="option in filteredOptions" @click.prevent="select(option)" :selected="option.name === value">
                            {{ option.label }}
                        </dropdown-option>
                    </template>
                </div>
            </transition-container>
        </div>
    </div>
</template>
<script>
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";
import CaretDown from "@/Components/Icons/CaretDown.vue";
import CaretUp from "@/Components/Icons/CaretUp.vue";
import DropdownOption from "@/Components/CustomDropdown/Components/DropdownOption.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import TextField from "@/Components/Form/TextField.vue";

export default {
    components: {TextField, CloseIcon, DropdownOption, CaretUp, CaretDown, TransitionContainer},
    props: {
        searchable: {
            type: Boolean,
            required: false,
            default: false,
        },
        label: {
            type: String,
            required: false,
        },
        withAllOption: {
            type: Boolean,
            required: false,
            default: true,
        },
        placeholder: {
            type: String,
            required: false,
        },
        options: {
            type: Object,
            required: false,
        },
        value: {
            type: [String, Number],
            required: false,
        },
        showClear: {
            type: Boolean,
            default: true,
        },
    },
    data(){
        return {
            open: false,
            defaultOption: {name: null, label: 'All fields', selected: true},
            selected: null,
            search: null,
            filteredOptions: [],
        }
    },
    methods: {
        toggle(){
            this.open = !this.open;
        },
        select(option){
            if (option){
                this.$emit('selectedChange', option.name);
            } else {
                this.$emit('selectedChange', null);
            }
            this.selected = option;
            this.open = false;
        },
        // select the option with value is given
        selectByValue(value){
            this.selected = this.options.find(option => option.name === value);
            this.$emit('selectedChange',this.selected? this.selected.name : null);
        },
        filterOptions(){
            if (this.search)
                this.filteredOptions = this.options.filter(option => option.label.toLowerCase().includes(this.search.toLowerCase()));
            else
                this.filteredOptions = this.options;
        }
    },
    watch: {
        'options': {
            handler(){
                this.filteredOptions = this.options;
            },
            deep: true,
        },
    },
    mounted() {
        if (!this.options) return;
        this.selected = this.options.find(option => option.selected);
        this.selectByValue(this.value);
        this.filteredOptions = this.options;

    }
}
</script>
