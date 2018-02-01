
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
						<v-list-tile-title>User Management</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<router-link is="v-list-tile" :to="{ name: 'role.overview' }">
					<v-list-tile-action>
						<v-icon>transfer_within_a_station</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>User Roles</v-list-tile-title>
					</v-list-tile-content>
				</router-link>
				
				<router-link is="v-list-tile" :to="{ name: 'category.overview' }">
					<v-list-tile-action>
						<v-icon>library_books</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Content Categories</v-list-tile-title>
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
						v-model="menu"
				>
					<v-btn icon dark slot="activator">
						<v-icon dark language>message</v-icon>
					</v-btn>
					
					<v-card style="max-width: 400px">
						
						<v-list three-line>
							<template v-for="item in items">
								<v-subheader v-if="item.header" v-text="item.header"></v-subheader>
								<v-divider v-else-if="item.divider" v-bind:inset="item.inset"></v-divider>
								<v-list-tile avatar v-else v-bind:key="item.title" @click="">
									<v-list-tile-avatar>
										<img v-bind:src="item.avatar"/>
									</v-list-tile-avatar>
									<v-list-tile-content>
										<v-list-tile-title v-html="item.title"></v-list-tile-title>
										<v-list-tile-sub-title v-html="item.subtitle"></v-list-tile-sub-title>
									</v-list-tile-content>
								</v-list-tile>
							</template>
						</v-list>
						
						
						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn flat @click="menu = false">Cancel</v-btn>
							<v-btn color="primary" flat @click="menu = false">Save</v-btn>
						</v-card-actions>
					</v-card>
				</v-menu>
				
				
				
				
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
				
				items: [
					{ header: 'Today' },
					{ avatar: 'https://scontent.ftxl1-1.fna.fbcdn.net/v/t1.0-9/11261407_1024234314285914_3210054362884355031_n.jpg?oh=d65cd194ad3fe1a3b84ca25383227466&oe=5ADEF36F', title: 'Brunch this weekend?', subtitle: "<span class='grey--text text--darken-2'>Ali Connors</span> — I'll be in your neighborhood doing errands this weekend. Do you want to hang out?" },
					{ divider: true, inset: true },
					{ avatar: 'https://scontent.ftxl1-1.fna.fbcdn.net/v/t1.0-9/11261407_1024234314285914_3210054362884355031_n.jpg?oh=d65cd194ad3fe1a3b84ca25383227466&oe=5ADEF36F', title: 'Summer BBQ <span class="grey--text text--lighten-1">4</span>', subtitle: "<span class='grey--text text--darken-2'>to Alex, Scott, Jennifer</span> — Wish I could come, but I'm out of town this weekend." },
					{ divider: true, inset: true },
					{ avatar: 'https://scontent.ftxl1-1.fna.fbcdn.net/v/t1.0-9/11261407_1024234314285914_3210054362884355031_n.jpg?oh=d65cd194ad3fe1a3b84ca25383227466&oe=5ADEF36F', title: 'Oui oui', subtitle: "<span class='grey--text text--darken-2'>Sandra Adams</span> — Do you have Paris recommendations? Have you ever been?" },
					{ divider: true, inset: true },
					{ avatar: 'https://scontent.ftxl1-1.fna.fbcdn.net/v/t1.0-9/11261407_1024234314285914_3210054362884355031_n.jpg?oh=d65cd194ad3fe1a3b84ca25383227466&oe=5ADEF36F', title: 'Birthday gift', subtitle: "<span class='grey--text text--darken-2'>Trevor Hansen</span> — Have any ideas about what we should get Heidi for her birthday?" },
					{ divider: true, inset: true },
					{ avatar: 'https://scontent.ftxl1-1.fna.fbcdn.net/v/t1.0-9/11261407_1024234314285914_3210054362884355031_n.jpg?oh=d65cd194ad3fe1a3b84ca25383227466&oe=5ADEF36F', title: 'Recipe to try', subtitle: "<span class='grey--text text--darken-2'>Britta Holt</span> — We should eat this: Grate, Squash, Corn, and tomatillo Tacos." },
				],
				
				fav: true,
				menu: false,
				message: false,
				hints: true,
				
				
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
