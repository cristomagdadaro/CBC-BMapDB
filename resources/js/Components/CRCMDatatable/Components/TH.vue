<template>
    <th class="dtHeaderColumn px-0.5 whitespace-nowrap">
        <div class="dtHeaderCell flex gap-0.5 py-1 justify-center items-center bg-cbc-dark-green rounded">
            <span class="dtHeaderCellText text-white">
                {{ props.column.label }}
            </span>
            <span class="dtHeaderCellSortIco text-yellow-500" :class="sorted"> </span>
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
</script>
<style>
.asc::after {
    content: '▲';
}

.desc::after {
    content: '▼';
}
</style>

