<template>
  <v-dialog v-model="dialog" persistent max-width="290">
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        class="mr-4 mt-2"
        color="success"
        dark
        v-bind="attrs"
        v-on="on"
      >
        {{buttonTitle}}
      </v-btn>
    </template>
    <v-card>
      <v-card-title class="headline">{{title}}</v-card-title>
      <v-card-text>
          {{text}}
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="green darken-1" text @click="dialog = false">Não</v-btn>
        <v-btn color="green darken-1" text @click="dialog = false; $emit('next')">Sim</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    props: ['buttonTitle', 'title', 'text'],

    data () {
      return {
        dialog: false,
      }
    },
  }
</script>