<template>
  <div class="atendimento">
    <v-app>
      <v-container>
        <template>
          <v-row justify="center">
            <v-dialog
              v-model="dialogConduta"
              persistent
              max-width="390"
            >
              <v-card>
                <v-card-title class="headline">
                  Orientação de Conduta
                </v-card-title>
                <v-card-text>{{conduta}}</v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn
                    color="green darken-1"
                    text
                    @click="cliqueOK"
                  >
                    OK
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-row>
        </template>
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
                    :readonly="modoVisualizacao"
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
                    :readonly="modoVisualizacao"
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
                    :readonly="modoVisualizacao"
                    @input="$v.cep.$touch()"
                    @blur="$v.cep.$touch(), buscaEndereco()"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    v-model="cidade"
                    :error-messages="cidadeErros"
                    label="Cidade"
                    :loading="loadingEndereco"
                    readonly
                    required
                    @input="$v.cns.$touch()"
                    @blur="$v.cns.$touch()"
                  ></v-text-field>
                </v-col>
                <v-col cols="1">
                  <v-text-field
                    v-model="uf"
                    :error-messages="ufErros"
                    label="UF"
                    :loading="loadingEndereco"
                    readonly
                    required
                    @input="$v.cns.$touch()"
                    @blur="$v.cns.$touch()"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    v-model="bairro"
                    :error-messages="bairroErros"
                    label="Bairro"
                    :loading="loadingEndereco"
                    readonly
                    required
                    @input="$v.cns.$touch()"
                    @blur="$v.cns.$touch()"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="9">
                  <v-text-field
                    v-model="logradouro"
                    :error-messages="logradouroErros"
                    label="Logradouro"
                    :loading="loadingEndereco"
                    readonly
                    required
                    @input="$v.cns.$touch()"
                    @blur="$v.cns.$touch()"
                  ></v-text-field>
                </v-col>
                <v-col cols="3">
                  <v-text-field
                    v-model="numero"
                    :error-messages="numeroErros"
                    label="Numero"
                    v-mask="'##########'"
                    required
                    :readonly="modoVisualizacao"
                    @input="$v.numero.$touch()"
                    @blur="$v.numero.$touch()"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-menu
                    ref="menuPrimeiraAvaliacaoMedica"
                    v-model="menuPrimeiraAvaliacaoMedica"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="dataPrimeiraAvaliacaoMedicaFormatada"
                        label="Primeira Avaliação Médica"
                        prepend-icon="event"
                        readonly
                        :error-messages="dataPrimeiraAvaliacaoMedicaErros"
                        v-bind="attrs"
                        v-on="on"
                        required
                        @input="$v.dataPrimeiraAvaliacaoMedica.$touch()"
                        @blur="$v.dataPrimeiraAvaliacaoMedica.$touch(), dataPrimeiraAvaliacaoMedica = parseDate(dataPrimeiraAvaliacaoMedicaFormatada)"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="dataPrimeiraAvaliacaoMedica"
                      :max="new Date().toISOString().substr(0, 10)"
                      min="1950-01-01"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="4">
                  <v-text-field
                    v-model="telefone"
                    :error-messages="telefoneErros"
                    label="Telefone"
                    v-mask="'## #########'"
                    required
                    :readonly="modoVisualizacao"
                    @input="$v.telefone.$touch()"
                    @blur="$v.telefone.$touch()"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
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
                    :readonly="modoVisualizacao"
                    @change="$v.local.$touch()"
                    @blur="$v.local.$touch()"
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="tipoConvenio"
                    :items="itensTipoConvenio"
                    :error-messages="tipoConvenioErros"
                    label="Tipo de Convênio"
                    required
                    :readonly="modoVisualizacao"
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
                    :readonly="modoVisualizacao"
                    @change="$v.unidadeReferencia.$touch()"
                    @blur="$v.unidadeReferencia.$touch()"
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-select
                    v-model="tipoExame"
                    :items="itensTipoExame"
                    :error-messages="tipoExameErros"
                    label="Tipo de Exame"
                    required
                    :readonly="modoVisualizacao"
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
                    :items="itensResultadoExame"
                    :error-messages="resultadoExameErros"
                    label="Resultado do Exame"
                    required
                    :readonly="modoVisualizacao"
                    @change="$v.resultadoExame.$touch()"
                    @blur="$v.resultadoExame.$touch()"
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-select
                    v-model="grupoRisco"
                    :items="itensGrupoRisco"
                    :error-messages="grupoRiscoErros"
                    label="Grupo de Risco"
                    required
                    :readonly="modoVisualizacao"
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
                    :readonly="modoVisualizacao"
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-select
                    v-model="emIsolamento"
                    :items="itensEmIsolamento"
                    :error-messages="emIsolamentoErros"
                    label="Em Isolamento"
                    required
                    :readonly="modoVisualizacao"
                    @change="$v.emIsolamento.$touch()"
                    @blur="$v.emIsolamento.$touch()"
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="orientacao"
                    :items="itensOrientacao"
                    :error-messages="orientacaoErros"
                    label="Orientação"
                    required
                    :readonly="modoVisualizacao"
                    @change="$v.orientacao.$touch()"
                    @blur="$v.orientacao.$touch()"
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="apetite"
                    :items="itensApetite"
                    :error-messages="apetiteErros"
                    label="Apetite"
                    required
                    :readonly="modoVisualizacao"
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
                    :readonly="modoVisualizacao"
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                  <v-select
                    v-model="febre"
                    :items="itensFebre"
                    :error-messages="febreErros"
                    label="Febre"
                    required
                    :readonly="modoVisualizacao"
                    @change="$v.febre.$touch()"
                    @blur="$v.febre.$touch()"
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="tosse"
                    :items="itensTosse"
                    :error-messages="tosseErros"
                    label="Tosse"
                    required
                    :readonly="modoVisualizacao"
                    @change="$v.tosse.$touch()"
                    @blur="$v.tosse.$touch()"
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="faltaArCansaco"
                    :items="itensFaltaArCansaco"
                    :error-messages="faltaArCansacoErros"
                    label="Falta de Ar/Cansaço"
                    required
                    :readonly="modoVisualizacao"
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
                            <template v-slot:activator="{ on, attrs }" v-if="!modoVisualizacao">
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
                                        :error-messages="nomeFamiliarErros"
                                      ></v-text-field>
                                    </v-col>
                                  </v-row>
                                  <v-row>
                                    <v-col cols="6">
                                      <v-select
                                        v-model="situacaoFamiliar"
                                        :items="itensSituacaoFamiliar"
                                        label="Situação"
                                        :error-messages="situacaoFamiliarErros"
                                      ></v-select>
                                    </v-col>
                                    <v-col cols="6">
                                      <v-select
                                        v-model="exameFamiliar"
                                        :items="itensExameFamiliar"
                                        label="Exame"
                                        :error-messages="exameFamiliarErros"
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
                      <template v-slot:item.actions="{ item }" v-if="!modoVisualizacao">
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
                      <template v-slot:no-data v-if="!modoVisualizacao">
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
                    :readonly="modoVisualizacao"
                    @change="$v.observacaoGeral.$touch()"
                    @blur="$v.observacaoGeral.$touch()"
                  ></v-textarea>
                </v-col>
              </v-row>

              <v-btn class="mr-4 mt-2" color="success" @click="registrar" v-if="!modoVisualizacao">Registrar</v-btn>
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
    cidade: { required },
    uf: { required },
    bairro: { required },
    logradouro: { required },
    numero: { required },
    telefone: { required },
    dataInicioSintomas: { required },
    dataPrimeiraAvaliacaoMedica: { required },
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
    idPaciente: 0,
    idAtendimento: 0,
    email: '',
    select: null,
    checkbox: false,
    dialogConduta: false,
    menuDataNascimento: false,
    menuDataObito: false,
    menuDataInicioSintomas: false,
    menuPrimeiraAvaliacaoMedica: false,
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
    dataPrimeiraAvaliacaoMedica: null,
    dataPrimeiraAvaliacaoMedicaFormatada: null,
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
    nomeFamiliarErros: '',
    situacaoFamiliarErros: '',
    exameFamiliarErros: '',
    observacaoGeral: '',
    conduta: '',
    loadingEndereco: false,
    unidadesSintomaticas: [],
    unidadesReferencia: [],
    comorbidades: [],
    sinais: [],
    itensTipoConvenio: [{text: 'SUS', value: 'SUS'}, {text: 'Particular', value: 'particular'}],
    itensTipoExame: [{text: 'PCR-RT', value: 'PCR-RT'}, {text: 'Sorologia', value: 'sorologia'}, {text: 'Teste Rápido', value: 'teste_rapido'}],
    itensResultadoExame: [{text: 'Positivo', value: 'positivo'}, {text: 'Negativo', value: 'negativo'}, {text: 'Aguardando Resultado', value: 'aguardando_resultado'}],
    itensGrupoRisco: [{text: 'Sim', value: 'sim'}, {text: 'Não', value: 'nao'}],
    itensEmIsolamento: [{text: 'Sim', value: 'sim'}, {text: 'Não', value: 'nao'}],
    itensOrientacao: [{text: 'Bem', value: 'bem'}, {text: 'Confuso', value: 'confuso'}, {text: 'Sonolento', value: 'sonolento'}],
    itensApetite: [{text: 'Bom', value: 'bom'}, {text: 'Diminuído', value: 'diminuido'}, {text: 'Anoréxico', value: 'anorexico'}],
    itensFebre: [{text: 'Ausente', value: 'ausente'}, {text: 'Um Pico Baixo', value: 'pico_baixo'}, {text: 'Febre Persistente', value: 'persistente'}],
    itensTosse: [{text: 'Ausente', value: 'ausente'}, {text: 'Consegue Falar Sem Tossir', value: 'fala_sem_tossir'}, {text: 'Não Completa uma Frase Sem Tossir', value: 'fala_tossindo'}],
    itensFaltaArCansaco: [{text: 'Ausente', value: 'ausente'}, {text: 'Presente ao Esforço', value: 'presente_ao_esforco'}, {text: 'Intensa no Repouso', value: 'intensa_no_repouso'}],
    itensSituacaoFamiliar: [{text: 'Sintomático', value: 'sintomatico'}, {text: 'Assintomático', value: 'assintomatico'}],
    itensExameFamiliar: [{text: 'Positivo', value: 'positivo'}, {text: 'Negativo', value: 'negativo'}, {text: 'Aguardando Resultado', value: 'aguardando_resultado'}],
    modoVisualizacao: false
  }),

  async mounted() {
    this.idAtendimento = this.$route.params.id;

    if(this.idAtendimento > 0)
    {
      this.modoVisualizacao = true;
      this.carregaAtendimento();
    }

    await this.carregaUnidadesReferencia();
    await this.carregaUnidadesSintomaticas();
    await this.carregaComorbidades();
    await this.carregaSinais();
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
      cidadeErros () {
        const errors = []
        if (!this.$v.cidade.$dirty) return errors
        !this.$v.cidade.required && errors.push('O nome da cidade é necessário.')
        return errors
      },
      ufErros () {
        const errors = []
        if (!this.$v.uf.$dirty) return errors
        !this.$v.uf.required && errors.push('O nome do estado é necessário.')
        return errors
      },
      bairroErros () {
        const errors = []
        if (!this.$v.bairro.$dirty) return errors
        !this.$v.bairro.required && errors.push('O bairro é necessário.')
        return errors
      },
      logradouroErros () {
        const errors = []
        if (!this.$v.logradouro.$dirty) return errors
        !this.$v.logradouro.required && errors.push('O logradouro é necessário.')
        return errors
      },
      numeroErros () {
        const errors = []
        if (!this.$v.numero.$dirty) return errors
        !this.$v.numero.required && errors.push('O numero é necessário.')
        return errors
      },
      dataPrimeiraAvaliacaoMedicaErros () {
        const errors = []
        if (!this.$v.dataPrimeiraAvaliacaoMedica.$dirty) return errors
        !this.$v.dataPrimeiraAvaliacaoMedica.required && errors.push('A data da primeira avaliação médica é necessária.')
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
      setAtendimento(atendimento){
        this.idPaciente = atendimento.paciente_id;
        this.dataLigacao = this.moment(atendimento.data_hora_ligacao).format('DD/MM/YYYY');
        this.horaLigacao = atendimento.data_hora_ligacao.split(' ')[1];
        this.emIsolamento = atendimento.isolamento;
        this.orientacao = atendimento.orientacao;
        this.conduta = atendimento.orientacao_conduta;
        this.apetite = atendimento.apetite;
        this.febre = atendimento.febre;
        this.tosse = atendimento.tosse;
        this.faltaArCansaco = atendimento.falta_de_ar;
        this.observacaoGeral = atendimento.observacoes_gerais;
      },

      setPaciente(paciente){
        this.cep = paciente.cep;
        this.cidade = paciente.cidade;
        this.uf = paciente.estado;
        this.bairro = paciente.bairro;
        this.logradouro = paciente.logradouro;
        this.nome = paciente.nome;
        this.numero = paciente.numero;
        this.telefone = paciente.telefone;
        this.cns = paciente.cns;
        this.dataNascimento = paciente.data_nasc;
        this.dataObito = paciente.obito;
        this.dataPrimeiraAvaliacaoMedica= paciente.primeira_avaliacao_medica;
        this.dataInicioSintomas = paciente.data_inicio_sintomas;
        this.dataColetaExame = paciente.data_coleta_exames;
        this.local = paciente.unidade_sintomatica_id;
        this.tipoConvenio = paciente.convenio;
        this.unidadeReferencia = paciente.unidade_saude_id;
        this.tipoExame = paciente.tipo_exame;
        this.dataResultado = paciente.data_resultado;
        this.resultadoExame = paciente.resultado_exame;
        this.grupoRisco = paciente.grupo_risco;
        // this.familiares = 
        // this.comorbidades = 
        // this.sinais = 
      },

      async carregaAtendimento(){
        await this.axios.get('/atendimentos/' + this.idAtendimento)
        .then((response) => {
          this.setAtendimento(response.data);
          
          this.carregaPaciente();
        })
        .catch((error) => {
          console.log(error);
        })
      },

      cliqueOK(){
        this.dialogConduta = false;
        this.$router.push('/home');
      },

      async carregaPaciente(){
        await this.axios.get('/pacientes/' + this.idPaciente)
        .then((response) => {
          this.setPaciente(response.data);
        })
        .catch((error) => {
          console.log(error);
        })
      },

      formataData(value){
        return this.moment(value).format("DD/MM/YYYY");
      },

      async carregaUnidadesReferencia(){
        await this.axios.get('/unidades_saude')
        .then((response) => {
          this.unidadesReferencia = response.data;
        })
        .catch((error) => {
          console.log(error);
        })
      },

      async carregaUnidadesSintomaticas(){
        await this.axios.get('/unidades_sintomaticas')
        .then((response) => {
          this.unidadesSintomaticas = response.data;
        })
        .catch((error) => {
          console.log(error);
        })
      },

      async carregaComorbidades(){
        await this.axios.get('/comorbidades')
        .then((response) => {
          this.comorbidades = response.data;
        })
        .catch((error) => {
          console.log(error);
        })
      },

      async carregaSinais(){
        await this.axios.get('/sinais')
        .then((response) => {
          this.sinais = response.data;
        })
        .catch((error) => {
          console.log(error);
        })
      },

      async buscaEndereco(){
        if (this.$v.cep.$invalid)
          return;

        this.loadingEndereco = true;

        await this.axios.get('/cep', { params: { cep: this.cep.replace("-", "") } })
        .then((response) => {
          this.cidade = response.data.cidade;
          this.uf = response.data.uf;
          this.bairro = response.data.bairro;
          this.logradouro = response.data.logradouro;
        })
        .catch((error) => {
          console.log(error);
        })

        this.loadingEndereco = false;
      },

      async registrar () {
        this.$v.$touch()

        if(this.$v.$invalid)
          return;

        const parametros = {
          paciente_cep: this.cep,
          paciente_nome: this.nome,
          paciente_numero: this.numero,
          paciente_telefone: this.telefone,
          paciente_cns: this.cns,
          paciente_data_nasc: this.dataNascimento,
          paciente_obito: this.dataObito,
          paciente_primeira_avaliacao_medica: this.dataPrimeiraAvaliacaoMedica,
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
          atendimento_paciente_id: this.idPaciente,
          atendimento_usuario_id: this.$store.getters.getDadosUser.id,
          familiares: this.familiares,
          comorbidades: this.comorbidades,
          sinais: this.sinais
        }

        await this.axios.post('/primeiro_cadastro', parametros )
        .then((response) => {
          if(response.status == 201)
          {
            if(response.data[1]['orientacao_conduta'] == 'manter_isolamento_domiciliar')
            {
              this.conduta = "Manter Isolamento Domiciliar.";
            }
            else if(response.data[1]['orientacao_conduta'] == 'encaminhar_unidade_sintomatica')
            {
              this.conduta = "Encaminhar paciente a uma unidade sintomática.";
            }
            else
            {
              this.conduta = "Encaminhar para o SAMU";
            }

            this.dialogConduta = true;
          }
          else
            alert("Erro ao registrar atendimento!")
        })
        .catch((error) => {
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
          this.nomeFamiliar = '';
          this.situacaoFamiliar = '';
          this.exameFamiliar = '';

          this.editedIndex = -1;
        })
      },

      save () {
        if (this.nomeFamiliar.trim() == '')
        {
          this.nomeFamiliarErros = "Informe o nome do familiar.";
          return;
        }
        
        if (this.situacaoFamiliar == '')
        {
          this.situacaoFamiliarErros = "Informe a situação do familiar.";
          return;
        }

        if (this.exameFamiliar == '')
        {
          this.exameFamiliarErros = "Informe a situação do exame do familiar.";
          return;
        }

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

      dataPrimeiraAvaliacaoMedica (val) {
        this.dataPrimeiraAvaliacaoMedicaFormatada = this.formataData(this.dataPrimeiraAvaliacaoMedica)
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

      nomeFamiliar (val) {
        if(this.nomeFamiliar.trim() != '')
          this.nomeFamiliarErros = ''
      },

      situacaoFamiliar (val) {
        if(this.situacaoFamiliar != null)
          this.situacaoFamiliarErros = ''
      },

      exameFamiliar (val) {
        if(this.exameFamiliar != null)
          this.exameFamiliarErros = ''
      }
    }
};
</script>
