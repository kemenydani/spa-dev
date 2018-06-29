<template>
	<v-app style="background: #eeeeee">
		
		<app-toaster></app-toaster>
		
		<v-container fluid fill-height app>
			<v-layout row style="display: flex; justify-content: center; align-items: center; align-content: center;" app>
				<v-flex xs12 sm3 offset-sm>
					<v-card app>
						<v-card-title app>
							<h2>Avenue Admin Login</h2>
						</v-card-title>
						<v-divider></v-divider>
						<v-card-text>
							<v-container>
								<form @submit.prevent="onLogin">
									<v-layout row>
										<v-flex xs12>
											<v-text-field
													name="email"
													label="Email"
													id="email"
													v-model="email"
													type="email"
													required></v-text-field>
										</v-flex>
									</v-layout>
									<v-layout row>
										<v-flex xs12>
											<v-text-field
													name="password"
													label="Password"
													id="password"
													v-model="password"
													type="password"
													required></v-text-field>
										</v-flex>
									</v-layout>
									
									<v-layout row>
										<v-flex xs12>
											<v-checkbox
													label="Remember Me"
													v-model="remember"
											></v-checkbox>
										</v-flex>
									</v-layout>
									
									<v-layout row>
										<v-flex xs12>
											<v-btn type="submit" :disabled="loading" :loading="loading">
												Sign in
												<span slot="loader" class="custom-loader">
	                          <v-icon light>cached</v-icon>
												</span>
											</v-btn>
										</v-flex>
									</v-layout>
								</form>
							</v-container>
						</v-card-text>
					</v-card>
				</v-flex>
			</v-layout>
		</v-container>
	</v-app>
</template>

<script>
	import AppToaster from '../../app-toaster';
	
	export default {
		components: { 'app-toaster' : AppToaster },
		data () {
			return {
				loading: false,
				email: '',
				password: '',
				remember: false
			}
		},
		methods: {
			onLogin ()
			{
				this.loading = true;
				
				this.$User.login( this.email, this.password, this.remember )
					.then( ( response ) => {
						this.loading = false;
						this.$app.$emit('toast', 'Logged in as Admin.', 'success');
						this.$router.push('/dashboard');
					})
					.catch( (error) => {
						this.$app.$emit('toast', 'Authorization failed. Please try again', 'error');
						this.loading = false;
					});
			}
		},
		created()
		{
			if( this.$User.isLogged() ) {
				this.$app.$emit('toast', ' Logged in automatically.', 'success');
				this.$router.push('/dashboard');
			}
		}

	}
</script>