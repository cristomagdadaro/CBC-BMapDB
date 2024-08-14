<script>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService.js";
import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder.js";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity.js";
import CommodityTable from "@/Pages/Projects/BreedersMap/presentation/components/commodity/CommodityTable.vue";
import Tab from "@/Components/Tab/Tab.vue";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
export default {
    name: "BreedersMapViewBreeder",
    components: {Tab, Head, CommodityTable, AppLayout, Map},
    props: {
        breeder: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            data: null,
            axiosInstance: new ApiService(route('api.breeders.show', this.breeder.id)),
            tabs: [
                {
                    name: "tab1",
                    label: "Commodities",
                    active: true,
                },{
                    name: "tab2",
                    label: "Geo Map",
                    active: false,
                },
            ],
        }
    },
    computed: {
        Breeder() {
            return Breeder
        },
        Commodity() {
            return Commodity
        },
        commodities() {
            if (this.breeder.commodities)
                return this.breeder.commodities.map(commodity => new Commodity(commodity));
            return [];
        }
    },
    methods: {
        getDataFromAPI() {
            this.axiosInstance.get().then(response => {
                this.data = response.data
            }).catch(error => {
                console.log(error);
            });
        }
    },
    watch: {
        breeder() {
            this.breeder = new Breeder(this.breeder);
        }
    },
    mounted() {
        this.getDataFromAPI();
    }
}
</script>

<template>
    <Head title="Breeder's Map View" />
    <app-layout>
        <div class="min-h-screen bg-transparent min-w-full m-2 p-2">
            <div v-if="breeder" class="flex flex-col">
                <h1 class="text-lg font-semibold uppercase select-none px-3 pb-2 mx-2">Breeder Information</h1>
                <div class="border p-3 rounded-lg bg-white mx-2">
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Identification No.: </h2>
                        <p>{{ breeder.id }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Breeder: </h2>
                        <p>{{ breeder.name }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Agency/Institution/Affiliation: </h2>
                        <p>{{ breeder.agency }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Affiliation Address: </h2>
                        <p>{{ breeder.address }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Phone: </h2>
                        <p>{{ breeder.phone }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Email: </h2>
                        <p>{{ breeder.email }}</p>
                    </div>
                </div>
                <Tab :tabs="tabs">
                    <template #tab1>
                        <commodity-table :params="{ filter:'breeder_id', is_exact:true, search:this.$page.props.breeder.id }" />
                    </template>
                    <template #tab2>
                        <div class="p-2 relative" v-if="axiosInstance && axiosInstance.baseUrl">
                            <h1 class="h1 text-center font-semibold uppercase select-none">Commodities Geographical Map</h1>
                            <Map :base-url="route('api.breeders.noPageSearch', breeder.id)" :model="Commodity"/>
                        </div>
                    </template>
                </Tab>
            </div>
        </div>
    </app-layout>
</template>

<style scoped>

</style>
