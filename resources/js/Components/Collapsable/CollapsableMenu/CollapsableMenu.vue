<script>
import FilterIcon from "@/Components/Icons/FilterIcon.vue";
import CaretDown from "@/Components/Icons/CaretDown.vue";
import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";

export default {
    name: "CollapsableMenu",
    components: {TransitionContainer, CustomDropdown, CaretDown, FilterIcon},
    props: {
        label: {
            type: String,
            required: false,
            default: 'Menu',
        },
        openDefault: {
            type: Boolean,
            required: false,
            default: false,
        }
    },
    data(){
        return {
            open: false,
        }
    },
    mounted() {
        this.open = this.openDefault;
    }
}
</script>

<template>
    <div class="flex flex-col gap-1 relative">
        <div @click="open = !open" class="flex items-center gap-2 border bg-gray-300 p-2 px-5 rounded w-fit">
            <p>{{ label }}</p>
            <filter-icon class="h-5 w-5 duration-300" :class="open? 'rotate-180':'rotate-0'"/>
        </div>
        <transition-container>
            <div v-show="open" class="select-none grid sm:grid-cols-4 sm:grid-rows-1 grid-cols-1 grid-rows-4 gap-1">
                <slot />
            </div>
        </transition-container>
    </div>
</template>

<style scoped>

</style>
