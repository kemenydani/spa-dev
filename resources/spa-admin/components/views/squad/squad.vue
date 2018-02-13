<template>
	<v-flex sm6>
		<v-card>
			<v-card-media :src="squad.src" height="140px">
				<v-container fill-height fluid>
					<v-layout fill-height>
						<v-flex xs12 align-end flexbox>
							<span class="headline white--text" v-text="squad.name"></span>
						</v-flex>
					</v-layout>
				</v-container>
			</v-card-media>
			<v-card-actions class="white">
				<v-btn @click.stop="dialog = !dialog" icon fab small color="red">
					<v-icon color="white">add</v-icon>
				</v-btn>
				
				<span v-for="member in squad.members">
					<v-icon color="red darken-1">person</v-icon>
				</span>
				
				<v-spacer></v-spacer>
				<router-link is="v-btn" :to="{ name: 'squad.update', params: { id: squad.id } }" icon fab secondary
				             small>
					<v-icon>settings</v-icon>
				</router-link>
			</v-card-actions>
		</v-card>
	</v-flex>
</template>

<script>
	export default {
		data() {
			return {
			
			}
		},
		components: {},
		props: {
			squad: {
				type: Object,
				required: true
			}
		},
		methods: {},
		mounted() {
			console.log('Component mounted.')
		}
	}
</script>

<style>

</style>