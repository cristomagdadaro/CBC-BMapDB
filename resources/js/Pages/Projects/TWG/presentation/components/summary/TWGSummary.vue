<script>
import ApiService from "@/Modules/core/infrastructure/ApiService.ts";
import Chart from 'chart.js/auto';
import {Line} from "vue-chartjs";
export default {
    name: "TWGSummary",
    data() {
        return {
            totalProjects: 0,
            totalExperts: 0,
            totalServices: 0,
            totalProducts: 0,
            totalOnGoingProjects: 0,
            topExperts: 0,
            typeServices: 0,
            axios: null,
        }
    },
    methods:{
        async getSummary(){
            this.axios = new ApiService(route('api.twg.summary'));
            await this.axios.get().then(response => {
                this.totalProjects = response.data.totalProjects;
                this.totalExperts = response.data.totalExperts;
                this.totalServices = response.data.totalServices;
                this.totalProducts = response.data.totalProducts;
                this.totalOnGoingProjects = response.data.totalOnGoingProjects;
                this.topExperts = response.data.topExperts;
                this.typeServices = response.data.typeServices;
            });
        },
        initializeLineChart(cardName, title, xlabel, ylabel, labels, dataset) {
            const ctx = document.getElementById(cardName);
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels, // Replace with your array of labels
                    datasets: [{
                        label: 'Data Label', // Replace with a meaningful label
                        data: dataset, // Replace with your array of values
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2,
                        tension: 0.4 // Optional: Adds a smooth curve
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: title,
                            position: 'top'
                        },
                        legend: {
                            display: false,
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.parsed.y}`;
                                }
                            }
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        display: false,
                        x: {
                            title: {
                                display: false,
                                text: xlabel
                            },
                            grid: {
                                display: false // Hides grid lines on the x-axis
                            }
                        },
                        y: {
                            title: {
                                display: false,
                                text: ylabel // Replace with your label
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        },
        initializePieChart(cardName, title, xlabel, ylabel, labels, dataset) {
            const ctx = document.getElementById(cardName);
            const chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: title,
                        data: dataset,
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
                    plugins: {
                        title: {
                            display: true,
                            text: title,
                            position: 'top'
                        },
                        legend: {
                            display: true,
                            position: 'bottom',
                        },
                        tooltip: {
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed;
                                }
                            },
                        },
                    },
                    parsing: {
                        key: 'nested.value',
                    }
                }
            });
        },
        getKeys(data){
            return Object.keys(data);
        },
        getValues(data){
            return Object.values(data);
        }
    },
    async mounted(){
        await this.getSummary();
        //this.initializeLineChart('chartExperts', 'No. Projects', 'Experts', 'No. Projects', this.getKeys(this.topExperts), this.getValues(this.topExperts));
        this.initializePieChart('chartProjects','Status','Status', 'Projects', this.getKeys(this.totalOnGoingProjects), this.getValues(this.totalOnGoingProjects));
        this.initializePieChart('chartServices', 'Type', 'Type', 'Services', this.getKeys(this.typeServices), this.getValues(this.typeServices));
        //this.initializePieChart('chartProducts');
    },
}
</script>

<template>
    <div class="flex flex-col sm:gap-5 gap-1 select-none">
        <h1 class="h1 text-center font-semibold text-subtitle">Summary</h1>
        <div class="grid grid-cols-4 grid-rows-1 gap-5">
            <div>
                <div class="text-center">
                    <p class="text-subtitle">{{ totalExperts }}</p>
                    <h1 class="h1 text-center font-semibold uppercase border-red-500 border-1">Experts</h1>
                </div>
                <div class="flex justify-center hidden">
                    <canvas id="chartExperts" role="img"></canvas>
                </div>
            </div>
            <div>
                <div class="text-center">
                    <p class="text-subtitle">{{ totalProjects }}</p>
                    <h1 class="h1 text-center font-semibold uppercase border-red-500 border-1">Projects</h1>
                </div>
                <div class="flex justify-center hidden">
                    <canvas id="chartProjects" role="img"></canvas>
                </div>
            </div>
            <div>
                <div class="text-center">
                    <p class="text-subtitle">{{ totalProducts }}</p>
                    <h1 class="h1 text-center font-semibold uppercase border-red-500 border-1">Products</h1>
                </div>
                <div class="flex justify-center hidden">
                    <canvas id="chartProducts" role="img"></canvas>
                </div>
            </div>
            <div>
                <div class="text-center">
                    <p class="text-subtitle">{{ totalServices }}</p>
                    <h1 class="h1 text-center font-semibold uppercase border-red-500 border-1">Services</h1>
                </div>
                <div class="flex justify-center hidden">
                    <canvas id="chartServices" role="img"></canvas>
                </div>
            </div>
        </div>
    </div>

</template>
