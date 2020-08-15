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
                        v-model="dataNascimento"
                        label="Data de Nascimento"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        @input="$v.dataNascimento.$touch()"
                        @blur="$v.dataNascimento.$touch()"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="dataNascimento"
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
                        v-model="dataObito"
                        label="Óbito"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        v-on="on"
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
                        v-model="dataInicioSintomas"
                        label="Data de Início dos Sintomas"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        v-on="on"
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
                        v-model="dataColetaExame"
                        label="Data da Coleta do Exame"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        v-on="on"
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
                        v-model="dataFimIsolamento"
                        label="Isolamento até"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        disabled
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
                        v-model="dataResultado"
                        label="Data do Resultado"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        v-on="on"
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
                <v-subheader>Familiares</v-subheader>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-text-field
                    v-model="nomeFamiliar"
                    :error-messages="nomeFamiliarErros"
                    label="Nome Completo"
                    required
                    @change="$v.nomeFamiliar.$touch()"
                    @blur="$v.nomeFamiliar.$touch()"
                  ></v-text-field>
                </v-col>
                <v-col cols="3">
                  <v-select
                    v-model="situacaoFamiliar"
                    :items="['Sintomático', 'Assintomático']"
                    :error-messages="situacaoFamiliarErros"
                    label="Situação"
                    required
                    @change="$v.situacaoFamiliar.$touch()"
                    @blur="$v.situacaoFamiliar.$touch()"
                  ></v-select>
                </v-col>
                <v-col cols="3">
                  <v-select
                    v-model="exameFamiliar"
                    :items="['Positivo', 'Negativo', 'Aguardando Resultado']"
                    :error-messages="exameFamiliarErros"
                    label="Exame"
                    required
                    @change="$v.exameFamiliar.$touch()"
                    @blur="$v.exameFamiliar.$touch()"
                  ></v-select>
                </v-col>
                <v-col cols="2">
                  <v-btn
                    class="ma-2"
                    color="success"
                    @click="adicionarFamiliar"
                  >
                    Adicionar
                  </v-btn>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-simple-table dense>
                    <template v-slot:default>
                      <thead>
                        <tr>
                          <th class="text-left">Nome</th>
                          <th class="text-left">Situação</th>
                          <th class="text-left">Exame</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="item in situacaoFamilia" :key="item.nome">
                          <td>{{ item.nome }}</td>
                          <td>{{ item.sintomatico }}</td>
                          <td>{{ item.exame }}</td>
                        </tr>
                      </tbody>
                    </template>
                  </v-simple-table>
                </v-col>
              </v-row>

              <v-btn class="mr-4 mt-2" color="primary" @click="submit">Registrar</v-btn>
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
    nomeFamiliar: { required },
    situacaoFamiliar: { required },
    exameFamiliar: { required },
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
    cns: '',
    cep: '',
    logradouro: '',
    bairro: '',
    cidade: '',
    uf: '',
    numero: '',
    telefone: '',
    dataObito: null,
    primeiraAvaliacaoMedica: '',
    dataFimIsolamento: null,
    dataInicioSintomas: null,
    dataColetaExame: null,
    local: '',
    tipoConvenio: '',
    unidadeReferencia: '',
    tipoExame: '',
    dataResultado: null,
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
    situacaoFamilia: [],
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
      nomeFamiliarErros () {
        const errors = []
        if (!this.$v.nomeFamiliar.$dirty) return errors
        !this.$v.nomeFamiliar.required && errors.push('O nome do familiar é necessário.')
        return errors
      },
      situacaoFamiliarErros () {
        const errors = []
        if (!this.$v.situacaoFamiliar.$dirty) return errors
        !this.$v.situacaoFamiliar.required && errors.push('A situação do familiar é necessário.')
        return errors
      },
      exameFamiliarErros () {
        const errors = []
        if (!this.$v.exameFamiliar.$dirty) return errors
        !this.$v.exameFamiliar.required && errors.push('A situação do exame do familiar é necessário.')
        return errors
      },
    },

    methods: {
      adicionarFamiliar(){
        let familiar = {
          nome: this.nomeFamiliar,
          sintomatico: this.situacaoFamiliar,
          exame: this.exameFamiliar
        }

        this.nomeFamiliar = "";
        this.situacaoFamiliar = "";
        this.exameFamiliar = "";

        this.situacaoFamilia.push(familiar)
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
      submit () {
        this.$v.$touch()
      }
    },

    watch: {
      menuDataNascimento (val) {
        val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR'))
      },
    },
};
</script>
