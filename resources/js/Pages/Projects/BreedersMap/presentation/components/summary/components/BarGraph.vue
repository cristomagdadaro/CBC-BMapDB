<script>
import ChartJsMixin from "@/Pages/Projects/BreedersMap/presentation/components/summary/infrastructure/ChartJsMixin.js";

export default {
    name: "BarGraph",
    mixins: [ChartJsMixin],
    props: {
        data: {
            type: Object,
            required: true
        },
    },
    computed: {
        chartOptions() {
            return {
                responsive: true,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        position: 'top',
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                if (!tooltipItem.raw)
                                    return `There are no entries`;
                                else if (tooltipItem.raw > 1)
                                    return `There are ${tooltipItem.raw} entries`;
                                return `There is ${tooltipItem.raw} entry`;
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
                            let percentage = (value*100 / sum).toFixed(2)+"%";
                            return percentage;
                        },
                        color: '#fff',
                        display: false,
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'No. of Commodities',
                            font: {
                                size: 20,
                            },
                        },
                        grid: {
                            display: false
                        },
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: false,
                        },
                        grid: {
                            display: false
                        },
                    },
                },
            }
        },
    }
}
</script>

<template>
    <Bar
        id="my-chart-id"
        :options="chartOptions"
        :data="data"
    />
</template>

<style scoped>

</style>
