<script>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService.js";
import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder.js";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity.js";
import CommodityTable from "@/Pages/Projects/BreedersMap/presentation/components/commodity/CommodityTable.vue";
export default {
    components: {Head, CommodityTable, AppLayout},
    data() {
        return {
            id: this.$page.props.id,
            data: null,
            axiosInstance: new ApiService(route('api.breeders.show', this.$page.props.id))
        }
    },
    computed: {
        commodities() {
            return this.data['commodities'].map(commodity => new Commodity(commodity));
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
    mounted() {
        this.getDataFromAPI();
    }
}
</script>

<template>
    <Head title="Breeder's Map View" />
    <app-layout>
        <div class="min-h-screen bg-transparent min-w-full m-2 p-2">
            <div v-if="data" class="flex flex-col gap-2">
                <h1 class="h1 font-semibold uppercase select-none">Breeder Information</h1>
                <div class="border p-3 rounded">
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Breeder Identification No.: </h2>
                        <p>{{ data.id }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Breeder/Owner: </h2>
                        <p>{{ data.name }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Agency/Institution/Affiliation: </h2>
                        <p>{{ data.agency }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Affiliation Address: </h2>
                        <p>{{ data.address }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Phone: </h2>
                        <p>{{ data.phone }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Email: </h2>
                        <p>{{ data.email }}</p>
                    </div>
                </div>
                <commodity-table :params="{ filter:'breeder_id', is_exact:true, search:this.$page.props.id }" />
            </div>
        </div>
    </app-layout>
</template>

<style scoped>

</style>
