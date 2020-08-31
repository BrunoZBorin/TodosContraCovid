<template>
    <v-main>
		<v-container>
            <v-select
                v-model="unidade"
                :items="['Unidade 1', 'Unidade 2']"
                @change="handleUnidade"
                dense
                solo
                outlined
            >
            </v-select>
            <!-- <div style="display: flex;">
                <Chart v-for="item in items"
                    :key="item.title"
                    :title="item.title"
                    :color="item.color"
                />
            </div> -->
            <v-row>
                <v-col cols="12" md="6" v-for="item in items" :key="item.title">
                    <Chart
                        :title="item.title"
                        :color="item.color"
                    />
                </v-col>
            </v-row>
		</v-container>
    </v-main>
</template>

<script>
import Chart from '../components/Charts/Chart';

export default {
    components: {
        Chart
    },

    data() {
        return {
            unidade: 'Unidade 1',
            items: [
                { title: 'Casos Finalizados', color: 'green' },
                { title: 'Faixa Etária dos Pacientes', color: 'blue' },
                { title: 'Faixa Etária dos Pacientes', color: 'blue' },
                { title: 'Faixa Etária dos Pacientes', color: 'blue' },
                { title: 'Faixa Etária dos Pacientes', color: 'blue' },
            ]
        }
    },

    methods: {
        handleUnidade(event) {
            console.log(event);
        }
    }
}
</script>