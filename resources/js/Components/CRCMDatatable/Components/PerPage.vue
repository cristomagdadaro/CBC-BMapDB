<template>
    <div class="flex items-center gap-1">
        <span>Show</span>
        <select class="border-gray-300 py-0.5 rounded text-center" @change="$emit('changePerPage', $event.target.value)">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
        <span>entries</span>
    </div>
</template>
