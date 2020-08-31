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
      this.load();
    },

    methods: {
      async load() {
        this.loading = true;

        const response = await this.axios.get('unidades_saude')
        .catch(err => {
          console.log(err);
          this.$swal(
            'Erro',
            'Ocorreu um problema inesperado!',
            'error'
          );
        });
        
        this.loading = false;
        this.items = response.data;
      },

      editItem(item) {
        this.$router.push(`/unidades_saude/cadastro/${item.id}`)
      },

      async deleteItem(item) {
        const result = await this.$swal({
          title: 'Tem ceteza que deseja continuar?',
          text: "Você não poderá reverter isso!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sim',
          cancelButtonText: 'Não'
        });

        if (!result.value) return;

        this.axios.delete(`unidades_saude/${item.id}`)
        .then(() => {
          this.$swal(
            'Deletado!',
            'Operação realizada com sucesso!',
            'success'
          );

          this.load();
        })
        .catch(err => {
          console.log(err);
          this.$swal(
            'Erro',
            'Ocorreu um problema inesperado!',
            'error'
          );
        });
      }
    }
  }
</script>