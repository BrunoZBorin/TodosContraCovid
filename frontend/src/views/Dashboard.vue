<template>
    <v-main>
		<v-container>
            <v-card class="blue lighten-3">
                <v-card-title>Dashboard</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col>
                            <v-card>
                                <v-card-title>Idades</v-card-title>
                                <v-card-text>
                                    <BarChart />
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col>
                            <v-card>
                                <v-card-title>Totais</v-card-title>
                                <v-card-text>
                                    <PieChart />
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
		</v-container>
    </v-main>
</template>

<script>
import BarChart from '@/components/Charts/Dashboard/BarChart';
import PieChart from '@/components/Charts/Dashboard/PieChart';

export default {
    components: {
        BarChart,
        PieChart
    },
}
</script>