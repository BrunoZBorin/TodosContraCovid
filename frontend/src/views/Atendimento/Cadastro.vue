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
          <v-card-title class="headline">Cadastro de Atendimento</v-card-title>
          <v-container>
            <v-form>
              <v-row>
                <v-col cols="4">
                  <v-text-field
                    v-model="nome"
                    :error-messages="nomeErros"
                    label="Nome"
                    required
                    :readonly="modo == 'visualizar'"
                    @input="$v.nome.$touch()"
                    @blur="$v.nome.$touch()"
                    dense
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
                        :value="computedDataNascimentoFormatada"
                        :error-messages="dataNascimentoErros"
                        label="Data de Nascimento"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        required
                        @input="$v.dataNascimento.$touch()"
                        @blur="$v.dataNascimento.$touch()"
                        dense
                        v-if="modo == 'visualizar'"
                      ></v-text-field>
                      <v-text-field
                        :value="computedDataNascimentoFormatada"
                        :error-messages="dataNascimentoErros"
                        label="Data de Nascimento"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        required
                        @input="$v.dataNascimento.$touch()"
                        @blur="$v.dataNascimento.$touch()"
                        dense
                        v-else
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
                    :readonly="modo == 'visualizar'"
                    @input="$v.cns.$touch()"
                    @blur="$v.cns.$touch()"
                    dense
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
                    :readonly="modo == 'visualizar'"
                    @input="$v.cep.$touch()"
                    @blur="$v.cep.$touch(), buscaEndereco()"
                    dense
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
                    dense
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
                    dense
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
                    dense
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
                    dense
                  ></v-text-field>
                </v-col>
                <v-col cols="3">
                  <v-text-field
                    v-model="numero"
                    :error-messages="numeroErros"
                    label="Numero"
                    required
                    :readonly="modo == 'visualizar'"
                    @input="$v.numero.$touch()"
                    @blur="$v.numero.$touch()"
                    dense
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
                        :value="computedDataPrimeiraAvaliacaoMedicaFormatada"
                        label="Primeira Avaliação Médica"
                        prepend-icon="event"
                        readonly
                        :error-messages="dataPrimeiraAvaliacaoMedicaErros"
                        v-bind="attrs"
                        required
                        @input="$v.dataPrimeiraAvaliacaoMedica.$touch()"
                        @blur="$v.dataPrimeiraAvaliacaoMedica.$touch()"
                        dense
                        v-if="modo == 'visualizar'"
                      ></v-text-field>

                      <v-text-field
                        :value="computedDataPrimeiraAvaliacaoMedicaFormatada"
                        label="Primeira Avaliação Médica"
                        prepend-icon="event"
                        readonly
                        :error-messages="dataPrimeiraAvaliacaoMedicaErros"
                        v-bind="attrs"
                        v-on="on"
                        required
                        @input="$v.dataPrimeiraAvaliacaoMedica.$touch()"
                        @blur="$v.dataPrimeiraAvaliacaoMedica.$touch()"
                        dense
                        v-else
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="dataPrimeiraAvaliacaoMedica"
                      :title-date-format="formataData"
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
                    :readonly="modo == 'visualizar'"
                    @input="$v.telefone.$touch()"
                    @blur="$v.telefone.$touch()"
                    dense
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
                        :value="computedDataObitoFormatada"
                        label="Óbito"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        dense
                        v-if="modo == 'visualizar'"
                      ></v-text-field>

                      <v-text-field
                        :value="computedDataObitoFormatada"
                        label="Óbito"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        clearable
                        @click:clear="dataObito = null"
                        dense
                        v-else
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="dataObito"
                      :title-date-format="formataData"
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
                        :value="computedDataInicioSintomasFormatada"
                        label="Data de Início dos Sintomas"
                        prepend-icon="event"
                        readonly
                        :error-messages="dataInicioSintomasErros"
                        v-bind="attrs"
                        required
                        @input="$v.dataInicioSintomas.$touch()"
                        @blur="$v.dataInicioSintomas.$touch()"
                        dense
                        v-if="modo == 'visualizar'"
                      ></v-text-field>

                      <v-text-field
                        :value="computedDataInicioSintomasFormatada"
                        label="Data de Início dos Sintomas"
                        prepend-icon="event"
                        readonly
                        :error-messages="dataInicioSintomasErros"
                        v-bind="attrs"
                        v-on="on"
                        required
                        @input="$v.dataInicioSintomas.$touch()"
                        @blur="$v.dataInicioSintomas.$touch()"
                        dense
                        v-else
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="dataInicioSintomas"
                      :title-date-format="formataData"
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
                        :value="computedDataColetaExameFormatada"
                        label="Data da Coleta do Exame"
                        prepend-icon="event"
                        readonly
                        :error-messages="dataColetaExameErros"
                        v-bind="attrs"
                        required
                        @input="$v.dataColetaExame.$touch()"
                        @blur="$v.dataColetaExame.$touch()"
                        dense
                        v-if="modo == 'visualizar'"
                      ></v-text-field>

                      <v-text-field
                        :value="computedDataColetaExameFormatada"
                        label="Data da Coleta do Exame"
                        prepend-icon="event"
                        readonly
                        :error-messages="dataColetaExameErros"
                        v-bind="attrs"
                        v-on="on"
                        required
                        @input="$v.dataColetaExame.$touch()"
                        @blur="$v.dataColetaExame.$touch()"
                        dense
                        v-else
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="dataColetaExame"
                      :title-date-format="formataData"
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
                    <template v-slot:activator="{ attrs }">
                      <v-text-field
                        v-model="dataFimIsolamentoFormatada"
                        label="Isolamento até"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        dense
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="dataFimIsolamento"
                      :title-date-format="formataData"
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
                    :readonly="modo == 'visualizar'"
                    @change="$v.local.$touch()"
                    @blur="$v.local.$touch()"
                    dense
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="tipoConvenio"
                    :items="itensTipoConvenio"
                    :error-messages="tipoConvenioErros"
                    label="Tipo de Convênio"
                    required
                    :readonly="modo == 'visualizar'"
                    @change="$v.tipoConvenio.$touch()"
                    @blur="$v.tipoConvenio.$touch()"
                    dense
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
                    :readonly="modo == 'visualizar'"
                    @change="$v.unidadeReferencia.$touch()"
                    @blur="$v.unidadeReferencia.$touch()"
                    dense
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
                    :readonly="modo == 'visualizar'"
                    @change="$v.tipoExame.$touch()"
                    @blur="$v.tipoExame.$touch()"
                    dense
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
                        :value="computedDataResultadoFormatada"
                        label="Data do Resultado"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        dense
                        v-if="modo == 'visualizar'"
                      ></v-text-field>

                      <v-text-field
                        :value="computedDataResultadoFormatada"
                        label="Data do Resultado"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        clearable
                        @click:clear="dataResultado = null"
                        dense
                        v-else
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="dataResultado"
                      :title-date-format="formataData"
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
                    :readonly="modo == 'visualizar'"
                    @change="$v.resultadoExame.$touch()"
                    @blur="$v.resultadoExame.$touch()"
                    dense
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
                    :readonly="modo == 'visualizar'"
                    @change="$v.grupoRisco.$touch()"
                    @blur="$v.grupoRisco.$touch()"
                    dense
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-menu>
                    <template v-slot:activator="{ attrs }">
                      <v-text-field
                        v-model="dataLigacao"
                        label="Data da Ligação"
                        prepend-icon="event"
                        readonly
                        v-bind="attrs"
                        dense
                      ></v-text-field>
                    </template>
                  </v-menu>
                </v-col>
                <v-col cols="4">
                  <v-menu>
                    <template v-slot:activator="{ attrs }">
                      <v-text-field
                        v-model="horaLigacao"
                        label="Hora da Ligação"
                        readonly
                        v-bind="attrs"
                        dense
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
                    item-value="id"
                    :readonly="modo == 'visualizar'"
                    multiple
                    dense
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
                    :readonly="modo == 'visualizar'"
                    @change="$v.emIsolamento.$touch()"
                    @blur="$v.emIsolamento.$touch()"
                    dense
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="orientacao"
                    :items="itensOrientacao"
                    :error-messages="orientacaoErros"
                    label="Orientação"
                    required
                    :readonly="modo == 'visualizar'"
                    @change="$v.orientacao.$touch()"
                    @blur="$v.orientacao.$touch()"
                    dense
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="apetite"
                    :items="itensApetite"
                    :error-messages="apetiteErros"
                    label="Apetite"
                    required
                    :readonly="modo == 'visualizar'"
                    @change="$v.apetite.$touch()"
                    @blur="$v.apetite.$touch()"
                    dense
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
                    item-value="id"
                    :readonly="modo == 'visualizar'"
                    multiple
                    dense
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
                    :readonly="modo == 'visualizar'"
                    @change="$v.febre.$touch()"
                    @blur="$v.febre.$touch()"
                    dense
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="tosse"
                    :items="itensTosse"
                    :error-messages="tosseErros"
                    label="Tosse"
                    required
                    :readonly="modo == 'visualizar'"
                    @change="$v.tosse.$touch()"
                    @blur="$v.tosse.$touch()"
                    dense
                  ></v-select>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="faltaArCansaco"
                    :items="itensFaltaArCansaco"
                    :error-messages="faltaArCansacoErros"
                    label="Falta de Ar/Cansaço"
                    required
                    :readonly="modo == 'visualizar'"
                    @change="$v.faltaArCansaco.$touch()"
                    @blur="$v.faltaArCansaco.$touch()"
                    dense
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
                            <template v-slot:activator="{ on, attrs }" v-if="modo != 'visualizar'">
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
                      <template v-slot:item.actions="{ item }" v-if="modo != 'visualizar'">
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
                    :readonly="modo == 'visualizar'"
                    @change="$v.observacaoGeral.$touch()"
                    @blur="$v.observacaoGeral.$touch()"
                    dense
                  ></v-textarea>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" v-if="modo == 'visualizar'">
                  <v-textarea
                    v-model="conduta"
                    label="Orientação / Conduta"
                    :readonly="modo == 'visualizar'"
                    dense
                  ></v-textarea>
                </v-col>
              </v-row>

              <v-btn class="mr-4 mt-2" color="success" @click="registrar" v-if="modo != 'visualizar'">Registrar</v-btn>
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
    modo: 'registrar'
  }),

  async mounted() {
    this.idAtendimento = this.$route.params.id;
    this.idPaciente = this.$route.params.paciente_id;

    if(this.idAtendimento > 0 && this.idPaciente == 0)
    {
      this.modo = 'visualizar';
      this.carregaAtendimento();
    }
    else if(this.idAtendimento > 0 && this.idPaciente > 0)
    {
      this.carregaAtendimento();
    }
    else if(this.idPaciente > 0)
    {
      this.carregaPaciente();
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

      computedDataNascimentoFormatada () {
        return this.formataData(this.dataNascimento)
      },

      computedDataPrimeiraAvaliacaoMedicaFormatada () {
        return this.formataData(this.dataPrimeiraAvaliacaoMedica)
      },

      computedDataObitoFormatada () {
        return this.formataData(this.dataObito)
      },

      computedDataInicioSintomasFormatada () {
        return this.formataData(this.dataInicioSintomas)
      },

      computedDataColetaExameFormatada () {
        return this.formataData(this.dataColetaExame)
      },

      computedDataResultadoFormatada () {
        return this.formataData(this.dataResultado)
      },
    },

    methods: {
      setAtendimento(atendimento){
        this.idPaciente = atendimento[0].paciente_id;
        this.dataLigacao = this.moment(atendimento[0].data_hora_ligacao).format('DD/MM/YYYY');
        this.horaLigacao = atendimento[0].data_hora_ligacao.split(' ')[1];
        this.emIsolamento = atendimento[0].isolamento;
        this.orientacao = atendimento[0].orientacao;

        if(atendimento[0].orientacao_conduta == 'manter_isolamento_domiciliar')
        {
          this.conduta = "Manter Isolamento Domiciliar.";
        }
        else if(atendimento[0].orientacao_conduta == 'encaminhar_unidade_sintomatica')
        {
          this.conduta = "Encaminhar paciente a uma unidade sintomática.";
        }
        else
        {
          this.conduta = "Encaminhar para o SAMU";
        }

        this.apetite = atendimento[0].apetite;
        this.febre = atendimento[0].febre;
        this.tosse = atendimento[0].tosse;
        this.faltaArCansaco = atendimento[0].falta_de_ar;
        this.observacaoGeral = atendimento[0].observacoes_gerais;
        
        for(var i in atendimento[1][0])
        {
          atendimento[1][0][i].id = atendimento[1][0][i].sinais_id;
        }

        let sinais = [];

        for(var sinal of atendimento[1][0])
        {
          sinais.push(sinal['id'])
        }

        this.sinal = sinais;
      },

      setPaciente(paciente){
        this.cep = paciente[0].cep;
        this.cidade = paciente[0].cidade;
        this.uf = paciente[0].estado;
        this.bairro = paciente[0].bairro;
        this.logradouro = paciente[0].logradouro;
        this.nome = paciente[0].nome;
        this.numero = paciente[0].numero;
        this.telefone = paciente[0].telefone;
        this.cns = paciente[0].cns;
        this.dataNascimento = paciente[0].data_nasc;
        this.dataObito = paciente[0].obito;
        this.dataPrimeiraAvaliacaoMedica = paciente[0].primeira_avaliacao_medica;
        this.dataInicioSintomas = paciente[0].data_inicio_sintomas;
        this.dataColetaExame = paciente[0].data_coleta_exames;
        this.local = paciente[0].unidade_sintomatica_id;
        this.tipoConvenio = paciente[0].convenio;
        this.unidadeReferencia = paciente[0].unidade_saude_id;
        this.tipoExame = paciente[0].tipo_exame;
        this.dataResultado = paciente[0].data_resultado;
        this.resultadoExame = paciente[0].resultado_exame;
        this.grupoRisco = paciente[0].grupo_risco;
        this.familiares = paciente[1];

        let comorbidades = [];

        for(var comorbidade of paciente[4])
        {
          comorbidades.push(comorbidade['comorbidades_id']);
        }

        this.comorbidade = comorbidades;
      },

      async carregaAtendimento(){
        await this.axios.get('/show_atendimento_sinais/' + this.idAtendimento)
        .then((response) => {
          this.setAtendimento(response.data);
          
          this.carregaPaciente();
        })
        .catch((error) => {
          console.log(error);
          this.$swal(
            'Erro',
            'Ocorreu um problema inesperado!',
            'error'
          );
          return;
        })
      },

      cliqueOK(){
        this.dialogConduta = false;
        this.$router.push('/home');
      },

      async carregaPaciente(){
        await this.axios.get('/show_sinais_familiares/' + this.idPaciente)
        .then((response) => {
          this.setPaciente(response.data);
        })
        .catch((error) => {
          console.log(error);
          this.$swal(
            'Erro',
            'Ocorreu um problema inesperado!',
            'error'
          );
          return;
        })
      },

      formataData(value){
        return value == null ? null : this.moment(value).format("DD/MM/YYYY");
      },

      async carregaUnidadesReferencia(){
        await this.axios.get('/unidades_saude')
        .then((response) => {
          this.unidadesReferencia = response.data;
        })
        .catch((error) => {
          console.log(error);
          this.$swal(
            'Erro',
            'Ocorreu um problema inesperado!',
            'error'
          );
          return;
        })
      },

      async carregaUnidadesSintomaticas(){
        await this.axios.get('/unidades_sintomaticas')
        .then((response) => {
          this.unidadesSintomaticas = response.data;
        })
        .catch((error) => {
          console.log(error);
          this.$swal(
            'Erro',
            'Ocorreu um problema inesperado!',
            'error'
          );
          return;
        })
      },

      async carregaComorbidades(){
        await this.axios.get('/comorbidades')
        .then((response) => {
          this.comorbidades = response.data;
        })
        .catch((error) => {
          console.log(error);
          this.$swal(
            'Erro',
            'Ocorreu um problema inesperado!',
            'error'
          );
          return;
        })
      },

      async carregaSinais(){
        await this.axios.get('/sinais')
        .then((response) => {
          this.sinais = response.data;
        })
        .catch((error) => {
          console.log(error);
          this.$swal(
            'Erro',
            'Ocorreu um problema inesperado!',
            'error'
          );
          return;
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
          this.$swal(
            'Erro',
            'Ocorreu um problema inesperado!',
            'error'
          );
          return;
        })

        this.loadingEndereco = false;
      },

      async registrar () {
        this.$v.$touch()

        if(this.$v.$invalid)
          return;

        const result = await this.$swal({
          title: 'Tem ceteza que deseja continuar?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sim',
          cancelButtonText: 'Não'
        });

        if (!result.value) return;

        const parametros = {
          paciente_cep: this.cep,
          paciente_nome: this.nome,
          paciente_numero: this.numero,
          paciente_telefone: this.telefone,
          paciente_cns: this.cns,
          paciente_data_nasc: this.dataNascimento,
          paciente_obito: this.dataObito,
          paciente_primeira_avaliacao_medica: this.dataPrimeiraAvaliacaoMedica,
          paciente_isolamento_ate: this.dataFimIsolamento,
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
          comorbidades: this.comorbidade,
          sinais: this.sinal
        }

        if(this.idAtendimento > 0 && this.idPaciente > 0)
        {
          await this.axios.put('/atendimentos/' + this.idAtendimento, parametros)
          .then((response) => {
            if(response.status == 200)
            {
              if(response.data['original'][0]['orientacao_conduta'] == 'manter_isolamento_domiciliar')
              {
                this.conduta = "Manter Isolamento Domiciliar.";
              }
              else if(response.data['original'][0]['orientacao_conduta'] == 'encaminhar_unidade_sintomatica')
              {
                this.conduta = "Encaminhar paciente a uma unidade sintomática.";
              }
              else
              {
                this.conduta = "Encaminhar para o SAMU";
              }

              this.$swal(
                'Criado!',
                'Operação realizada com sucesso!',
                'success'
              )

              this.dialogConduta = true;
            }
            else
              this.$swal(
                'Erro',
                'Ocorreu um problema inesperado!',
                'error'
              );
              return;
          })
          .catch((error) => {
            console.log(error);
            this.$swal(
              'Erro',
              'Ocorreu um problema inesperado!',
              'error'
            );
            return;
          })
        }
        else if(this.idPaciente > 0)
        {
          await this.axios.post('/atendimento_create_paciente_update', parametros)
          .then((response) => {
            if(response.status == 200)
            {
              if(response.data['original'][0]['orientacao_conduta'] == 'manter_isolamento_domiciliar')
              {
                this.conduta = "Manter Isolamento Domiciliar.";
              }
              else if(response.data['original'][0]['orientacao_conduta'] == 'encaminhar_unidade_sintomatica')
              {
                this.conduta = "Encaminhar paciente a uma unidade sintomática.";
              }
              else
              {
                this.conduta = "Encaminhar para o SAMU";
              }

              this.$swal(
                'Criado!',
                'Operação realizada com sucesso!',
                'success'
              )

              this.dialogConduta = true;
            }
            else
              this.$swal(
                'Erro',
                'Ocorreu um problema inesperado!',
                'error'
              );
              return;
          })
          .catch((error) => {
            console.log(error);
            this.$swal(
              'Erro',
              'Ocorreu um problema inesperado!',
              'error'
            );
            return;
          })
        }
        else
        {
          await this.axios.post('/primeiro_cadastro', parametros)
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

              this.$swal(
                'Criado!',
                'Operação realizada com sucesso!',
                'success'
              )

              this.dialogConduta = true;
            }
            else
              this.$swal(
                'Erro',
                'Ocorreu um problema inesperado!',
                'error'
              );
              return;
          })
          .catch((error) => {
            console.log(error);
            this.$swal(
              'Erro',
              'Ocorreu um problema inesperado!',
              'error'
            );
            return;
          })
        }
      },

      editItem (item) {
        this.editedIndex = this.familiares.indexOf(item);

        this.nomeFamiliar = this.familiares[this.editedIndex].nome;
        this.situacaoFamiliar = this.familiares[this.editedIndex].sintomatico;
        this.exameFamiliar = this.familiares[this.editedIndex].exame;

        this.dialog = true
      },

      async deleteItem (item) {
        const index = this.familiares.indexOf(item);

        const result = await this.$swal({
          title: 'Você tem certeza que deseja excluir este familiar?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sim',
          cancelButtonText: 'Não'
        });

        if (!result.value) return;
        
        this.familiares.splice(index, 1);
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
      },

      dataColetaExame (val) {
        this.dataFimIsolamento = this.moment(this.dataColetaExame).add(14, 'days').format('YYYY-MM-DD');
        this.dataFimIsolamentoFormatada = this.moment(this.dataColetaExame).add(14, 'days').format('DD/MM/YYYY');
      }
    }
};
</script>
