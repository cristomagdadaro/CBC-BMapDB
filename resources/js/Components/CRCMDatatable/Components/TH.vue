<template>
    <th class="dtHeaderColumn whitespace-nowrap text-white sm:text-md md:text-sm text-xs select-none" >
        <div class="dtHeaderCell flex gap-0.5 p-1 sm:py-2 justify-center items-center sm:rounded rounded-sm" :class="[{'hover:bg-opacity-70 ':sortable}, sortedColumnClass]">
            <span class="dtHeaderCellText">
                {{ props.column }}
            </span>
            <span class="dtHeaderCellSortIco text-yellow-500 text-xs" :class="sorted"></span>
        </div>
    </th>
</template>
<script setup>
    import {computed} from "vue";

    const props = defineProps({
        order: {
            type: String,
            default: 'asc',
        },
        sortable:{
            type: Boolean,
            default: false,
        },
        column: {
            type: String,
            default: null,
        },
        sortedValue: {
            type: Boolean,
            default: false,
        },
    })

    const sorted = computed(() => {
        return props.sortable && props.sortedValue ?props.order:'';
    })

    const sortedColumnClass = computed(() => {
        return props.sortedValue?'bg-gray-900':'bg-gray-600';
    })
</script>
<style>
.asc::after {
    content: '▲';
}

.desc::after {
    content: '▼';
}
</style>

