
<template>
	<v-app id="inspire" :dark="dark">
		<v-navigation-drawer
				fixed
				:mini-variant="mini"
				v-model="drawer"
				@mouseenter.native="toggleDrawerDelayed( $event )"
				@mouseleave.native="toggleDrawerDelayed( $event )"
				app
		>
			<v-switch v-model="dark"></v-switch>
			
			<v-list dense>
				
				<router-link is="v-list-tile" :to="{ name: 'dashboard' }">
					<v-list-tile-action>
						<v-icon>home</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Home</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<router-link is="v-list-tile" :to="{ name: 'settings.overview' }">
					<v-list-tile-action>
						<v-icon>settings</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Page Settings</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<v-divider inset></v-divider>
				
				<router-link is="v-list-tile" :to="{ name: 'article.list' }">
					<v-list-tile-action>
						<v-icon>font_download</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Articles</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<router-link is="v-list-tile" :to="{ name: 'user.list' }">
					<v-list-tile-action>
						<v-icon>supervisor_account</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Users</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<router-link is="v-list-tile" :to="{ name: 'role.overview' }">
					<v-list-tile-action>
						<v-icon>no_encryption</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Roles</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
			
			</v-list>
		</v-navigation-drawer>
		
		
		<v-toolbar color="red" dark fixed app>
			<v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
			<v-toolbar-title>{{ $route.meta.title }}</v-toolbar-title>
			<div class="d-flex align-center" style="margin-left: auto">
				
				<span>{{ $User.getName() }}</span>
				
				<v-menu offset-y>
					<v-btn icon dark slot="activator">
						<v-icon dark language>language</v-icon>
					</v-btn>
					<v-list>
						<v-list-tile v-for="lang in locales" :key="lang" @mouseover.native="changeLocale(lang)">
							<v-list-tile-title> {{lang}} </v-list-tile-title>
						</v-list-tile>
					</v-list>
				</v-menu>
				<v-btn icon>
					<v-icon>message</v-icon>
				</v-btn>
				<v-btn icon>
					<v-icon on>notifications</v-icon>
				</v-btn>
				<v-btn icon @click=" logout() ">
					<v-icon>exit_to_app</v-icon>
				</v-btn>
			</div>
		</v-toolbar>
		<v-content>
			<v-container fluid fill-height>
				<router-view></router-view>
			</v-container>
		</v-content>
	</v-app>
</template>

<script>
	import UserDataService from '../../services/UserDataService';
	
	export default {
		name: 'app-main',
		props: {
			source: String
		},
		data () {
			return {
				dialog: false,
				dark: false,
				theme: 'primary',
				mini: true,
				drawer: true,
				locales: ['en-US', 'zh-CN'],
				location: '',
				colors: ['blue', 'green', 'purple', 'red']
			}
		},
		methods: {
			logout(){
				this.$User.logout().then( () => {
					if( !this.$User.isLogged() ) this.$router.push('/login');
				})
			},
			toggleDrawerDelayed( event )
			{
				let dimensions = event.target.getBoundingClientRect();
				let mouseX = event.clientX;
				let mouseY = event.clientY;
				
				let isInside = () => {
					return (mouseX >= dimensions.left && mouseX <= dimensions.right && mouseY >= dimensions.top && mouseY <= dimensions.bottom);
				};
				
				let trackMouse = moveEvent =>
				{
					mouseX = moveEvent.clientX;
					mouseY = moveEvent.clientY;
					
					// If mouse leaves after tracking started, cancel tracking
					if(!isInside()) document.removeEventListener(moveEvent.type, trackMouse);
				};
				
				document.addEventListener('mousemove', trackMouse );
				
				setTimeout( () =>
				{
					this.mini = !isInside();
					document.removeEventListener('mousemove', trackMouse );
				}, 650)
			}
		}
	}
</script>

<style lang="scss">


</style>
