<template>
    <v-main>
		<!-- <v-container> -->
            <v-card class="blue lighten-3">
                <v-card-title>Dashboard</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-card>
                                <v-card-title>Atendimentos</v-card-title>
                                <v-card-text>
                                    <bar-chart
                                        v-if="handleAtendimentos"
                                        :chart-data="dataAtendimentos"
                                        :options="optionsBar"
                                    ></bar-chart>
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-card>
                                <v-card-title>Casos</v-card-title>
                                <v-card-text>
                                    <pie-chart
                                        v-if="handleCasosFinalizadosPositivos"
                                        :chart-data="dataCasosFinalizadosPositivos"
                                        :options="optionsPie"
                                    ></pie-chart>
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-card>
                                <v-card-title>Totais</v-card-title>
                                <v-card-text>
                                    <pie-chart
                                        v-if="handleObitosComorbidades"
                                        :chart-data="dataObitosComorbidades"
                                        :options="optionsPie"
                                    ></pie-chart>
                                </v-card-text>
                            </v-card>
                        </v-col>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-card>
                                <v-card-title>Idades</v-card-title>
                                <v-card-text>
                                    <bar-chart
                                        v-if="handleIdades"
                                        :chart-data="dataIdades"
                                        :options="optionsBar"
                                    ></bar-chart>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
		<!-- </v-container> -->
    </v-main>
</template>

<script>
import BarChart from '@/components/Charts/BarChart';
import PieChart from '@/components/Charts/PieChart';

export default {
    components: {
        BarChart,
        PieChart
    },

    data() {
        return {
            optionsBar: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        gridLines: {
                            display: true
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        gridLines: {
                            display: false
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {
                    enabled: true,
                    mode: 'single',
                },
                responsive: true,
                maintainAspectRatio: false,
            },
            optionsPie: {
                responsive: true,
                maintainAspectRatio: false,
            },
            tempoRequisicao: 60 * 1000,

            handleAtendimentos: false,
            dataAtendimentos: [],

            handleCasosFinalizadosPositivos: false,
            dataCasosFinalizadosPositivos: [],

            handleObitosComorbidades: false,
            dataObitosComorbidades: [],

            handleIdades: false,
            dataIdades: [],
        }
    },

    mounted() {
        this.fillData();

        setInterval(() => {
            this.fillData();
        }, this.tempoRequisicao);
    },

    methods: {
        async fillData() {
            await this.getAtendimentos();
            await this.getCasosFinalizadosPositivos();
            await this.getObitosComorbidades();
            await this.getIdades();
        },

        getRandomInt() {
            return Math.floor(Math.random() * (50 - 5 + 1)) + 5
        },

        async getAtendimentos() {
            const { data } = await this.axios.get('atendimentos_por_data')
            .catch(err => {
                console.log(err);
                this.$swal(
                    'Erro',
                    'Ocorreu um problema inesperado!',
                    'error'
                );
            });

            this.dataAtendimentos = {
                    labels: ['Hoje', 'Semana', 'Mês'],
                    datasets: [{
                        backgroundColor: ['green', 'orange', 'red'],
                        data: [data[0], data[1], data[2]]
                    }
                ],
            };

            this.handleAtendimentos = true;
        },

        async getCasosFinalizadosPositivos() {
            let { data: finalizados } = await this.axios.get('casos_finalizados')
            .catch(err => {
                console.log(err);
                this.$swal(
                    'Erro',
                    'Ocorreu um problema inesperado!',
                    'error'
                );
            });

            let { data: positivos } = await this.axios.get('casos_positivos')
            .catch(err => {
                console.log(err);
                this.$swal(
                    'Erro',
                    'Ocorreu um problema inesperado!',
                    'error'
                );
            });

            this.dataCasosFinalizadosPositivos = {
                    labels: ['Finalizados', 'Positivos'],
                    datasets: [{
                    backgroundColor: ['green', 'red'],
                    data: [finalizados, positivos]
                    }
                ],
            };

            this.handleCasosFinalizadosPositivos = true;
        },

        async getObitosComorbidades() {
            let { data: obitos } = await this.axios.get('pacientes_obitos')
            .catch(err => {
                console.log(err);
                this.$swal(
                    'Erro',
                    'Ocorreu um problema inesperado!',
                    'error'
                );
            });

            let { data: comorbidades } = await this.axios.get('pacientes_com_comorbidades')
            .catch(err => {
                console.log(err);
                this.$swal(
                    'Erro',
                    'Ocorreu um problema inesperado!',
                    'error'
                );
            });

            this.dataObitosComorbidades = {
                labels: ['Obitos', 'Comorbidades'],
                datasets: [{
                        backgroundColor: ['red', 'orange'],
                        data: [obitos, comorbidades]
                    }
                ],
            };

            this.handleObitosComorbidades= true;
        },

        async getIdades() {
            const { data } = await this.axios.get('pacientes_idades')
            .catch(err => {
                console.log(err);
                this.$swal(
                    'Erro',
                    'Ocorreu um problema inesperado!',
                    'error'
                );
            });

            this.dataIdades = {
                labels: ['Menos de 30', 'Menos de 60', 'Mais de 60'],
                datasets: [{
                    backgroundColor: ['green', 'orange', 'red'],
                    data: [data[0], data[1], data[2]]
                    }
                ],
            };

            this.handleIdades = true;
        }
    }
}
</script>