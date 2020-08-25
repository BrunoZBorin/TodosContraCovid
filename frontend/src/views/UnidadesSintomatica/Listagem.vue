<template>
  <div>
    <v-card>
    <v-card-title class="headline">
        Listagem das Unidades de Saúde
        <v-spacer></v-spacer>
        <v-text-field 
        v-model="search"
        append-icon="mdi-magnify"
        label="Pesquisar"
        single-line
        hide-details
        >
        </v-text-field>
    </v-card-title>
    <v-data-table 
        :headers="headers" 
        :items="items" 
        :search="search" 
        :loading="loading"
        dense
    >
        <template v-slot:item.perfil="{ item }">
        {{ item.perfil.toUpperCase() }}
        </template>
        <template v-slot:item.actions="{ item }">
        <v-icon
            small
            class="mr-2"
            @click="editItem(item)"
        >
            mdi-pencil
        </v-icon>
        <v-icon
            small
            @click="deleteItem(item)"
        >
            mdi-delete
        </v-icon>
        </template>
    </v-data-table>
    </v-card>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        headers: [
          { text: 'ID', value: 'id' },
          { text: 'Nome', value: 'nome' },
          { text: 'Telefone', value: 'telefone' },
          { text: 'CEP', value: 'cep' },
          { text: 'Bairro', value: 'bairro' },
          { text: 'Logradouro', value: 'logradouro' },
          { text: 'Número', value: 'numero' },
          { text: 'Cidade', value: 'cidade' },
          { text: 'Estado', value: 'estado' },
          { text: 'Ações', value: 'actions', sortable: false },
        ],
        items: [],
        search: '',
        loading: false
      }
    },

    mounted() {
      this.getUsuarios();
    },

    methods: {
      async getUsuarios() {
        this.loading = true;

        const response = await this.axios.get('unidades_sintomaticas');
        this.loading = false;

        this.items = response.data;
      },

      editItem(item) {
        
      },

      deleteItem(item) {
        
      }
    }
  }
</script>