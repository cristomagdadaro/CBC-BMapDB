<script>
import ChartJsMixin from "@/Pages/Projects/BreedersMap/presentation/components/summary/infrastructure/ChartJsMixin.js";

export default {
    name: "DoughnutGraph",
    mixins: [ChartJsMixin],
    props: {
        data: {
            type: Object,
            required: true
        },
    },
    computed: {
        piechartOptions() {
            return {
                responsive: true,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        position: 'bottom',
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                return `There are ${tooltipItem.raw} research studies of ${tooltipItem.label} in this slice`;
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += data;
                            });
                            // percentage of the value and the name of the commodity
                            let percentage = (value*100 / sum).toFixed(0)+"%";
                            return `${percentage} ${ctx.chart.data.labels[ctx.dataIndex]}`;
                        },
                        color: '#fff',
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            text: 'No. of Commodities',
                            display: true,
                        },
                        grid: {
                            display: false
                        },
                        display: false
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            text: 'No. of Commodities',
                            display: false,
                        },
                        grid: {
                            display: false
                        },
                        display: false
                    }
                },
            }
        },
    }
}
</script>

<template>
    <Doughnut
        id="my-chart-id"
        :options="piechartOptions"
        :data="data"
    />
</template>

<style scoped>

</style>
