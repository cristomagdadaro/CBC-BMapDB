<script>
import PhilippineMapOutline from "@/Components/Icons/PhilippineMapOutline.vue";
import {Link} from "@inertiajs/vue3";
export default {
    name: "BmOverviewMap",
    components: {PhilippineMapOutline, Link},
    data() {
        return {
            hoveredData: null,
        }
    }
}
</script>

<template>
    <h3 class="text-subtitle text-center text-dark-color">
        Plant Breeders across the Philippines
    </h3>
    <div class="flex flex-col-reverse select-none sm:flex-row justify-between items-center gap-5 text-center sm:text-left">
        <div v-if="hoveredData" class="text-gray-900">
            <span class="text-subtitle" >{{ hoveredData.province }}</span>
            <span class="text-normal"><br /> has a total of <b>{{ hoveredData.data }}</b> plant breeders <br /></span>
        </div>
        <div v-else class="flex flex-col text-normal text-gray-900 drop-shadow">
            <span class="flex">
                <span class="hidden sm:block">Hover</span><span class="sm:hidden block">Click</span>&nbsp;over a province to see data or
            </span>
            <Link :href="route('projects.breedersmap.public')" class="text-cbc-dark-green underline">click here to view the full map</Link>
        </div>
        <philippine-map-outline class="max-w-[20rem] lg:max-w-[30rem] drop-shadow-lg" @hovered="hoveredData = $event" @mouseleave="hoveredData = null" :data="$page.props.breedersmap_overview"/>
    </div>
</template>

<style scoped>

</style>
