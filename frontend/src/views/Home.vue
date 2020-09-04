<template>
  <div v-if="this.$store.getters.getDadosUser.perfil == 'monitoramento'">
    <v-card>
      <v-card-title class="headline">
        Listagem de Ligações Diárias
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
        <template v-slot:item.actions="{ item }">
          <v-icon
            small
            class="ml-2"
            text="teste"
            @click="incluirAtendimento(item)"
          >
            mdi-plus
          </v-icon>
        </template>
      </v-data-table>
    </v-card>
  </div>
  <div v-else>
    <v-card>
      <v-container class="fill-height" fluid >
        <img 
            src="../assets/antcovid.png" 
            alt="Todos Contra Covid!" 
            width="50%"
            class="mx-auto">
      </v-container>
    </v-card>
  </div>
</template>

<script>
    export default {
			data() {
				return {
					headers: [
						{ text: 'ID', value: 'id' },
						{ text: 'Nome Paciente', value: 'nome' },
						{ text: 'Telefone Paciente', value: 'telefone' },
						{ text: 'Ações', value: 'actions', sortable: false },
					],
					items: [],
					search: '',
          loading: false,
          tempoRequisicao: 180 * 1000,
				}
			},

			mounted() {
          this.getLigacao();
          
          setInterval(() => {
            this.getLigacao();
          }, this.tempoRequisicao);
			},

      methods: {
				async getLigacao() {
					this.loading = true;

					const response = await this.axios.get('listagem_diaria/' + this.$store.getters.getDadosUser.id);
          this.loading = false;
          
          this.items = response.data[0].concat(response.data[1]);
				},

				incluirAtendimento(item) {
					this.$router.push('/atendimento/cadastro/0/' + item.id);
				},
			}
    }
</script>