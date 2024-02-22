<script>
import ApiService from "@/Modules/core/infrastructure/ApiService.js";

export default {
    name: "TWGSummary",
    data() {
        return {
            totalProjects: 0,
            totalExperts: 0,
            totalServices: 0,
            totalProducts: 0,
            axios: new ApiService(route('api.twg.summary'))
        }
    },
    methods:{
        getSummary(){
            this.axios.get().then(response => {
                this.totalProjects = response.data.totalProjects;
                this.totalExperts = response.data.totalExperts;
                this.totalServices = response.data.totalServices;
                this.totalProducts = response.data.totalProducts;
            });
        }
    },
    mounted(){
        this.getSummary();
    }
}
</script>

<template>
    <h1 class="h1 text-center font-semibold uppercase">Summary</h1>
    <div class="flex justify-center">
        <div class="w-1/2">
            <div class="flex justify-between">
                <div class="w-1/2">
                    <p class="text-sm font-semibold">Total Projects</p>
                    <p class="text-2xl font-semibold">{{ totalProjects }}</p>
                </div>
                <div class="w-1/2">
                    <p class="text-sm font-semibold">Total Experts</p>
                    <p class="text-2xl font-semibold">{{ totalExperts }}</p>
                </div>
            </div>
            <div class="flex justify-between">
                <div class="w-1/2">
                    <p class="text-sm font-semibold">Total Services</p>
                    <p class="text-2xl font-semibold">{{ totalServices }}</p>
                </div>
                <div class="w-1/2">
                    <p class="text-sm font-semibold">Total Products</p>
                    <p class="text-2xl font-semibold">{{ totalProducts }}</p>
                </div>
            </div>
        </div>
    </div>

</template>
