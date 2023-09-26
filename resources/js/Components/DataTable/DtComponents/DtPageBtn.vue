<template>
    <div class="flex items-center" v-if="selected.length > 1">
        <span>Selected {{ selected.length }} rows</span>
    </div>
    <div class="flex" v-if="totalPages > 1 && totalPages <= 10">
        <template v-for="i in totalPages" :key="i">
            <button :class="{'bg-gray-300': i === pageNumber }" class="dtBtn-sm border" @click="changePageNumber(i)">
                {{ i }}
            </button>
        </template>
    </div>
    <div class="flex overflow-hidden" v-else-if="totalPages > 1">
        <button :class="{'bg-gray-300': 1 === pageNumber }" class="dtBtn-sm border" @click="changePageNumber(1)">
            1
        </button>
        <template v-if="pageNumber > 3">
            <span class="px-2">&hellip;</span>
        </template>
        <template v-if="pageNumber > 2">
            <button :class="{'bg-gray-300': pageNumber - 1 === pageNumber }" class="dtBtn-sm border" @click="changePageNumber(pageNumber - 1)">
                {{ pageNumber - 1 }}
            </button>
        </template>
        <button v-if="pageNumber !== 1 && pageNumber !== totalPages" :class="{'bg-gray-300': pageNumber === pageNumber }" class="dtBtn-sm border" @click="changePageNumber(pageNumber)">
            {{ pageNumber }}
        </button>
        <template v-if="pageNumber < totalPages - 1">
            <button :class="{'bg-gray-300': pageNumber + 1 === pageNumber }" class="dtBtn-sm border" @click="changePageNumber(pageNumber + 1)">
                {{ pageNumber + 1 }}
            </button>
        </template>

        <template v-if="totalPages - pageNumber > 3">
            <span class="px-2">&hellip;</span>
        </template>
        <button :class="{'bg-gray-300': totalPages === pageNumber }" class="dtBtn-sm border" @click="changePageNumber(totalPages)">
            {{ totalPages }}
        </button>
    </div>
</template>
<script>
export default {
    props: {
        selected: Array,
        pageNumber: Number,
        totalPages: Number,
        changePageNumber: Function,
    },
};
</script>
<style>
    .dtBtn-sm {
        @apply flex items-center text-sm flex-shrink-0 py-0.5 px-2 mx-0.5 shadow-sm rounded-sm hover:bg-gray-200 border-gray-200 border;
    }
</style>
