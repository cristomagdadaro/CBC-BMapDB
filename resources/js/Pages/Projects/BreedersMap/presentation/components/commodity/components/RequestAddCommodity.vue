<script>
import { Link } from "@inertiajs/vue3";

export default {
    name: "RequestAddCommodity",
    components: {Link}
}
</script>

<template>
    <div class="flex flex-col border-0 p-0 bg-transparent gap-1">
        <div class="flex justify-end items-center px-1 gap-2">
            <span class="text-gray-600 opacity-50 font-bold text-[0.6rem] text-right leading-none border border-gray-800 rounded-full p-0.5 px-1 hover:bg-cbc-dark-green hover:text-white hover:border-cbc-dark-green" title="Can't find your commodity?">
                ?
            </span>
        </div>
        <div class="flex relative rounded-md shadow-sm border bg-white hover:ring-1 active:ring-1 h-fit p-2">
            <Link :href="route('breedersmap.commodity.requestaddcommodity')" @click.prevent class="border-0 w-full rounded-md text-gray-900 focus:ring-0 overflow-ellipsis leading-none text-xs">
                Request to Add Commodity
            </Link>
        </div>
    </div>
</template>

<style scoped>

</style>
