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
                <div class="flex flex-col shadow-md w-fit rounded-md mx-auto">
                    <div class="bg-white flex md:flex-row flex-col w-full rounded-md overflow-hidden p-2">
                        <div
                            v-if="profilePhoto"
                            class="block w-full h-52 rounded-md lg:w-44 lg:h-44 md:w-32 md:h-32 bg-cover bg-no-repeat bg-center"
                            :style="'background-image: url(\'' + profilePhoto + '\');'"
                        />
                        <div class="flex flex-col leading-none p-5 justify-between w-full text-center">
                            <div class="flex flex-col leading-none mb-1">
                                <span class="lg:text-5xl md:text-3xl sm:text-xl text-xl font-bold">
                                    {{ Breeder.getNestedValue(breederInstance, 'getFullName') }}
                                </span>
                            </div>
                            <div class="flex flex-col leading-none">
                                 <span class="text-normal md:text-xl font-semibold">
                                    {{ Breeder.getNestedValue(breederInstance, 'position') }}
                                 </span>
                                 <span class="text-normal">
                                    at {{ Breeder.getNestedValue(breederInstance, 'affiliated.name') }}
                                 </span>
                                 <span class="text-sm">
                                    {{ Breeder.getNestedValue(breederInstance, 'location.getFullAddress') }}
                                 </span>
                            </div>
                            <div class="flex flex-row gap-3 justify-evenly leading-none text-sm mt-1">
                                <div class="flex gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                                    </svg>
                                    <span>
                                   {{ Breeder.getNestedValue(breederInstance, 'email') }}
                                </span>
                                </div>
                                <div class="flex gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
                                    </svg>
                                    <span>
                                   {{ Breeder.getNestedValue(breederInstance, 'mobile_no') }}
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white flex flex-row w-full overflow-hidden" :class="{'hidden': !Breeder.getNestedValue(breederInstance, 'expertise') && !Breeder.getNestedValue(breederInstance, 'research_interest')}">
                       <div class="flex gap-3 justify-evenly w-full py-3 bg-cbc-yellow-green mx-2 my-1 rounded-md">
                           <span>
                               Specialization
                           </span>
                           <span>
                               Research Interest
                           </span>
                       </div>
                    </div>
                    <div class="bg-white grid grid-cols-2 justify-center rounded-md overflow-hidden p-3 text-center" :class="{'hidden': !Breeder.getNestedValue(breederInstance, 'expertise') && !Breeder.getNestedValue(breederInstance, 'research_interest')}">
                       <span>
                           {{ Breeder.getNestedValue(breederInstance, 'expertise') }}
                       </span>
                            <span>
                           {{ Breeder.getNestedValue(breederInstance, 'research_interest') }}
                       </span>
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
