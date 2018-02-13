<template>
	<v-content>
		
		<v-layout column>
			<v-flex xs12 sm12>
				<v-container fluid grid-list-md>
					<v-layout row wrap>
						<squad-item v-for="card in cards" :squad="card" :key="card.name"></squad-item>
					</v-layout>
				</v-container>
			</v-flex>
		</v-layout>
		
		
		
		<v-dialog v-model="dialog" width="600px">
			<v-card>
				<v-card-title
						class="grey lighten-4 py-4 title"
				>
					Manage Members
				</v-card-title>
				<v-container grid-list-sm class="pa-4">
					<v-layout row wrap>
						<v-flex xs12 align-center justify-space-between>
							<v-layout align-center>
								<v-avatar size="40px" class="mr-3">
									<img
											src="//ssl.gstatic.com/s2/oz/images/sge/grey_silhouette.png"
											alt=""
									>
								</v-avatar>
								<v-text-field
										placeholder="Name"
								></v-text-field>
							</v-layout>
						</v-flex>
					</v-layout>
				</v-container>
				<v-card-actions>
				
					<v-spacer></v-spacer>
					<v-btn flat color="primary" @click="dialog = false">Cancel</v-btn>
					<v-btn flat @click="dialog = false">Save</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
		
	</v-content>
</template>

<script>
	
	import Squad from '../../../model/Squad';
	import SquadItem from '../../../components/views/squad/squad';
	
	export default {
		name: 'squad-overview',
		components: { SquadItem },
		data() {
			return {
				dialog: false,
				cards: [
				
				],
				data_field: 'data_field'
			}
		},
		methods: {
			fetchData(){
				(new Squad()).all().then( ( squads ) => {
					squads.forEach( squad => {
						squad.src = 'https://images7.alphacoders.com/587/587593.png';
					});
					this.cards = squads;
				})
			}
		},
		mounted()
		{
			this.fetchData();
		}
	}
</script>

<style lang="scss">

</style>