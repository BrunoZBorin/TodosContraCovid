<template>
  <div class="atendimento">
    <v-app>
      <v-container>
        <v-card>
          <v-card-title class="headline">Atendimento</v-card-title>
          <v-container>
            <v-form>
              <v-row>
                <v-col cols="4">
                  <v-text-field
                    v-model="nome"
                    :error-messages="nomeErros"
                    label="Nome"
                    required
                    @input="$v.nome.$touch()"
                    @blur="$v.nome.$touch()"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-menu
                    ref="menuDataNascimento"
                    v-model="menuDataNascimento"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="dataNascimentoFormatada"
                        :error-messages="dataNascimentoErros"
                        label="Data de Nascimento"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        required
                        @input="$v.dataNascimento.$touch()"
                        @blur="$v.dataNascimento.$touch(), dataNascimento = parseDate(dataNascimentoFormatada)"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="dataNascimento"
                      :title-date-format="formataData"
                      :max="new Date().toISOString().substr(0, 10)"
                      min="1950-01-01"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    v-model="cns"
                    :error-messages="cnsErros"
                    label="CNS"
                    v-mask="'### #### #### ####'"
                    required
                    @input="$v.cns.$touch()"
                    @blur="$v.cns.$touch()"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="3">
                  <v-text-field
                    v-model="cep"
                    :error-messages="cepErros"
                    label="CEP"
                    v-mask="'#####-###'"
                    :counter="9"
                    required
                    @input="$v.cep.$touch()"
                    @blur="$v.cep.$touch(), buscaEndereco()"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    v-model="cidade"
                    label="Cidade"
                    :loading="loadingEndereco"
                    disabled
                  ></v-text-field>
                </v-col>
                <v-col cols="1">
                  <v-text-field
                    v-model="uf"
                    label="UF"
                    :loading="loadingEndereco"
                    disabled
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    v-model="bairro"
                    label="Bairro"
                    :loading="loadingEndereco"
                    disabled
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="9">
                  <v-text-field
                    v-model="logradouro"
                    label="Logradouro"
                    :loading="loadingEndereco"
                    disabled
                  ></v-text-field>
                </v-col>
                <v-col cols="3">
                  <v-text-field
                    v-model="numero"
                    :error-messages="numeroErros"
                    label="Numero"
                    v-mask="'##########'"
                    required
                    @input="$v.numero.$touch()"
                    @blur="$v.numero.$touch()"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <v-text-field
                    v-model="primeiraAvaliacaoMedica"
                    :error-messages="primeiraAvaliacaoMedicaErros"
                    label="Primeira avaliação médica"
                    required
                    @input="$v.primeiraAvaliacaoMedica.$touch()"
                    @blur="$v.primeiraAvaliacaoMedica.$touch()"
                  ></v-text-field>
                </v-col>
                <v-col cols="3">
                  <v-text-field
                    v-model="telefone"
                    :error-messages="telefoneErros"
                    label="Telefone"
                    v-mask="'## #########'"
                    required
                    @input="$v.telefone.$touch()"
                    @blur="$v.telefone.$touch()"
                  ></v-text-field>
                </v-col>
                <v-col cols="3">
                  <v-menu
                    ref="menuDataObito"
                    v-model="menuDataObito"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="dataObitoFormatada"
                        label="Óbito"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        @blur="dataObito = parseDate(dataObitoFormatada)"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="dataObito"
                      :max="new Date().toISOString().substr(0, 10)"
                      min="1950-01-01"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-menu
                    ref="menuDataInicioSintomas"
                    v-model="menuDataInicioSintomas"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="dataInicioSintomasFormatada"
                        label="Data de Início dos Sintomas"
                        prepend-icon="event"
                        readonly
                        :error-messages="dataInicioSintomasErros"
                        v-bind="attrs"
                        v-on="on"
                        required
                        @input="$v.dataInicioSintomas.$touch()"
                        @blur="$v.dataInicioSintomas.$touch(), dataInicioSintomas = parseDate(dataInicioSintomasFormatada)"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="dataInicioSintomas"
                      :max="new Date().toISOString().substr(0, 10)"
                      min="1950-01-01"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="4">
                  <v-menu
                    ref="menuDataColetaExame"
                    v-model="menuDataColetaExame"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="dataColetaExameFormatada"
                        label="Data da Coleta do Exame"
                        prepend-icon="event"
                        readonly
                        :error-messages="dataColetaExameErros"
                        v-bind="attrs"
                        v-on="on"
                        required
                        @input="$v.dataColetaExame.$touch()"
                        @blur="$v.dataColetaExame.$touch(), dataColetaExame = parseDate(dataColetaExameFormatada)"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="dataColetaExame"
                      :max="new Date().toISOString().substr(0, 10)"
                      min="1950-01-01"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="4">
                  <v-menu
                    ref="menuDataFimIsolamento"
                    v-model="menuDataFimIsolamento"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="dataFimIsolamentoFormatada"
                        label="Isolamento até"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        disabled
                        @blur="dataFimIsolamento = parseDate(dataFimIsolamentoFormatada)"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="dataFimIsolamento"
                      :max="new Date().toISOString().substr(0, 10)"
                      min="1950-01-01"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-select
                    v-model="local"
                    :items="unidadesSintomaticas"
                    :error-messages="localErros"
                    label="Local"
                    item-text="nome"
                    item-value="id"
                    required
                    @change="$v.local.$touch()"
                    @blur="$v.local.$touch()"
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="tipoConvenio"
                    :items="['SUS', 'Particular']"
                    :error-messages="tipoConvenioErros"
                    label="Tipo de Convênio"
                    required
                    @change="$v.tipoConvenio.$touch()"
                    @blur="$v.tipoConvenio.$touch()"
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="unidadeReferencia"
                    :items="unidadesReferencia"
                    :error-messages="unidadeReferenciaErros"
                    label="Unidade de Referência"
                    item-text="nome"
                    item-value="id"
                    required
                    @change="$v.unidadeReferencia.$touch()"
                    @blur="$v.unidadeReferencia.$touch()"
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-select
                    v-model="tipoExame"
                    :items="['PCR-RT', 'Sorologia', 'Teste Rápido']"
                    :error-messages="tipoExameErros"
                    label="Tipo de Exame"
                    required
                    @change="$v.tipoExame.$touch()"
                    @blur="$v.tipoExame.$touch()"
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-menu
                    ref="menuDataResultado"
                    v-model="menuDataResultado"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="dataResultadoFormatada"
                        label="Data do Resultado"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        @blur="dataResultado = parseDate(dataResultadoFormatada)"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="dataResultado"
                      :max="new Date().toISOString().substr(0, 10)"
                      min="1950-01-01"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="resultadoExame"
                    :items="['Positivo', 'Negativo', 'Aguardando Resultado']"
                    :error-messages="resultadoExameErros"
                    label="Resultado do Exame"
                    required
                    @change="$v.resultadoExame.$touch()"
                    @blur="$v.resultadoExame.$touch()"
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-select
                    v-model="grupoRisco"
                    :items="['Sim', 'Não']"
                    :error-messages="grupoRiscoErros"
                    label="Grupo de Risco"
                    required
                    @change="$v.grupoRisco.$touch()"
                    @blur="$v.grupoRisco.$touch()"
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-menu>
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="dataLigacao"
                        label="Data da Ligação"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        disabled
                      ></v-text-field>
                    </template>
                  </v-menu>
                </v-col>
                <v-col cols="4">
                  <v-menu>
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="horaLigacao"
                        label="Hora da Ligação"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        disabled
                      ></v-text-field>
                    </template>
                  </v-menu>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-select
                    v-model="comorbidade"
                    :items="comorbidades"
                    label="Comorbidades"
                    item-text="nome"
                    multiple
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-select
                    v-model="emIsolamento"
                    :items="['Sim', 'Não']"
                    :error-messages="emIsolamentoErros"
                    label="Em Isolamento"
                    required
                    @change="$v.emIsolamento.$touch()"
                    @blur="$v.emIsolamento.$touch()"
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="orientacao"
                    :items="['Bem', 'Confuso', 'Sonolento']"
                    :error-messages="orientacaoErros"
                    label="Orientação"
                    required
                    @change="$v.orientacao.$touch()"
                    @blur="$v.orientacao.$touch()"
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="apetite"
                    :items="['Bom', 'Diminuído', 'Anorético']"
                    :error-messages="apetiteErros"
                    label="Apetite"
                    required
                    @change="$v.apetite.$touch()"
                    @blur="$v.apetite.$touch()"
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-select
                    v-model="sinal"
                    :items="sinais"
                    label="Sinais"
                    item-text="nome"
                    multiple
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-select
                    v-model="febre"
                    :items="['Ausente', 'Um pico baixo', 'Febre persistente']"
                    :error-messages="febreErros"
                    label="Febre"
                    required
                    @change="$v.febre.$touch()"
                    @blur="$v.febre.$touch()"
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="tosse"
                    :items="['Ausente', 'Consegue falar sem tossir', 'Não completa uma frase sem tossir']"
                    :error-messages="tosseErros"
                    label="Tosse"
                    required
                    @change="$v.tosse.$touch()"
                    @blur="$v.tosse.$touch()"
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="faltaArCansaco"
                    :items="['Bom', 'Diminuído', 'Anorético']"
                    :error-messages="faltaArCansacoErros"
                    label="Falta de Ar/Cansaço"
                    required
                    @change="$v.faltaArCansaco.$touch()"
                    @blur="$v.faltaArCansaco.$touch()"
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <template>
                    <v-data-table
                      :headers="headers"
                      :items="familiares"
                      sort-by="calories"
                      class="elevation-1"
                      :items-per-page="5"
                    >
                      <template v-slot:top>
                        <v-toolbar flat color="white">
                          <v-toolbar-title>Familiares</v-toolbar-title>
                          <v-divider
                            class="mx-4"
                            inset
                            vertical
                          ></v-divider>
                          <v-spacer></v-spacer>
                          <v-dialog v-model="dialog" max-width="500px">
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn
                                color="success"
                                dark
                                class="mb-2"
                                v-bind="attrs"
                                v-on="on"
                              >Novo Familiar</v-btn>
                            </template>
                            <v-card>
                              <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                              </v-card-title>

                              <v-card-text>
                                <v-container>
                                  <v-row>
                                    <v-col cols="12">
                                      <v-text-field
                                        v-model="nomeFamiliar"
                                        label="Nome Completo"
                                      ></v-text-field>
                                    </v-col>
                                  </v-row>
                                  <v-row>
                                    <v-col cols="6">
                                      <v-select
                                        v-model="situacaoFamiliar"
                                        :items="['Sintomático', 'Assintomático']"
                                        label="Situação"
                                      ></v-select>
                                    </v-col>
                                    <v-col cols="6">
                                      <v-select
                                        v-model="exameFamiliar"
                                        :items="['Positivo', 'Negativo', 'Aguardando Resultado']"
                                        label="Exame"
                                      ></v-select>
                                    </v-col>
                                  </v-row>
                                </v-container>
                              </v-card-text>

                              <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
                                <v-btn color="blue darken-1" text @click="save">Salvar</v-btn>
                              </v-card-actions>
                            </v-card>
                          </v-dialog>
                        </v-toolbar>
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
                      <template v-slot:no-data>
                        <v-btn color="primary" @click="initialize">Resetar</v-btn>
                      </template>
                    </v-data-table>
                  </template>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-textarea
                    v-model="observacaoGeral"
                    label="Observações Gerais"
                    :error-messages="observacaoGeralErros"
                    :counter="250"
                    @change="$v.observacaoGeral.$touch()"
                    @blur="$v.observacaoGeral.$touch()"
                  ></v-textarea>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-textarea
                    v-model="conduta"
                    label="Orientação / Conduta"
                    filled
                    auto-grow
                    disabled
                  ></v-textarea>
                </v-col>
              </v-row>

              <v-btn class="mr-4 mt-2" color="success" @click="registrar">Registrar</v-btn>
            </v-form>
          </v-container>
        </v-card>
      </v-container>
    </v-app>
  </div>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, maxLength, minLength } from 'vuelidate/lib/validators'

