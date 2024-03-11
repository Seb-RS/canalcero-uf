<template>
    <div class="flex flex-col space-y-2">
        <div class="pl-4 px-2 w-full">
            <select name="select__year__chart" class="md:w-[200px] w-full text-sm" v-model="selectedYear">
                <option v-for="year in years" :key="year" :value="year">
                    {{ year }}
                </option>
            </select>
        </div>
        <apexchart v-if="showChart" class="text-black" type="line" :options="chartOptions" :series="series" ref="chart">
        </apexchart>
    </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";
import { formatDayMonth } from "@/utils/date";
import axios from 'axios';
import { mapMutations } from "vuex";
import { mapState } from "vuex";

export default {
    components: {
        apexchart: VueApexCharts,
    },
    mounted() {
        this.dataTransform()
        this.showChart = true;
    },
    data() {
        return {
            selectedYear: new Date().getFullYear(),
            currentYear: new Date().getFullYear(),
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
                yaxis: {
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
        dataTransform(date = new Date().getFullYear()) {
            const dates = this.ufObject.map(item => formatDayMonth(item.fecha)).reverse();
            const values = this.ufObject.map(item => item.valor).reverse();

            this.chartOptions.xaxis.categories = dates;
            this.series[0].data = values;
            this.series[0].name = `Valor UF ${date}`;

            const totalItems = this.ufObject.length;
            const desiredTickCount = 20;
            const tickAmount = Math.ceil(totalItems / desiredTickCount);

            this.chartOptions.xaxis.tickAmount = tickAmount;

            if (this.$refs.chart) {
                this.$refs.chart.updateOptions({
                    xaxis: {
                        categories: dates,
                        tickAmount: tickAmount,
                    },
                });
            }
        },
        ...mapMutations(['setUfObject']),
        async getUFbyYear(year) {
            try {
                const response = await axios.get('/getUF/' + year);
                this.data = response.data;
                this.setUfObject(this.data);

                await this.$nextTick();
                this.dataTransform(year);
            } catch (error) {
                console.error('Error al obtener datos:', error);
            }
        },
    },
    computed: {
        years() {
            const years = [];
            for (let year = this.currentYear; year >= 1977; year--) {
                years.push(year);
            }
            return years;
        },
        ...mapState(['ufObject']),
    },
    watch: {
        selectedYear(newYear) {
            if (!this.showChart) return;

            this.getUFbyYear(newYear);
        }
    }
}
</script>