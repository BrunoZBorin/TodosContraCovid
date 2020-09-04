<template>
  <div>
    <v-container>
      <v-card>
        <v-card-title class="headline">Cadastro das Unidades de Saúdes</v-card-title>
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
                  dense
                ></v-text-field>
              </v-col>
              <v-col cols="4">
                <v-text-field
                  v-model="cidade"
                  :error-messages="cidadeErros"
                  label="Cidade"
                  :loading="loadingEndereco"
                  disabled
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
                  disabled
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
                  disabled
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
                  disabled
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
                  v-mask="'##########'"
                  required
                  @input="$v.numero.$touch()"
                  @blur="$v.numero.$touch()"
                  dense
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  ref="telefone"
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
  import { required, maxLength, minLength } from 'vuelidate/lib/validators';

  export default {

    mixins: [validationMixin],

    validations: {
      nome: { required },
      cep: { required, minLength: minLength(9), maxLength: maxLength(9)  },
      cns: { required },
      cidade: { required },
      uf: { required },
      bairro: { required },
      logradouro: { required },
      numero: { required },
      telefone: { required },
    },

    data() {
      return {
        nome: '',
        cep: '',
        logradouro: '',
        bairro: '',
        cidade: '',
        uf: '',
        numero: '',
        telefone: '',
        
        loadingEndereco: false,
      }
    },

    async mounted() {
      if(this.$route.params.id > 0) this.findById(this.$route.params.id);
    },

    methods: {

      async findById(id) {
        const response = await this.axios.get(`unidades_saude/${id}`)
        .catch(err => {
          console.log(err);
          this.$swal(
            'Erro',
            'Ocorreu um problema inesperado!',
            'error'
          );
        });
        
        const { nome, cep, cidade, estado, bairro, logradouro, numero, telefone } = response.data;

        this.nome = nome;
        this.cep = cep;
        this.cidade = cidade;
        this.uf = estado;
        this.bairro = bairro;
        this.logradouro = logradouro;
        this.numero = numero;
        this.telefone = telefone;

        this.$v.$touch();
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

        const { nome, telefone, cep, logradouro, numero, bairro, cidade, uf: estado } = this;

        this.axios.post(
          'unidades_saude',
          {
            nome,
            telefone,
            cep,
            logradouro,
            numero,
            bairro,
            cidade,
            estado
          }
        )
        .then(response => {
          this.$swal(
            'Criado!',
            'Operação realizada com sucesso!',
            'success'
          )

          this.$router.push('/home');
        })
        .catch(error => {
          console.log(error.response.data);

          const { response: { data: { errors } } } = error;

          if(!errors) {
            this.$swal(
              'Erro',
              'Ocorreu um problema inesperado!',
              'error'
            );
            return;
          }

          let html = '<div style="text-align: left;">';

          Object.keys(errors).map(campo => {
            html += errors[campo][0] + '<br/>';
          });

          html += '</div>';

          this.$swal({
            html,
            title: 'Atenção',
            icon: 'warning'
          });

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
      telefoneErros () {
        const errors = []
        if (!this.$v.telefone.$dirty) return errors
        !this.$v.telefone.required && errors.push('O telefone é necessário.')
        return errors
      },
    }
  }
</script>