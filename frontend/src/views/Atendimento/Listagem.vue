<template>
  <div>
    <v-card>
      <v-card-title class="headline">
        Listagem de Atendimentos
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
            class="mr-2"
            @click="editarAtendimento(item)"
          >
            mdi-pencil
          </v-icon>
          <v-icon
            small
            class="ml-2"
            text="teste"
            @click="abrirAtendimento(item)"
          >
            mdi-clipboard
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
          { text: 'Nome Paciente', value: 'nome_paciente' },
          { text: 'Telefone Paciente', value: 'telefone_paciente' },
          { text: 'Data Ligação', value: 'data_hora_ligacao' },
          { text: 'Orientação', value: 'orientacao_conduta' },
          { text: 'Isolamento', value: 'isolamento' },
          { text: 'Ações', value: 'actions', sortable: false },
        ],
        items: [],
        search: '',
        loading: false
      }
    },

    mounted() {
      this.getAtendimentos();
    },

    methods: {
      async getAtendimentos() {
        this.loading = true;

        const response = await this.axios.get('index_atendimentos/' + this.$store.getters.getDadosUser.id);
        this.loading = false;

        for(var atendimento of response.data)
        {
          const responsePaciente = await this.axios.get('pacientes/' + atendimento.paciente_id);
          atendimento.nome_paciente = responsePaciente.data.nome;
          atendimento.telefone_paciente = responsePaciente.data.telefone;
          
          atendimento.data_hora_ligacao = this.moment(atendimento.data_hora_ligacao).format("DD/MM/YYYY hh:mm:ss");

          if(atendimento.orientacao_conduta == 'manter_isolamento_domiciliar')
            atendimento.orientacao_conduta = 'Manter Isolamento Domiciliar';
          else if(atendimento.orientacao_conduta == 'encaminhar_unidade_sintomatica')
            atendimento.orientacao_conduta = 'Encaminhar paciente a uma unidade sintomática';
          else
            atendimento.orientacao_conduta = 'Encaminhar para o SAMU';

          if(atendimento.isolamento == 'nao')
            atendimento.isolamento = 'Não';
          else
            atendimento.isolamento = 'Sim';
        }

        this.items = response.data;
      },

      abrirAtendimento(item) {
        this.$router.push('/atendimento/cadastro/' + item.id + '/0');
      },

      editarAtendimento(item) {
        this.$router.push('/atendimento/cadastro/' + item.id + '/' + item.paciente_id);
      },
    }
  }
</script>