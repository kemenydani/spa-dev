<template>
	<v-content>
		<data-model-manager :row-actions="table.rowActions"  :model="table.model" :actions-allowed="table.actions"  :headers="table.headers"></data-model-manager>
		
		<v-dialog v-model="edit.dialog" max-width="800px">
			<v-card>
				<v-card-title>
					<span class="headline">{{ edit.title }}</span>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md>
						<v-layout wrap>
							<v-flex xs12>
								<SquadModelSelector v-model="edit.item.squad_id" label="Select Squad"></SquadModelSelector>
							</v-flex>
							<!-- enemyselector ... -->
							<v-flex xs12>
								<CategoryModelSelector :context="'game'" v-model="edit.item.game_id" label="Select Game"></CategoryModelSelector>
							</v-flex>
							<v-flex xs12>
								<EventModelSelector v-model="edit.item.event_id" label="Select Squad"></EventModelSelector>
							</v-flex>
							<v-flex xs12>
								<v-switch :true-value="'1'" :false-value="'0'" label="Featured" v-model="edit.item.featured"></v-switch>
							</v-flex>
							<v-flex xs12>
								<v-menu
										ref="datePickerMenu"
										:close-on-content-click="false"
										v-model="edit.datePickerMenu"
										:nudge-right="40"
										:return-value.sync="edit.item.date_played"
										lazy
										transition="scale-transition"
										offset-y
										full-width
										min-width="290px"
								>
									<v-text-field
											slot="activator"
											v-model="edit.item.date_played"
											label="Picker in menu"
											prepend-icon="event"
											readonly
									></v-text-field>
									<v-date-picker v-model="edit.item.date_played" no-title scrollable>
										<v-spacer></v-spacer>
										<v-btn flat color="primary" @click="edit.datePickerMenu = false">Cancel</v-btn>
										<v-btn flat color="primary" @click="$refs.datePickerMenu.save(edit.item.date_played)">OK</v-btn>
									</v-date-picker>
								</v-menu>
							</v-flex>
						</v-layout>
					</v-container>
					<small>*indicates required field</small>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="blue darken-1" flat @click.native="edit.dialog = false">Close</v-btn>
					<v-btn color="blue darken-1" flat @click.native="saveCloseModel(edit.item); edit.dialog = false">Save</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
		
		<v-btn
				@click="addModel"
				fab
				bottom
				right
				color="red"
				dark
				fixed
		>
			<v-icon>add</v-icon>
		</v-btn>
	
	</v-content>
</template>

<script>
	
	import DataModelManager from '../../DataModelManager';
	import Match from '../../../model/Match';
	import SquadModelSelector from '../../SquadModelSelector';
	import CategoryModelSelector from '../../CategoryModelSelector';
	
	export default {
		components: { DataModelManager, SquadModelSelector, CategoryModelSelector },
		data() {
			return {
				edit : {
					item : {},
					title : 'Manage',
					dialog: false,
					datePickerMenu: false,
				},
				table: {
					actions : ['delete'],
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Date', value: 'date_played', sortable: true, align: 'right' },
					],
					model: new Match()
				},
			}
		},
		methods : {
			tableFetchData(){
				this.$app.$emit('tableFetchData');
			},
			addModel(){
				this.edit.dialog = true;
				this.edit.title = 'Add';
				this.edit.item = { squad_id: null, event_id: null, enemy_team_id: null, game_id: null, featured: false, date_played: null  };
			},
			editModel( model ){
				this.edit.dialog = true;
				this.edit.title = 'Edit';
				this.edit.item = Object.assign({}, model );
			},
			saveCloseModel( model, dialog )
			{
				this.$app.$emit('toast', 'Saving...', 'info');
				
				this.storeModel( model )
					.then((response) => {
						if(response.success) {
							this.$app.$emit('toast', 'Saved : ' + response.data.id  , 'success');
						} else {
							this.$app.$emit('toast', 'Save failed: ' + response.data.id, 'error');
						}
					})
					.catch( (error) => {
						this.$app.$emit('toast', 'Database Error', 'error');
					})
					.finally(() => {
						this.edit.item = {};
						this.tableFetchData();
					});
				dialog = false;
			},
			storeModel( model ){
				return (new Match).store( model )
			}
		}
	}
</script>

<style lang="scss">

</style>