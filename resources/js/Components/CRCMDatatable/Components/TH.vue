<template>
    <th class="dtHeaderColumn px-0.5 whitespace-nowrap">
        <div class="dtHeaderCell flex gap-0.5 justify-center items-center bg-cbc-dark-green rounded">
            <span class="dtHeaderCellText text-white">
                <slot />
            </span>
            <span class="dtHeaderCellSortIco text-yellow-500" :class="props.order"> </span>
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
