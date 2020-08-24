<template>
    <div class="usuario">
      <v-snackbar
        v-model="snack" 
        :timeout="3000" 
        :color="snackColor" 
        top
        multiLine
      >
        {{ snackText }}
        <v-btn text @click="snack = false">Fechar</v-btn>
      </v-snackbar>
      <v-container>
        <v-card>
          <v-card-title class="headline">Usuário</v-card-title>
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
                  ></v-select>
                </v-col>
              </v-row>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="success" @click="registrar()">Registrar</v-btn>
          </v-card-actions>
        </v-card>
      </v-container>
    </div>
</template>

<script>
import { validationMixin } from 'vuelidate';
import { required, maxLength, minLength } from 'vuelidate/lib/validators';
export default {

  mixins: [validationMixin],

  validations: {
    nome: { required },
    email: { required },
    password: { required },
    telefone: { required },
    perfil: { required },
    unidade_saude: { required }
  },

  data() {
    return {
      snack: false,
      snackColor: '',
      snackText: '',
      nome: '',
      email: '',
      password: '',
      telefone: '',
      perfil: '',
      itemsPerfils: [ 'Municipal', 'Local' ],
      unidade_saude: '',
      itemsUnidadesSaude: []
    }
  },

  mounted() {
    this.buscarUnidadesSaude();
  },

  methods: {
    success() {
      this.snack = true;
      this.snackColor = 'success';
      this.snackText = 'Operação realizada com sucesso!';
    },

    buscarUnidadesSaude() {
      this.axios.get('unidades_saude')
      .then(response => {
        this.itemsUnidadesSaude = response.data;
      })
      .catch(error => {
        console.log(error);
      });
    },

    registrar() {
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
        alert("Operação realizada com sucesso!");
        this.$router.push('home');
      })
      .catch(error => {
        const warnings = error.response.data.errors;
        let msgError = "";
        
        Object.keys(warnings).forEach(warning => {
          msgError += `${warning}: ${warnings[warning][0]} \n`;
        });

        alert(msgError);
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