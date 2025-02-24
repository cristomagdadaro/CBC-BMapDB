<script>
import {Head} from "@inertiajs/vue3";
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
                    route: {name: 'breedersmap.breeder.view', params: {id: this.breeder?.id}},
                }, {
                    name: "tab2",
                    label: "Geo Map",
                    active: false,
                    route: {name: 'breedersmap.breeder.geomap', params: {id: this.breeder?.id}},
                },
            ],
            tables: [
                { label: 'Commodity', name: 'commodities', route: route('api.commodities.summary', () => { return this.breeder.id ? this.breeder.id : null; }), model: Commodity },
            ]
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
        },
        profilePhoto() {
            return this.breederInstance?.getProfilePhoto;
        }
    },
}
</script>

<template>
    <Head title="Plant Breeders Map View" />
    <app-layout>
        <div class="min-h-screen bg-transparent min-w-full m-2 p-2">
            <div v-if="breederInstance" class="flex flex-col gap-2">
                <h1 class="text-lg font-semibold uppercase select-none px-3">Breeder Information</h1>
                <div class="border p-3 rounded-lg bg-white mx-2 flex gap-3 items-center">
                    <span
                        v-if="profilePhoto"
                        class="block rounded-lg w-20 h-20 lg:w-44 lg:h-44 md:w-32 md:h-32 bg-cover bg-no-repeat bg-center drop-shadow border-2 border-cbc-dark-green"
                        :style="'background-image: url(\'' + profilePhoto + '\');'"
                    />
                    <div class="grid sm:grid-cols-2 grid-cols-1 w-full">
                        <template v-for="column in Breeder.getCardColumns()" >
                            <div class="flex gap-1" v-if="column.visible">
                                <h2 class="h2 font-semibold select-none text-normal">{{column.title}}: </h2>
                                <p class="text-normal">{{ Breeder.getNestedValue(breederInstance, column.key) }}</p>
                            </div>
                        </template>
                    </div>
                </div>
                <h1 class="text-lg font-semibold uppercase select-none px-3 mt-5">Commodities</h1>
                <Tab :tabs="tabs">
                    <template #tab1>
                        <commodity-table :base-url="route(Commodity.indexUri)" :params="{ filter_by_parent_id: breederInstance.id,  filter_by_parent_column: 'breeder_id' }" />
                    </template>
                    <template #tab2>
                        <div class="p-2 relative">
                            <h1 class="h1 text-center font-semibold uppercase select-none">Commodities Geographical Map</h1>
                            <Map :table-list="tables" :model="Commodity" offline :custom-point="breederInstance.commodities" />
                        </div>
                    </template>
                </Tab>
            </div>
        </div>
    </app-layout>
</template>

<style scoped>

</style>
