<script>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService.js";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity.js";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import CommodityTable from "@/Pages/Projects/BreedersMap/presentation/components/commodity/CommodityTable.vue";
export default {
    components: {Head, CommodityTable, AppLayout, Map},
    props: {
        commodity: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            id: this.commodity.id,
            data: null,
            axiosInstance: new ApiService(route('api.commodities.show', this.commodity.id))
        }
    },
    computed: {
        commodities() {
            return this.data['commodities'].map(commodity => new Commodity(commodity));
        }
    },
    methods: {
        getDataFromAPI() {
            this.axiosInstance.get({
                filter: 'id',
                search: this.commodity.id,
                is_exact: true
            }).then(response => {
                this.data = response.data
            }).catch(error => {
                console.log(error);
            });
        }
    },
    mounted() {
        this.getDataFromAPI();
    }
}
</script>

<template>
    <Head title="Breeder's Map" />
    <app-layout>
    <div class="p-5">
        <h1 class="h1 text-center font-semibold uppercase select-none">Commodity Geo Location</h1>
        <Map :custom-point="[commodity]" />
    </div>
    </app-layout>

</template>

<style scoped>
#map {
    @apply sm:w-[50%] w-full h-screen z-0;
}
</style>
