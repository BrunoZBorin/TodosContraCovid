<template>
	<v-content>
		<v-snackbar v-model="snack" :timeout="3000" :color="snackColor" top>
			{{ snackText }}
			<v-btn text @click="snack = false">Fechar</v-btn>
		</v-snackbar>
		<loading :active.sync="isLoading" loader="Bars"></loading>
		<v-container class="fill-height" fluid>
			<v-row align="center" justify="center">
				<v-col cols="12" sm="8" md="4">
					<v-card outlined style="border: none; background-color: transparent;">
						<v-card-title style="margin-bottom: 40px;">
							Foto
						</v-card-title>
						<v-card-text>
							<v-form ref="form" v-model="valid" lazy-validation>
								<v-text-field label="E-mail" name="email" type="email" autocomplete="email"
									v-model="email" required :rules="EmailRules" v-on:keyup.enter="validate"
									outlined rounded>
									<template v-slot:append>
										<v-fade-transition leave-absolute>
											<v-icon>person</v-icon>
										</v-fade-transition>
									</template>
								</v-text-field>
								<v-text-field id="password" label="Senha" name="password" type="password"
									autocomplete="current-password" v-model="password" required :rules="passwordRules"
									v-on:keyup.enter="validate" outlined rounded>
									<template v-slot:append>
										<v-fade-transition leave-absolute>
											<v-icon>lock</v-icon>
										</v-fade-transition>
									</template>
								</v-text-field>
								<v-btn color="primary" v-on:click="validate" v-on:keyup.enter="validate" rounded block>Acessar</v-btn>
								<v-spacer />
							</v-form>
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
		</v-container>
	</v-content>
</template>

<script>
	import Loading from 'vue-loading-overlay';
	import 'vue-loading-overlay/dist/vue-loading.css';
	export default {
		components: {
			Loading
		},
		data() {
			return {
				isLoading: false,
				snack: false,
				snackColor: '',
				snackText: '',
				valid: true,
				email: '',
                EmailRules: [ v => /.+@.+/.test(v) || 'E-mail é obrigatório' ],
				password: '',
				passwordRules: [v => !!v || 'Senha é obrigatório']
			}
		},
		methods: {
			stop() {
				this.snack = true
				this.snackColor = 'warning'
				this.snackText = 'E-mail ou senha incorretos!'
			},
			validate() {
				if (this.$refs.form.validate()) {
					this.logar();
					return;
				}
			},
			async logar() {
				this.isLoading = true;

				await this.$store
				.dispatch('login', { email: this.email, password: this.password })
				.then(() => this.$router.push('home'))
				.catch(error => this.stop());

				this.isLoading = false;
			}
		}
	}
</script>