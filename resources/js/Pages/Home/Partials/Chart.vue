<template>
    <div class="flex flex-col space-y-2">
        <div class="pl-4 px-2 w-full">
            <select name="select__year__chart" class="md:w-[200px] w-full text-sm" v-model="selectedYear">
                <option v-for="year in years" :key="year" :value="year">
                    {{ year }}
                </option>
            </select>
        </div>
        <apexchart v-if="showChart" class="text-black" type="line" :options="chartOptions" :series="series"></apexchart>
    </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";
import { formatDayMonth } from "@/utils/date";
import axios from 'axios';

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
                    tickAmount: 10,
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
        dataTransform() {
            const dates = this.ufs.map(item => formatDayMonth(item.fecha)).reverse();
            const values = this.ufs.map(item => item.valor).reverse();

            this.chartOptions.xaxis.categories = dates;
            this.series[0].data = values;
            this.series[0].name = `Valor UF ${new Date().getFullYear()}`;
            this.showChart = true;
        },
        getUFbyYear(year) {
            axios.get('/getUF/' + year)
                .then(response => {
                    this.data = response.data;
                })
                .catch(error => {
                    console.error('Error al obtener datos:', error);
                });
            return this.ufs.filter(item => new Date(item.fecha).getFullYear() === year).map(item => item.valor).reverse();
        },
    },
    computed: {
        years() {
            const years = [];
            for (let year = this.currentYear; year >= 1977; year--) {
                years.push(year);
            }
            return years;
        }
    },
    watch: {
        selectedYear(newYear) {
            if (!this.showChart) return;

            this.getUFbyYear(newYear);
        }
    }
}
</script>