<template>
	<v-content>
		
		<v-layout row>
			<v-flex sm5>
				<v-card>
					<v-card-title>New Users</v-card-title>
					<v-list subheader>
						<v-list-tile avatar v-for="item in items" v-bind:key="item.title" @click="">
							<v-list-tile-avatar>
								<img v-bind:src="item.avatar"/>
							</v-list-tile-avatar>
							<v-list-tile-content>
								<v-list-tile-title v-html="item.title"></v-list-tile-title>
							</v-list-tile-content>
							<v-list-tile-action>
								<v-icon v-bind:color="item.active ? 'teal' : 'grey'">chat_bubble</v-icon>
							</v-list-tile-action>
						</v-list-tile>
					</v-list>
				</v-card>
			</v-flex>
			<v-spacer></v-spacer>
			<v-flex sm5>
				<v-card>
					<v-card-title>Top Users</v-card-title>
					<v-list subheader>
						<v-list-tile avatar v-for="item in items" v-bind:key="item.title" @click="">
							<v-list-tile-avatar>
								<img v-bind:src="item.avatar"/>
							</v-list-tile-avatar>
							<v-list-tile-content>
								<v-list-tile-title v-html="item.title"></v-list-tile-title>
							</v-list-tile-content>
							<v-list-tile-action>
								<v-icon v-bind:color="item.active ? 'teal' : 'grey'">chat_bubble</v-icon>
							</v-list-tile-action>
						</v-list-tile>
					</v-list>
				</v-card>
			</v-flex>
		</v-layout>
		
		<v-speed-dial
				bottom
				right
				:direction="direction"
				:hover="hover"
				:transition="transition"
				fixed
				v-model="fab"
		>
			<v-btn
					slot="activator"
					color="red"
					dark
					fab
					v-model="fab"
			>
				<v-icon>dehaze</v-icon>
				<v-icon>clear_all</v-icon>
			</v-btn>
			<router-link is="v-btn" :to="{ name: 'user.list' }"
					fab
					dark
					small
					color="blue"
			>
				<v-icon>list</v-icon>
			</router-link>
			<router-link is="v-btn" :to="{ name: 'user.create' }"
					fab
					dark
					small
					color="green"
			>
				<v-icon>add</v-icon>
			</router-link>
		</v-speed-dial>
		
</v-content>
</template>

<script>
	
	export default {
		name: 'user-overview',
		data() {
			return {
				items: [
					{ active: true, title: 'Jason Oner', avatar: 'https://scontent.ftxl1-1.fna.fbcdn.net/v/t1.0-9/11261407_1024234314285914_3210054362884355031_n.jpg?oh=d65cd194ad3fe1a3b84ca25383227466&oe=5ADEF36F' },
					{ active: true, title: 'Ranee Carlson', avatar: 'https://scontent.ftxl1-1.fna.fbcdn.net/v/t1.0-9/11261407_1024234314285914_3210054362884355031_n.jpg?oh=d65cd194ad3fe1a3b84ca25383227466&oe=5ADEF36F' },
					{ title: 'Cindy Baker', avatar: 'https://scontent.ftxl1-1.fna.fbcdn.net/v/t1.0-9/11261407_1024234314285914_3210054362884355031_n.jpg?oh=d65cd194ad3fe1a3b84ca25383227466&oe=5ADEF36F' },
					{ title: 'Ali Connors', avatar: 'https://scontent.ftxl1-1.fna.fbcdn.net/v/t1.0-9/11261407_1024234314285914_3210054362884355031_n.jpg?oh=d65cd194ad3fe1a3b84ca25383227466&oe=5ADEF36F' },
				],
				direction: "top",
				fab: true,
				fling: false,
				hover: false,
				tabs: null,
				top: false,
				right: true,
				bottom: true,
				left: false,
				transition: 'slide-y-reverse-transition',
				data_field: 'data_field',
			}
		},
		mounted() {
			console.log('new component mounted');
		}
	}
</script>

<style lang="scss">

</style>