export default {
  name: "Atendimento",

  components: {
  },

  mixins: [validationMixin],

  validations: {
    nome: { required },
    dataNascimento: { required },
    cns: { required },
    cep: { required, minLength: minLength(9), maxLength: maxLength(9)  },
    numero: { required },
    primeiraAvaliacaoMedica: { required },
    telefone: { required },
    dataInicioSintomas: { required },
    dataColetaExame: { required },
    local: { required },
    tipoConvenio: { required },
    unidadeReferencia: { required },
    tipoExame: { required },
    resultadoExame: { required },
    grupoRisco: { required },
    emIsolamento: { required },
    orientacao: { required },
    apetite: { required },
    febre: { required },
    tosse: { required },
    faltaArCansaco: { required },
    observacaoGeral: { maxLength: maxLength(250)  },
  },

  data: () => ({
    email: '',
    select: null,
    checkbox: false,
    menuDataNascimento: false,
    menuDataObito: false,
    menuDataInicioSintomas: false,
    menuDataColetaExame: false,
    menuDataFimIsolamento: false,
    menuDataResultado: false,
    nome: '',
    dataNascimento: null,
    dataNascimentoFormatada: null,
    cns: '',
    cep: '',
    logradouro: '',
    bairro: '',
    cidade: '',
    uf: '',
    numero: '',
    telefone: '',
    dataObito: null,
    dataObitoFormatada: null,
    primeiraAvaliacaoMedica: '',
    dataFimIsolamento: null,
    dataFimIsolamentoFormatada: null,
    dataInicioSintomas: null,
    dataInicioSintomasFormatada: null,
    dataColetaExame: null,
    dataColetaExameFormatada: null,
    local: '',
    tipoConvenio: '',
    unidadeReferencia: '',
    tipoExame: '',
    dataResultado: null,
    dataResultadoFormatada: null,
    resultadoExame: '',
    grupoRisco: '',
    dataLigacao: new Date().toLocaleString("pt-BR", {timeZone: "America/Sao_Paulo"}).slice(0, 10),
    horaLigacao: new Date().toLocaleString("pt-BR", {timeZone: "America/Sao_Paulo"}).slice(11),
    comorbidade: '',
    emIsolamento: '',
    orientacao: '',
    apetite: '',
    sinal: '',
    febre: '',
    tosse: '',
    faltaArCansaco: '',
    dialog: false,
    headers: [
      { text: 'Nome', value: 'nome' },
      { text: 'Situação', value: 'sintomatico' },
      { text: 'Exame', value: 'exame' },
      { text: '', value: 'actions', sortable: false }
    ],
    familiares: [],
    editedIndex: -1,
    defaultItem: {
      nome: '',
      sintomatico: null,
      exame: null
    },
    familiarSelecionado: [],
    nomeFamiliar: '',
    situacaoFamiliar: '',
    exameFamiliar: '',
    observacaoGeral: '',
    conduta: '',
    loadingEndereco: false,
    unidadesSintomaticas: [],
    unidadesReferencia: [],
    comorbidades: [],
    sinais: []
  }),

  mounted() {
    if(this.$route.params.paciente > 0)
    {
      this.carregaPaciente(this.$route.params.paciente);
    }

    this.carregaUnidadesReferencia();
    this.carregaUnidadesSintomaticas();
    this.carregaComorbidades();
    this.carregaSinais();
  },

  computed: {
      nomeErros () {
        const errors = []
        if (!this.$v.nome.$dirty) return errors
        !this.$v.nome.required && errors.push('O nome é necessário.')
        return errors
      },
      dataNascimentoErros () {
        const errors = []
        if (!this.$v.dataNascimento.$dirty) return errors
        !this.$v.dataNascimento.required && errors.push('A data de nascimento é necessária.')
        return errors
      },
      cnsErros () {
        const errors = []
        if (!this.$v.cns.$dirty) return errors
        !this.$v.cns.required && errors.push('O CNS é necessário.')
        return errors
      },
      cepErros () {
        const errors = []
        if (!this.$v.cep.$dirty) return errors
        !this.$v.cep.minLength && errors.push('Informe no minimo 8 numeros.')
        !this.$v.cep.maxLength && errors.push('Informe no maximo 8 numeros.')
        !this.$v.cep.required && errors.push('O CEP é necessário.')
        return errors
      },
      numeroErros () {
        const errors = []
        if (!this.$v.numero.$dirty) return errors
        !this.$v.numero.required && errors.push('O numero é necessário.')
        return errors
      },
      primeiraAvaliacaoMedicaErros () {
        const errors = []
        if (!this.$v.primeiraAvaliacaoMedica.$dirty) return errors
        !this.$v.primeiraAvaliacaoMedica.required && errors.push('A avaliação médica é necessária.')
        return errors
      },
      telefoneErros () {
        const errors = []
        if (!this.$v.telefone.$dirty) return errors
        !this.$v.telefone.required && errors.push('O telefone é necessário.')
        return errors
      },
      dataInicioSintomasErros () {
        const errors = []
        if (!this.$v.dataInicioSintomas.$dirty) return errors
        !this.$v.dataInicioSintomas.required && errors.push('A data de início dos sintomas é necessária.')
        return errors
      },
      dataColetaExameErros () {
        const errors = []
        if (!this.$v.dataColetaExame.$dirty) return errors
        !this.$v.dataColetaExame.required && errors.push('A data de coleta do exame é necessária.')
        return errors
      },
      localErros () {
        const errors = []
        if (!this.$v.local.$dirty) return errors
        !this.$v.local.required && errors.push('O local é necessário.')
        return errors
      },
      tipoConvenioErros () {
        const errors = []
        if (!this.$v.tipoConvenio.$dirty) return errors
        !this.$v.tipoConvenio.required && errors.push('O tipo de convênio é necessário.')
        return errors
      },
      unidadeReferenciaErros () {
        const errors = []
        if (!this.$v.unidadeReferencia.$dirty) return errors
        !this.$v.unidadeReferencia.required && errors.push('A unidade de referência é necessária.')
        return errors
      },
      tipoExameErros () {
        const errors = []
        if (!this.$v.tipoExame.$dirty) return errors
        !this.$v.tipoExame.required && errors.push('O tipo de exame é necessário.')
        return errors
      },
      resultadoExameErros () {
        const errors = []
        if (!this.$v.resultadoExame.$dirty) return errors
        !this.$v.resultadoExame.required && errors.push('O resultado do exame é necessário.')
        return errors
      },
      grupoRiscoErros () {
        const errors = []
        if (!this.$v.grupoRisco.$dirty) return errors
        !this.$v.grupoRisco.required && errors.push('O grupo de risco é necessário.')
        return errors
      },
      emIsolamentoErros () {
        const errors = []
        if (!this.$v.emIsolamento.$dirty) return errors
        !this.$v.emIsolamento.required && errors.push('A situação de isolamento é necessária.')
        return errors
      },
      orientacaoErros () {
        const errors = []
        if (!this.$v.orientacao.$dirty) return errors
        !this.$v.orientacao.required && errors.push('A situação de orientação é necessária.')
        return errors
      },
      apetiteErros () {
        const errors = []
        if (!this.$v.apetite.$dirty) return errors
        !this.$v.apetite.required && errors.push('A situação do apetite é necessária.')
        return errors
      },
      febreErros () {
        const errors = []
        if (!this.$v.febre.$dirty) return errors
        !this.$v.febre.required && errors.push('A situação de febre é necessária.')
        return errors
      },
      tosseErros () {
        const errors = []
        if (!this.$v.tosse.$dirty) return errors
        !this.$v.tosse.required && errors.push('A situação de tosse é necessária.')
        return errors
      },
      faltaArCansacoErros () {
        const errors = []
        if (!this.$v.faltaArCansaco.$dirty) return errors
        !this.$v.faltaArCansaco.required && errors.push('A situação da falta de ar ou cansaço é necessária.')
        return errors
      },
      formTitle () {
        return this.editedIndex === -1 ? 'Novo Familiar' : 'Editar Familiar'
      },
      observacaoGeralErros () {
        const errors = []
        if (!this.$v.observacaoGeral.$dirty) return errors
        !this.$v.observacaoGeral.maxLength && errors.push('Informe no máximo 250 caracteres.')
        return errors
      },
    },

    methods: {
      async carregaPaciente(idPaciente){
        let _this = this;
        await this.axios.get('/paciente', { params: { id_paciente: idPaciente}})
        .then(function (response) {
          _this.unidadesReferencia = response.data;
        })
        .catch(function (error) {
          console.log(error);
        })
      },

      formataData(value){
        return this.moment(value).format("DD/MM/YYYY");
      },

      async carregaUnidadesReferencia(){
        let _this = this;
        await this.axios.get('/unidades_saude')
        .then(function (response) {
          _this.unidadesReferencia = response.data;
        })
        .catch(function (error) {
          console.log(error);
        })
      },

      async carregaUnidadesSintomaticas(){
        let _this = this;
        await this.axios.get('/unidades_sintomaticas')
        .then(function (response) {
          _this.unidadesSintomaticas = response.data;
        })
        .catch(function (error) {
          console.log(error);
        })
      },

      async carregaComorbidades(){
        let _this = this;
        await this.axios.get('/comorbidades')
        .then(function (response) {
          _this.comorbidades = response.data;
        })
        .catch(function (error) {
          console.log(error);
        })
      },

      async carregaSinais(){
        let _this = this;
        await this.axios.get('/sinais')
        .then(function (response) {
          _this.sinais = response.data;
        })
        .catch(function (error) {
          console.log(error);
        })
      },

      async buscaEndereco(){
        if (this.$v.cep.$invalid)
          return;

        this.loadingEndereco = true;

        let _this = this;
        await this.axios.get('/cep', { params: { cep: this.cep.replace("-", "") } })
        .then(function (response) {
          _this.cidade = response.data.cidade;
          _this.uf = response.data.uf;
          _this.bairro = response.data.bairro;
          _this.logradouro = response.data.logradouro;
        })
        .catch(function (error) {
          console.log(error);
        })

        this.loadingEndereco = false;
      },

      async registrar () {
        this.$v.$touch()

        if(this.$v.$invalid)
          return;

        let parametros = {
          paciente_cep: this.cep,
          paciente_nome: this.nome,
          paciente_numero: this.numero,
          paciente_telefone: this.telefone,
          paciente_cns: this.cns,
          paciente_data_nasc: this.dataNascimento,
          paciente_obito: this.dataObito,
          paciente_primeira_avaliacao_medica: this.primeiraAvaliacaoMedica,
          paciente_isolamento_ate: this.dataFimIsolamento.format('YYYY-MM-DD'),
          paciente_data_inicio_sintomas: this.dataInicioSintomas,
          paciente_data_coleta_exames: this.dataColetaExame,
          paciente_unidade_sintomatica_id: this.local,
          paciente_convenio: this.tipoConvenio,
          paciente_unidade_saude_id: this.unidadeReferencia,
          paciente_tipo_exame: this.tipoExame,
          paciente_data_resultado: this.dataResultado,
          paciente_resultado_exame: this.resultadoExame,
          paciente_grupo_risco: this.grupoRisco,
          atendimento_data_hora_ligacao: this.dataLigacao.split('/').reverse().join('-') + " " + this.horaLigacao,
          atendimento_isolamento: this.emIsolamento,
          atendimento_orientacao: this.orientacao,
          atendimento_apetite: this.apetite,
          atendimento_febre: this.febre,
          atendimento_tosse: this.tosse,
          atendimento_falta_de_ar: this.faltaArCansaco,
          atendimento_observacoes_gerais: this.observacaoGeral,
          atendimento_orientacao_conduta: this.conduta,
          atendimento_paciente_id: this.$route.params.paciente,
          atendimento_usuario_id: this.$store.getters.getDadosUser.id,
          familiares: this.familiares,
          comorbidades: this.comorbidades,
          sinais: this.sinais
        }

        console.log(parametros)

        let _this = this;
        await this.axios.post('/primeiro_cadastro', { parametros })
        .then(function (response) {
          if(response.status == 200)
            _this.$router.push('home')
          else
            alert("Erro ao registrar atendimento!")
        })
        .catch(function (error) {
          console.log(error);
        })
      },

      initialize () {
        this.familiares = [];
      },

      editItem (item) {
        this.editedIndex = this.familiares.indexOf(item)

        this.nomeFamiliar = this.familiares[this.editedIndex].nome;
        this.situacaoFamiliar = this.familiares[this.editedIndex].sintomatico;
        this.exameFamiliar = this.familiares[this.editedIndex].exame;

        this.dialog = true
      },

      deleteItem (item) {
        const index = this.familiares.indexOf(item)
        confirm('Você tem certeza que deseja excluir este familiar?') && this.familiares.splice(index, 1)
      },

      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.$v.nomeFamiliar.$reset();
          this.$v.situacaoFamiliar.$reset();
          this.$v.exameFamiliar.$reset();
          
          this.nomeFamiliar = '';
          this.situacaoFamiliar = '';
          this.exameFamiliar = '';

          this.editedIndex = -1;
        })
      },

      save () {
        if (this.$v.nomeFamiliar.$invalid || this.$v.situacaoFamiliar.$invalid || this.$v.exameFamiliar.$invalid)
          return;
        let editedItem = {
          nome: this.nomeFamiliar,
          sintomatico: this.situacaoFamiliar,
          exame: this.exameFamiliar
        }

        if (this.editedIndex > -1) {
          Object.assign(this.familiares[this.editedIndex], editedItem)
        } else {
          this.familiares.push(editedItem)
        }
        this.close()
      },

      parseDate (date) {
        if (!date) return null

        const [month, day, year] = date.split('/')
        return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
      },
    },

    watch: {
      menuDataNascimento (val) {
        val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR'))
      },
      dialog (val) {
        val || this.close()
      },

      dataNascimento (val) {
        this.dataNascimentoFormatada = this.formataData(this.dataNascimento)
      },

      dataObito (val) {
        this.dataObitoFormatada = this.formataData(this.dataObito)
      },

      dataInicioSintomas (val) {
        this.dataInicioSintomasFormatada = this.formataData(this.dataInicioSintomas)
      },

      dataColetaExame (val) {
        this.dataFimIsolamento = this.moment(this.dataColetaExame).add(14, 'days')
        this.dataColetaExameFormatada = this.formataData(this.dataColetaExame)
      },

      dataFimIsolamento (val) {
        this.dataFimIsolamentoFormatada = this.formataData(this.dataFimIsolamento)
      },

      dataResultado (val) {
        this.dataResultadoFormatada = this.formataData(this.dataResultado)
      },
    }
};
</script>
