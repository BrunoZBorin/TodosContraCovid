<template>
  <bar-chart v-if="handleChart" :chart-data="datacollection" :options="options"></bar-chart>
</template>

<script>
  import BarChart from '../BarChart';
  import axios from '@/services/apiDashboard';

  export default {
    props: ['items'],

    components: {
      BarChart
    },

    data() {
      return {
        handleChart: false,
        datacollection: null,
        options: {
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
        const { data } = await axios.get('pacientes_idades')
        .catch(err => {
          console.log(err);
          this.$swal(
            'Erro',
            'Ocorreu um problema inesperado!',
            'error'
          );
        });

        data[0] = this.getRandomInt();
        data[1] = this.getRandomInt();
        data[2] = this.getRandomInt();

        this.datacollection = {
            labels: ['Menos de 30', 'Menos de 60', 'Mais de 60'],
            datasets: [{
              backgroundColor: ['green', 'orange', 'red'],
              data: [data[0], data[1], data[2]]
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