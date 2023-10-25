<template>
    <div class="relative">
        <div class="flex gap-1 min-w-[7rem] justify-between items-center bg-white rounded px-4 py-1 shadow" @click="toggle">
            <button class="text-gray-600">{{ selected? selected.label : placeholder }}</button>
            <caret-down :class="open?'rotate-180':'rotate-360'" class="h-4 w-4 duration-300" />
        </div>
        <div v-show="open" class="fixed inset-0 z-48" @click="open = false" />
        <transition-container>
            <div
                v-show="open"
                class="z-50 absolute border border-gray-300 shadow rounded bg-white mt-1"
            >
                <dropdown-option v-if="!options">No options available</dropdown-option>
                <template v-else>
                    <dropdown-option v-if="withAllOption" @click="select({name:null, label:'All fields'})">All fields</dropdown-option>
                    <dropdown-option v-for="option in options" :key="option.name" @click="select(option)">
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
        }
    },
    mounted() {
        this.selected = this.options.find(option => option.selected);
    }
}
</script>
