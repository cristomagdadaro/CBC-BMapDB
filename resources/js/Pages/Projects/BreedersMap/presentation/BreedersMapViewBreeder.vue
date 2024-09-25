<script>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder.ts";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity.ts";
import CommodityTable from "@/Pages/Projects/BreedersMap/presentation/components/commodity/CommodityTable.vue";
import Tab from "@/Components/Tab/Tab.vue";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
export default {
    name: "BreedersMapViewBreeder",
    components: {Tab, Head, CommodityTable, AppLayout, Map},
    props: {
        breeder: {
            type: Object,
            required: false,
            default: null,
        }
    },
    data() {
        return {
            data: null,
            axiosInstance: null,
            tabs: [
                {
                    name: "tab1",
                    label: "Commodities",
                    active: true,
                    route: { name: 'breedersmap.breeder.view' },
                },{
                    name: "tab2",
                    label: "Geo Map",
                    active: false,
                    route: { name: 'projects.breedersmap.geomap' },
                },
            ],
        }
    },
    computed: {
        Breeder() {
            return Breeder
        },
        breederInstance() {
            if (this.breeder)
                return new Breeder(this.breeder);
            return null;
        },
        Commodity() {
            return Commodity
        },
        commodities() {
            if (this.breeder && this.breeder.commodities)
                return this.breeder.commodities.map(commodity => new Commodity(commodity));
            return [];
        }
    },
}
</script>

<template>
    <Head title="Breeders' Map View" />
    <app-layout>
        <div class="min-h-screen bg-transparent min-w-full m-2 p-2">
            <div v-if="breederInstance" class="flex flex-col gap-2">
                <h1 class="text-lg font-semibold uppercase select-none px-3">Breeder Information</h1>
                <div class="border p-3 rounded-lg bg-white mx-2 grid sm:grid-cols-2 grid-cols-1">
                    <div class="flex gap-1" v-for="column in Breeder.visibleColumns()">
                        <h2 class="h2 font-semibold select-none text-normal">{{column.title}}: </h2>
                        <p class="text-normal">{{ Breeder.getNestedValue(breederInstance, column.key) }}</p>
                    </div>
                </div>
                <h1 class="text-lg font-semibold uppercase select-none px-3 mt-5">Commodities</h1>
                <Tab :tabs="tabs">
                    <template #tab1>
                        <commodity-table :base-url="route('api.commodities.index', breederInstance.id)" />
                    </template>
                    <template #tab2>
                        <div class="p-2 relative">
                            <h1 class="h1 text-center font-semibold uppercase select-none">Commodities Geographical Map</h1>
                            <Map :base-url="route('api.commodities.index', breeder.id)" :model="Commodity"/>
                        </div>
                    </template>
                </Tab>
            </div>
        </div>
    </app-layout>
</template>

<style scoped>

</style>
