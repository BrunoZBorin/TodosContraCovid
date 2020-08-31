<template>
  <v-card class="small">
    <v-card-title>
      {{title}}
    </v-card-title>
    <v-card-text>
      <bar-chart :chart-data="datacollection"></bar-chart>
    </v-card-text>
    <v-card-actions>
      <button @click="fillData()">Randomize</button>
    </v-card-actions>
  </v-card>
</template>

<script>
  import BarChart from './BarChart';

  export default {
    props: ['title', 'color'],

    components: {
      BarChart
    },
    
    data () {
      return {
        datacollection: null
      }
    },

    mounted () {
      this.updateData();
    },

    methods: {

      updateData() {
        this.fillData();

        setInterval(() => {
          this.fillData();
        }, 1000);
      },

      fillData () {
        this.datacollection = {
          labels: [this.getRandomInt(), this.getRandomInt()],
          datasets: [
            {
              label: 'Data One',
              backgroundColor: this.color,
              data: [this.getRandomInt(), this.getRandomInt()]
            }, {
              label: 'Data One',
              backgroundColor: this.color,
              data: [this.getRandomInt(), this.getRandomInt()]
            }
          ]
        }
      },
      getRandomInt () {
        return Math.floor(Math.random() * (50 - 5 + 1)) + 5
      }
    },
  }
</script>

<style>
  .small {
    max-width: 80%;
  }
</style>