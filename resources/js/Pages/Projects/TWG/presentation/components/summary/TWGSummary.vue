<script>
import ApiService from "@/Modules/core/infrastructure/ApiService.ts";
import Chart from 'chart.js/auto';
export default {
    name: "TWGSummary",
    data() {
        return {
            totalProjects: 0,
            totalExperts: 0,
            totalServices: 0,
            totalProducts: 0,
            totalOnGoingProjects: 0,
            axios: new ApiService(route('api.twg.summary')),
        }
    },
    methods:{
        async getSummary(){
            await this.axios.get().then(response => {
                this.totalProjects = response.data.totalProjects;
                this.totalExperts = response.data.totalExperts;
                this.totalServices = response.data.totalServices;
                this.totalProducts = response.data.totalProducts;
                this.totalOnGoingProjects = response.data.totalOnGoingProjects;
            });
        },
        async initializeChart() {
            const ctx = document.getElementById('chart');
            const chart = await new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: this.getHeaderOnGoingProjects,
                    datasets: [{
                        label: 'Projects Status',
                        data: this.getValuesOnGoingProjects,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    //show grid lines
                    /*scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },*/
                    plugins: {
                        title: {
                            display: true,
                            text: 'Projects Status'
                        },
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed + ' projects';
                                }
                            },
                        },
                    },
                    parsing: {
                        key: 'nested.value',
                    }
                }
            });
        }
    },
    computed: {
        getHeaderOnGoingProjects(){
            return Object.keys(this.totalOnGoingProjects);
        },
        getValuesOnGoingProjects(){
            return Object.values(this.totalOnGoingProjects);
        },
    },
    async mounted(){
        await this.getSummary();
        await this.initializeChart();
    },
}
</script>

<template>
    <div class="flex flex-col sm:gap-5 gap-1 select-none">
        <div>
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
        </div>
        <div>
            <h1 class="h1 text-center font-semibold uppercase">Projects Status</h1>
            <div class="flex justify-center">
                <div class="w-1/2">
                    <div class="w-1/2">
                        <canvas id="chart" role="img"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
