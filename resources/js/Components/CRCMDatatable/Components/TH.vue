<template>
    <th class="dtHeaderColumn border border-gray-500">
        <div class="dtHeaderCell flex gap-0.5 justify-center items-center">
            <span class="dtHeaderCellText">
                <slot />
            </span>
            <span class="dtHeaderCellSortIco" :class="props.order"> </span>
        </div>
    </th>
</template>
<script setup>
    const props = defineProps({
        order: {
            type: String,
            default: 'asc',
        }
    })
</script>
