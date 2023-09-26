<template>
    <th v-if="isSortedColumn" class="p-1 bg-vsu-olive whitespace-nowrap max-w-fit">
        {{ title }}
        <template v-if="sortDir === 'asc'">
            <span class="asc"></span>
        </template>
        <template v-else>
            <span class="desc"></span>
        </template>
    </th>
    <th v-else class="p-1 whitespace-nowrap max-w-fit">
        {{ title }}
    </th>
</template>
<script>
export default {
    props: {
        title: {
            type: String,
            required: true,
        },
        isSortedColumn: {
            type: Boolean,
            required: true,
        },
        sortDir: {
            type: String,
            required: true,
        },
    },
}
</script>
<style>
.asc::after {
    content: '▲';
}

.desc::after {
    content: '▼';
}
</style>
