<template>
    <apexchart v-if="showChart" class="text-black" type="line" :options="chartOptions" :series="series"></apexchart>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";
import { formatDayMonth } from "@/utils/date"

export default {
    components: {
        apexchart: VueApexCharts,
    },
    props: {
        ufs: {
            type: Object,
            required: true,
        },
    },
    mounted() {
        this.dataTransform()
    },
    data() {
        return {
            showChart: false,
            chartOptions: {
                chart: {
                    id: "vuechart-example",
                },
                xaxis: {
                    categories: [],
                    labels: {
                        style: {
                            colors: '#22d3ee',
                        },
                    },
                },
                colors: ['#22d3ee'],
            },
            series: [
                {
                    name: "series-1",
                    data: [],
                },
            ],
        };
    },
    methods: {
        dataTransform() {
            const dates = this.ufs.map(item => formatDayMonth(item.fecha)).reverse();
            const values = this.ufs.map(item => item.valor).reverse();

            this.chartOptions.xaxis.categories = dates;
            this.series[0].data = values;
            this.series[0].name = `Valor UF ${new Date().getFullYear()}`;
            this.showChart = true;
        },
    },
}
</script>