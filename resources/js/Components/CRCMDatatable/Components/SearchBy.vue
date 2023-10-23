<template>
    <div class="flex items-center gap-1">
        <select class="border-0 py-1.5 rounded text-center">
            <option v-if="!columns.length" value="null">No options available</option>
            <template v-else>
                <option value="null">Filter By</option>
                <option v-for="column in columns" :key="column.id" :value="column.id">{{ column.label }}</option>
            </template>
        </select>
    </div>
</template>
<script>
    export default {
        props: {
            columns: {
                type: Object,
                required: false,
            },
        },
    }
</script>
