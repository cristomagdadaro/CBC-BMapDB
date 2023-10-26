<template>
    <div class="flex flex-col gap-0.5">
        <span class="text-xs text-gray-500 flex items-center justify-between">
            <span class="flex gap-0.5">Filter by</span>
            <div class="flex items-center gap-0.5">
                <label for="exactValue"></label>
                <input type="checkbox" v-model="is_exact" @change="$emit('isExact', is_exact )" class="rounded-full h-3 w-4 focus:outline-none" title="turn on filter by exact value">
            </div>
        </span>
        <custom-dropdown placeholder="Filters" :options="columns" @selectedChange="$emit('searchBy', $event)">
            <template #icon>
                <filter-icon class="h-4 w-4" />
            </template>
        </custom-dropdown>
    </div>
</template>
<script>
    import {ref} from "vue";
    import SearchIcon from "@/Components/Icons/SearchIcon.vue";
    import FilterIcon from "@/Components/Icons/FilterIcon.vue";
    import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";

    export default {
        components: {CustomDropdown, FilterIcon, SearchIcon},
        props: {
            columns: {
                type: Object,
                required: false,
            },
        },
        data(){
            return {
                selected: null,
                is_exact: false,
            }
        },
    }
</script>
