<template>
    <div class="min-h-screen max-h-max bg-gradient-to-t from-[#10191c] to-[#1c4553]">
        <div class="mx-auto w-full max-w-screen-md px-4 pb-8 pt-20 flex justify-center">
            <div class="max-w-xl w-full h-full flex flex-col justify-center space-y-10">
                <h1 class="font-bold text-5xl italic text-white">
                    VALOR DE LA <span class="text-cyan-400 underline">UF</span> HOY
                </h1>
                <p class="text-lg text-neutral-300">
                    Valor hoy: {{ today }}
                </p>
                <div class="py-10 border-neutral-400 border-y">
                    <h2 class="text-lg font-bold text-white">
                        1 UF <span class="text-cyan-400">=</span> {{ ufToday.valor }} CLP
                    </h2>
                </div>
                <Chart v-if="ufObject" class="-ml-[9px] scale-[1.04]" />
            </div>
        </div>
    </div>
</template>

<script>
import Chart from './Partials/Chart.vue';
import { mapMutations, mapState } from "vuex";

export default {
    components: {
        Chart
    },
    props: {
        ufs: {
            type: Object,
            required: true,
        },
        ufToday: {
            type: Object,
            required: true,
        },
    },
    mounted() {
        this.setObject();
    },
    methods: {
        ...mapMutations(['setUfObject']),
        setObject() {
            this.setUfObject(this.ufs)
        }
    },
    computed: {
        today() {
            const currentDate = new Date();
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            const formattedDate = currentDate.toLocaleDateString('es-ES', options);
            return formattedDate
        },
        ...mapState(['ufObject']),
    },
}
</script>