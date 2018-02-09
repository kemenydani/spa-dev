<template>
	<v-content grid-list-md>
		<v-layout row wrap>
			<v-flex sm6>
				<v-card>
					<v-card-title>Latest Matches</v-card-title>
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
			
			<v-flex sm6>
				<v-card>
					<v-card-title>Upcoming Matches</v-card-title>
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
	</v-content>
</template>

<script>
	
	export default {
		name: 'squad-overview',
		data() {
			return {
				items: [
					{ active: true, title: 'Jason Oner', avatar: 'https://scontent.ftxl1-1.fna.fbcdn.net/v/t1.0-9/11261407_1024234314285914_3210054362884355031_n.jpg?oh=d65cd194ad3fe1a3b84ca25383227466&oe=5ADEF36F' },
					{ active: true, title: 'Ranee Carlson', avatar: 'https://scontent.ftxl1-1.fna.fbcdn.net/v/t1.0-9/11261407_1024234314285914_3210054362884355031_n.jpg?oh=d65cd194ad3fe1a3b84ca25383227466&oe=5ADEF36F' },
					{ title: 'Cindy Baker', avatar: 'https://scontent.ftxl1-1.fna.fbcdn.net/v/t1.0-9/11261407_1024234314285914_3210054362884355031_n.jpg?oh=d65cd194ad3fe1a3b84ca25383227466&oe=5ADEF36F' },
					{ title: 'Ali Connors', avatar: 'https://scontent.ftxl1-1.fna.fbcdn.net/v/t1.0-9/11261407_1024234314285914_3210054362884355031_n.jpg?oh=d65cd194ad3fe1a3b84ca25383227466&oe=5ADEF36F' },
				],
				data_field: 'data_field'
			}
		},
		mounted() {
			console.log('new component mounted');
		}
	}
</script>

<style lang="scss">

</style>