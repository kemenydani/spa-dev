
<template>
	<v-app id="inspire" :dark="dark">
		
		<app-toaster></app-toaster>
		
		<v-navigation-drawer
			fixed
			:mini-variant="mini"
			v-model="drawer"
			@mouseenter.native="toggleDrawerDelayed( $event )"
			@mouseleave.native="toggleDrawerDelayed( $event )"
			app>
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
				
				<router-link is="v-list-tile" :to="{ name: 'category.overview' }">
					<v-list-tile-action>
						<v-icon>notes</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Categories</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<router-link is="v-list-tile" :to="{ name: 'gallery.overview' }">
					<v-list-tile-action>
						<v-icon>photo</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Galleries</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<router-link is="v-list-tile" :to="{ name: 'product.overview' }">
					<v-list-tile-action>
						<v-icon>monetization_on</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Products</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<router-link is="v-list-tile" :to="{ name: 'comment.overview' }">
					<v-list-tile-action>
						<v-icon>chat</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Comments</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<v-divider inset></v-divider>
				
				<router-link is="v-list-tile" :to="{ name: 'user.overview' }">
					<v-list-tile-action>
						<v-icon>person</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>User Management</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<router-link is="v-list-tile" :to="{ name: 'role.overview' }">
					<v-list-tile-action>
						<v-icon>vpn_key</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>User Roles</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<v-divider inset></v-divider>
				
				<router-link is="v-list-tile" :to="{ name: 'squad.overview' }">
					<v-list-tile-action>
						<v-icon>headset_mic</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Squads</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<router-link is="v-list-tile" :to="{ name: 'enemy.overview' }">
					<v-list-tile-action>
						<v-icon>games</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Opponents</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<router-link is="v-list-tile" :to="{ name: 'event.overview' }">
					<v-list-tile-action>
						<v-icon>today</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Events</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<router-link is="v-list-tile" :to="{ name: 'match.overview' }">
					<v-list-tile-action>
						<v-icon>reorder</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Matches</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<router-link is="v-list-tile" :to="{ name: 'award.overview' }">
					<v-list-tile-action>
						<v-icon>star</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Awards</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
			</v-list>
		</v-navigation-drawer>
		
		<v-toolbar color="red" dark fixed app>
			<v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
			<v-toolbar-title>{{ $route.meta.title }}</v-toolbar-title>
			<div class="d-flex align-center" style="margin-left: auto">
				<span>{{ $User.getName() }}</span>
				<v-menu
						offset-x
						offset-y
						:close-on-content-click="false"
						:nudge-width="200"
						v-model="menu">
					<v-btn icon dark slot="activator">
						<v-icon dark language>message</v-icon>
					</v-btn>
					<v-card style="max-width: 400px">
						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn flat @click="menu = false">Cancel</v-btn>
							<v-btn color="primary" flat @click="menu = false">Save</v-btn>
						</v-card-actions>
					</v-card>
				</v-menu>
				<v-btn icon @click=" logout() ">
					<v-icon>exit_to_app</v-icon>
				</v-btn>
			</div>
		</v-toolbar>
		<!-- route content -->
		<v-content>
			<v-container fluid fill-height>
				<div v-show="loading" class="">loading</div>
				<router-view v-show="!loading"></router-view>
			</v-container>
		</v-content>
	</v-app>
</template>

<script>

	import AppToaster from '../app-toaster';
	
	export default {
		components: { 'app-toaster' : AppToaster },
		name: 'app-main',
		props: {
			source: String
		},
		data () {
			return {
				loading : false,
				fav: true,
				menu: false,
				message: false,
				hints: true,
				dialog: false,
				dark: false,
				theme: 'primary',
				mini: false,
				drawer: true,
				locales: ['en-US', 'zh-CN'],
				location: '',
				colors: ['blue', 'green', 'purple', 'red']
			}
		},
		created(){
		
		},
		methods: {
			logout(){
				this.$User.logout().then( () => {
					if( !this.$User.isLogged() ) this.$router.push('/login');
				})
			},
			toggleDrawerDelayed( event )
			{
				/*
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
				*/
			}
		}
	}
</script>

<style lang="scss">
	#inspire {
			background-color: #eee;
	}

</style>
