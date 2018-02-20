
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
				
				<router-link is="v-btn" :to="{ name: 'squad.update', params: { id: squad.id } }" icon fab small color="red">
					<v-icon color="white">edit</v-icon>
				</router-link>
				
				<span v-for="member in squad.members">
					<v-icon color="red darken-1">person</v-icon>
				</span>
				
			</v-card-actions>
		</v-card>
	</v-flex>
</template>

<script>
	export default {
		data()
		{
			return {
			
			}
		},
		components: {},
		props:
		{
			squad:
			{
				type: Object,
				required: true
			}
		},
		methods:
		{
			onAddMember()
			{
			
			}
		},
		mounted()
		{
			console.log('Component mounted.')
		}
	}
</script>

<style>

</style>