<template>
    <pie-chart v-if="handleChart" :chart-data="datacollection" :options="options"></pie-chart>
</template>

<script>
  import PieChart from '../PieChart';
  import axios from '@/services/apiDashboard';

  export default {
    props: ['items'],

    components: {
      PieChart
    },

    data() {
      return {
        handleChart: false,
        datacollection: null,
        options: {
          responsive: true,
          maintainAspectRatio: false,
        }
      }
    },

    mounted() {
      this.updateData();
    },

    methods: {

      updateData() {
        this.fillData();

        setInterval(async () => {
          await this.fillData();
        }, 5000);
      },

      async fillData() {
        let { data: obitos } = await axios.get('pacientes_obitos')
        .catch(err => {
          console.log(err);
          this.$swal(
            'Erro',
            'Ocorreu um problema inesperado!',
            'error'
          );
        });

        let { data: comorbidades } = await axios.get('pacientes_com_comorbidades')
        .catch(err => {
          console.log(err);
          this.$swal(
            'Erro',
            'Ocorreu um problema inesperado!',
            'error'
          );
        });

        obitos = this.getRandomInt();
        comorbidades = this.getRandomInt();

        this.datacollection = {
            labels: ['Obitos', 'Comorbidades'],
            datasets: [{
              backgroundColor: ['red', 'orange'],
              data: [obitos, comorbidades]
            }
          ],
        };

        this.handleChart = true;
      },
      getRandomInt() {
        return Math.floor(Math.random() * (50 - 5 + 1)) + 5
      }
    },
  }
</script>