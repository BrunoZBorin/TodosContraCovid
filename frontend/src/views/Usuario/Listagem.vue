<template>
  <div>
    <v-card>
      <v-card-title class="headline">
        Listagem de Usuário
        <v-spacer></v-spacer>
        <v-text-field v-model="search" append-icon="mdi-magnify" label="Pesquisar" single-line hide-details>
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
          { text: 'E-mail', value: 'email' },
          { text: 'Perfil', value: 'perfil' },
          { text: 'Unidade de Saúde', value: 'unidade_nome' },
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

        const response = await this.axios.get('users')
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
        this.$router.push(`/usuario/cadastro/${item.id}`)
      },

      async deleteItem(item) {
        if(this.$store.state.dadosUser.id === item.id) {
          this.$swal(
            'Atenção!',
            'Você não pode deletar o seu próprio usuário!',
            'warning'
          )
          return;
        }

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

        this.axios.delete(`users/${item.id}`)
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