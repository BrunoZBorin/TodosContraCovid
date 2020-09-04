<template>
    <div class="usuario">
      <v-container>
        <v-card>
          <v-card-title class="headline">Cadastro de Usuário</v-card-title>
          <v-card-text>
            <v-form>
              <v-row>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="nome"
                    :error-messages="nomeErros"
                    label="Nome"
                    required
                    @input="$v.nome.$touch()"
                    @blur="$v.nome.$touch()"
                    dense
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="email"
                    :error-messages="emailErros"
                    label="E-mail"
                    required
                    autocomplete="new-email"
                    @input="$v.email.$touch()"
                    @blur="$v.email.$touch()"
                    dense
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="password"
                    :error-messages="passwordErros"
                    label="Senha"
                    required
                    type="password"
                    autocomplete="new-password"
                    @input="$v.password.$touch()"
                    @blur="$v.password.$touch()"
                    dense
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="telefone"
                    :error-messages="telefoneErros"
                    label="Telefone"
                    v-mask="'## #########'"
                    required
                    @input="$v.telefone.$touch()"
                    @blur="$v.telefone.$touch()"
                    dense
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <v-select
                    v-model="perfil"
                    :items="itemsPerfils"
                    :error-messages="perfilErros"
                    label="Perfil"
                    required
                    @change="$v.perfil.$touch()"
                    @blur="$v.perfil.$touch()"
                    dense
                  ></v-select>
                </v-col>
                <v-col cols="12" md="12">
                  <v-select
                    v-model="unidade_saude"
                    :items="itemsUnidadesSaude"
                    :error-messages="unidadesSaudeErros"
                    label="Unidades de Saúde"
                    item-text="nome"
                    item-value="id"
                    required
                    @change="$v.unidade_saude.$touch()"
                    @blur="$v.unidade_saude.$touch()"
                    dense
                  ></v-select>
                </v-col>
              </v-row>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn
              class="mr-4 mt-2"
              color="success"
              dark
              @click="registrar"
            >
              Registrar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-container>
    </div>
</template>

<script>

import { validationMixin } from 'vuelidate';
import { required, maxLength, minLength, email } from 'vuelidate/lib/validators';

export default {

  mixins: [validationMixin],

  validations: {
    nome: { required },
    email: { required, email },
    password: { required },
    telefone: { required },
    perfil: { required },
    unidade_saude: { required }
  },

  data() {
    return {
      nome: '',
      email: '',
      password: '',
      telefone: '',

      perfil: '',
      itemsPerfils: [ 'MUNICIPAL', 'MONITORAMENTO' ],

      unidade_saude: '',
      itemsUnidadesSaude: []

    }
  },

  mounted() {
    this.buscarUnidadesSaude();

    if(this.$route.params.id > 0) this.findById(this.$route.params.id);
  },

  methods: {

    buscarUnidadesSaude() {
      this.axios.get('unidades_saude')
      .then(response => {
        this.itemsUnidadesSaude = response.data;
      })
      .catch(error => {
        console.log(error);
      });
    },

    async findById(id) {
      const response = await this.axios.get(`users/${id}`)
      .catch(err => {
        console.log(err);
        this.$swal(
          'Erro',
          'Ocorreu um problema inesperado!',
          'error'
        );
      });
      
      const { nome, email, telefone, perfil, unidade_saude_id } = response.data;

      this.nome = nome;
      this.email = email;
      this.telefone = telefone;
      this.perfil = String(perfil).toUpperCase();
      this.unidade_saude = unidade_saude_id;
    },

    async registrar() {
      this.$v.$touch();

      if(this.$v.$invalid) return;

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

      const { nome, email, password, telefone, perfil, unidade_saude } = this;

      this.axios.post('users', {
        nome,
        email,
        password,
        telefone,
        perfil,
        unidade_saude_id: unidade_saude
      })
      .then(response => {
        this.$swal(
          'Criado!',
          'Operação realizada com sucesso!',
          'success'
        )

        this.$router.push('/home');
      })
      .catch(error => {
        console.log(error);
        this.$swal(
          'Erro',
          'Ocorreu um problema inesperado!',
          'error'
        );
        return;
      });
    }

  },

  computed: {
    nomeErros () {
      const errors = []
      if (!this.$v.nome.$dirty) return errors
      !this.$v.nome.required && errors.push('O nome é necessário.')
      return errors
    },
    emailErros () {
      const errors = []
      if (!this.$v.email.$dirty) return errors
      !this.$v.email.required && errors.push('O e-mail é necessário.')
      !this.$v.email.email && errors.push('O e-mail é inválido.')
      return errors
    },
    passwordErros () {
      const errors = []
      if (!this.$v.password.$dirty) return errors
      !this.$v.password.required && errors.push('A senha é necessária.')
      return errors
    },
    telefoneErros () {
      const errors = []
      if (!this.$v.telefone.$dirty) return errors
      !this.$v.telefone.required && errors.push('O telefone é necessário.')
      return errors
    },
    perfilErros () {
      const errors = []
      if (!this.$v.perfil.$dirty) return errors
      !this.$v.perfil.required && errors.push('O perfil é necessário.')
      return errors
    },
    unidadesSaudeErros () {
      const errors = []
      if (!this.$v.unidade_saude.$dirty) return errors
      !this.$v.unidade_saude.required && errors.push('A unidade de saúde é necessário.')
      return errors
    }
  }
}
</script>