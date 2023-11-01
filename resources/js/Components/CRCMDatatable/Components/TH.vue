<template>
    <th class="dtHeaderColumn whitespace-nowrap text-white sm:text-md md:text-sm text-xs" >
        <div class="dtHeaderCell flex gap-0.5 p-0.5 justify-center items-center rounded" :class="[{'hover:bg-opacity-70 ':sortable}, sortedColumnClass]">
            <span class="dtHeaderCellText">
                {{ props.column.label }}
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
            type: Object,
            default: null,
        },
        sortedColumn: {
            type: String,
            default: null,
        },
    })

    const sorted = computed(() => {
        return props.sortable? props.sortedColumn===props.column.name?props.order:'':'';
    })

    const sortedColumnClass = computed(() => {
        return props.sortedColumn===props.column.name?'bg-gray-900':'bg-gray-600';
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

