<script>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService.js";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity.js";
import CommodityTable from "@/Pages/Projects/BreedersMap/presentation/components/commodity/CommodityTable.vue";
export default {
    components: {Head, CommodityTable, AppLayout},
    data() {
        return {
            id: this.$page.props.id,
            data: null,
            axiosInstance: new ApiService(route('api.commodities.show', this.$page.props.id))
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
                filter: 'breeder_id',
                search: this.$page.props.id,
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
    <div>
        <h1 class="h1 text-center font-semibold uppercase select-none">Commodities Database</h1>
        {{data}}
    </div>
    </app-layout>

</template>

<style scoped>

</style>
