<template>
    <div class="text-sm">
        <button class="focus-within:ring-1 flex gap-1 justify-between items-center bg-white rounded px-4 py-2 border-gray-200 border" @click="toggle">
            <div class="text-gray-600 whitespace-nowrap">{{ selected? selected.label : placeholder }}</div>
            <div :class="open?'rotate-180':'rotate-360'" class="h-4 w-4 duration-300">
                <slot name="icon" />
            </div>
        </button>
        <div v-show="open" class="fixed inset-0 z-48" @click="open = false" />
        <transition-container>
            <div
                v-show="open"
                class="z-50 absolute border border-gray-300 shadow rounded bg-white mt-1 py-2"
            >
                <div v-if="options" class="text-xs text-gray-200 px-2">Options</div>
                <dropdown-option v-if="!options">No options available</dropdown-option>
                <template v-else>
                    <dropdown-option v-if="withAllOption" @click="select({name:null, label:'All fields'})">All fields</dropdown-option>
                    <dropdown-option v-for="option in options" :key="option.name" @click="select(option)" :selected="option.name === value">
                        {{ option.label }}
                    </dropdown-option>
                </template>
            </div>
        </transition-container>
    </div>
</template>
<script>
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";
import CaretDown from "@/Components/Icons/CaretDown.vue";
import CaretUp from "@/Components/Icons/CaretUp.vue";
import DropdownOption from "@/Components/CustomDropdown/Components/DropdownOption.vue";

export default {
    components: {DropdownOption, CaretUp, CaretDown, TransitionContainer},
    props: {
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
    },
    data(){
        return {
            open: false,
            selected: null,
        }
    },
    methods: {
        toggle(){
            this.open = !this.open;
        },
        select(option){
            this.selected = option;
            this.$emit('selectedChange', this.selected.name);
            this.open = false;
        },
        // select the option with value is given
        selectByValue(value){
            this.selected = this.options.find(option => option.name === value);
        },
    },
    mounted() {
        this.selected = this.options.find(option => option.selected);
        this.selectByValue(this.value);
    }
}
</script>
