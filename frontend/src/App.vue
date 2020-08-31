<template>
  <v-app>
      <loading :active.sync="$store.state.loading" loader="Bars"></loading>
      <router-view/>
  </v-app>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  name: "App",
  
  components: {
    Loading
  },

  mounted() {
    this.$router.push("login");
  }
};
</script>