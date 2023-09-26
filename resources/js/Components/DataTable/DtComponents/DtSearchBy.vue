<template>
    <select class="rounded-sm appearance-none text-sm bg-transparent border-none w-auto h-8 text-gray-700 py-1 px-2 pr-7 leading-tight focus:outline-none focus:ring-0"
            name="searchBy" id="searchBy" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)">
        <option value="*" disabled>Search by:</option>
        <option value="" selected>All</option>
        <template v-for="col in columns">
            <option v-if="col.searchable" :value="col.name" :key="col.id">{{ col.title }}</option>
        </template>
    </select>
</template>
<script>
export default {
    props: {
        columns: {
            type: Array,
            required: true,
        },
        modelValue: {
            type: String,
            required: true,
        },
    },
}
</script>
