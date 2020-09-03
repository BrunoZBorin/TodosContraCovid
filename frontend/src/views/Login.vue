<template>
	<v-main>
		<v-snackbar v-model="snack" :timeout="3000" :color="snackColor" top>
			{{ snackText }}
			<v-btn text @click="snack = false">Fechar</v-btn>
		</v-snackbar>
		<v-container class="fill-height" fluid>
			<v-row>
				<v-col
					cols="12"
					sm="8"
					md="4"
					class="mx-auto"
				>
					<v-card
						outlined
						elevation="15"
						class="pa-9"
					>
						<v-card-title style="margin-bottom: 10px;">
							<img 
								src="../assets/antcovid.png" 
								alt="Todos Contra Covid!" 
								width="200" 
								class="mx-auto">
						</v-card-title>
						<v-card-text>
							<v-form
								ref="form"
								v-model="valid"
								lazy-validation
							>
								<v-text-field
									label="E-mail"
									name="email"
									type="email"
									autocomplete="email"
									v-model="email"
									required
									:rules="EmailRules"
									v-on:keyup.enter="validate"
									outlined
								>
									<template v-slot:append>
										<v-fade-transition leave-absolute>
											<v-icon color="primary">person</v-icon>
										</v-fade-transition>
									</template>
								</v-text-field>
								<v-text-field
									id="password"
									label="Senha"
									name="password"
									type="password"
									autocomplete="current-password"
									v-model="password"
									required
									:rules="passwordRules"
									v-on:keyup.enter="validate"
									outlined
								>
									<template v-slot:append>
										<v-fade-transition leave-absolute>
											<v-icon color="primary">lock</v-icon>
										</v-fade-transition>
									</template>
								</v-text-field>
								<v-btn
									elevation="5"
									color="primary"
									v-on:click="validate"
									v-on:keyup.enter="validate"
									block
									x-large
								>Acessar</v-btn>
								<v-spacer />
							</v-form>
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
		</v-container>
	</v-main>
</template>

<script>
	export default {
		data() {
			return {
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
				await this.$store
				.dispatch('login', { email: this.email, password: this.password })
				.then(() => this.$router.push('home'))
				.catch(error => this.stop());
			}
		}
	}
</script>

<style scoped>
	.v-card {
		border: 1px solid rgb(64 141 150);
		border-radius: 5px;
	}

	.theme--light.v-btn {
		color: white;
	}
</style>