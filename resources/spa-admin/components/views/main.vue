
<template>
	<v-app id="inspire" :dark="dark" :style="{ backgroundColor: (!dark ? '#eeeeee' : '') }">
		
		<app-toaster></app-toaster>
		
		<v-navigation-drawer
			fixed
			:mini-variant="mini"
			v-model="drawer"
			@mouseenter.native="toggleDrawerDelayed( $event )"
			@mouseleave.native="toggleDrawerDelayed( $event )"
			dark
			app>
			<div style="margin: 15px;">
				<v-switch v-model="dark"></v-switch>
			</div>
			<v-list dense>

				<router-link is="v-list-tile" :to="{ name: 'dashboard' }" exact>
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

				<router-link is="v-list-tile" :to="{ name: 'user.overview' }">
					<v-list-tile-action>
						<v-icon>person</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>User Management</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				<!--
				<router-link is="v-list-tile" :to="{ name: 'role.overview' }">
					<v-list-tile-action>
						<v-icon>vpn_key</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>User Roles</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				-->
				<router-link is="v-list-tile" :to="{ name: 'category.overview' }">
					<v-list-tile-action>
						<v-icon>folder</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Categories</v-list-tile-title>
					</v-list-tile-content>
				</router-link>

				<v-list-group
						:value="false"
				>
					<v-list-tile slot="activator">
						<v-list-tile-action>
							<v-icon>headset_mic</v-icon>
						</v-list-tile-action>
						<v-list-tile-title>Team</v-list-tile-title>
					</v-list-tile>

					<router-link is="v-list-tile" :to="{ name: 'squad.overview' }">
						<v-list-tile-action>
							<v-icon>headset_mic</v-icon>
						</v-list-tile-action>
						<v-list-tile-content>
							<v-list-tile-title>Squads</v-list-tile-title>
						</v-list-tile-content>
					</router-link>

					<router-link is="v-list-tile" :to="{ name: 'squad_member.overview' }">
						<v-list-tile-action>
							<v-icon>group_add</v-icon>
						</v-list-tile-action>
						<v-list-tile-content>
							<v-list-tile-title>Squad Members</v-list-tile-title>
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

				</v-list-group>

				<v-list-group
						:value="false"
				>
					<v-list-tile slot="activator">
						<v-list-tile-action>
							<v-icon>notes</v-icon>
						</v-list-tile-action>
						<v-list-tile-title>Content</v-list-tile-title>
					</v-list-tile>
					
					<router-link is="v-list-tile" :to="{ name: 'article.overview' }">
						<v-list-tile-action>
							<v-icon>font_download</v-icon>
						</v-list-tile-action>
						<v-list-tile-content>
							<v-list-tile-title>Articles</v-list-tile-title>
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

					<router-link is="v-list-tile" :to="{ name: 'comment.overview' }">
						<v-list-tile-action>
							<v-icon>chat</v-icon>
						</v-list-tile-action>
						<v-list-tile-content>
							<v-list-tile-title>Comments</v-list-tile-title>
						</v-list-tile-content>
					</router-link>

					<router-link is="v-list-tile" :to="{ name: 'partner.overview' }">
						<v-list-tile-action>
							<v-icon>contacts</v-icon>
						</v-list-tile-action>
						<v-list-tile-content>
							<v-list-tile-title>Partners</v-list-tile-title>
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

				</v-list-group>

				<v-list-group
						:value="false"
				>
					<v-list-tile slot="activator">
						<v-list-tile-action>
							<v-icon>local_grocery_store</v-icon>
						</v-list-tile-action>
						<v-list-tile-title>Shop</v-list-tile-title>
					</v-list-tile>

					<router-link is="v-list-tile" :to="{ name: 'product.overview' }">
						<v-list-tile-action>
							<v-icon>inbox</v-icon>
						</v-list-tile-action>
						<v-list-tile-content>
							<v-list-tile-title>Products</v-list-tile-title>
						</v-list-tile-content>
					</router-link>

					<router-link is="v-list-tile" :to="{ name: 'paypal.overview' }">
						<v-list-tile-action>
							<v-icon>attach_money</v-icon>
						</v-list-tile-action>
						<v-list-tile-content>
							<v-list-tile-title>PayPal Payments</v-list-tile-title>
						</v-list-tile-content>
					</router-link>

				</v-list-group>
				
			</v-list>
		</v-navigation-drawer>

		<v-toolbar color="primary" dark fixed app>
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
					<v-card style="max-width: 400px">
						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn flat @click="menu = false">Cancel</v-btn>
							<v-btn color="primary" flat @click="menu = false">Save</v-btn>
						</v-card-actions>
					</v-card>
				</v-menu>
				<v-btn icon @click="logout() ">
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
				locales: [],
				location: [],
				colors: []
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
			//background-color: #eee;
	}

</style>
