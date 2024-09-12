<script>
import ChartJsMixin from "@/Pages/Projects/BreedersMap/presentation/components/summary/infrastructure/ChartJsMixin.js";

export default {
    name: "LineGraph",
    mixins: [ChartJsMixin],
    props: {
        data: {
            type: Object,
            required: true
        },
    },
    computed: {
        linechartOptions() {
            return {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        display: false,
                        onClick: (e, legendItem, legend) => {
                            // if a hidden legend is clicked, show all
                            if (legendItem.hidden)
                                legend.chart.data.datasets.forEach((dataset, i) => {
                                    dataset.hidden = false;
                                });
                            else
                                legend.chart.data.datasets.forEach((dataset, i) => {
                                    dataset.hidden = legendItem.text !== dataset.label;
                                });

                            legend.chart.update();
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                if (!tooltipItem.raw)
                                    return `There are no entries`;
                                else if (tooltipItem.raw > 1)
                                    return `Population of ${tooltipItem.raw}`;
                                return `Population of ${tooltipItem.raw}`;
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
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Population per variety',
                            font: {
                                size: 20,
                            },
                        },
                        grid: {
                            display: false
                        },
                    },
                    y: {
                        beginAtZero: false,
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
    },
}
</script>

<template>
    <Line
        :options="linechartOptions"
        :data="data"
    />
</template>

<style scoped>

</style>
