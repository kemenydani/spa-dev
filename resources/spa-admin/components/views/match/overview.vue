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
							<v-flex xs12>
								<EnemyModelSelector v-model="edit.item.enemy_team_id" label="Select Opponent Team"></EnemyModelSelector>
							</v-flex>
							<v-flex xs12>
								<CategoryModelSelector :context="'game'" v-model="edit.item.game_id" label="Select Game"></CategoryModelSelector>
							</v-flex>
							<v-flex xs12>
								<EventModelSelector v-model="edit.item.event_id" label="Select Event"></EventModelSelector>
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
		
		<v-dialog v-model="maps.dialog" max-width="800px">
			<v-card>
				<v-card-title>
					<span class="headline">{{ maps.title }}</span>
				</v-card-title>
				<v-card-text>
					<v-layout wrap>
						<v-flex xs12 v-for="map in maps.items">
							<v-card color="grey lighten-3">
								<v-card-text>
									<v-layout row wrap>
										<v-flex xs-8>
											<label>Map name</label>
											<v-text-field prepend-icon="map" v-model="map.name"></v-text-field>
										</v-flex>

										<v-flex xs-2>
											<label>Home score</label>
											<v-text-field type="number" prepend-icon="exposure" style="text-align: center;" v-model="map.homeScore"></v-text-field>
										</v-flex>

										<v-flex xs-2>
											<label>Enemy score</label>
											<v-text-field type="number" prepend-icon="exposure" style="text-align: center;" v-model="map.enemyScore"></v-text-field>
										</v-flex>
									</v-layout>
								</v-card-text>
								<v-card-actions>
									<v-btn flat color="orange">REMOVE</v-btn>
								</v-card-actions>
							</v-card>
							<br>
						</v-flex>
					</v-layout>
				</v-card-text>
				<v-card-actions>
					<v-btn flat color="orange" @click="maps.items.push({ name: null, homeScore: 0, enemyScore: 0 })">ADD MAP +</v-btn>
					<v-spacer></v-spacer>
					<v-btn color="orange" flat @click.native="maps.dialog = false">Close</v-btn>
					<v-btn color="orange" flat @click.native="saveCloseModel(edit.item); maps.dialog = false">Save</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
		
		<v-btn
				@click="addModel"
				fab
				bottom
				right
				color="amber accent-3"
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
	import EventModelSelector from '../../EventModelSelector';
	import EnemyModelSelector from '../../EnemyModelSelector';
	
	export default {
		components: { DataModelManager, SquadModelSelector, CategoryModelSelector, EventModelSelector, EnemyModelSelector },
		data() {
			return {
				edit : {
					item : {},
					title : 'Manage',
					dialog: false,
					datePickerMenu: false,
				},
				maps : {
					items : [
						{ name: 'dust2', homeScore: 0, enemyScore: 0 },
            { name: 'dust3', homeScore: 0, enemyScore: 0 },
             { name: 'dust4', homeScore: 0, enemyScore: 0 }
					],
					title : 'Manage Maps',
					dialog: false,
				},
				table: {
					rowActions : [
						{
							name : 'Edit',
							icon : 'edit',
							callback : this.editModel
						},
						{
							name : 'Maps',
							icon : 'map',
							callback : this.editMaps
						},
					],
					actions : ['delete'],
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Squad', align: 'left', sortable: false, value: 'join2__name',},
						{ text: 'Enemy', align: 'left', sortable: false, value: 'join1__name',},
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
			editMaps( model ){
				this.maps.dialog = true;
				this.maps.title = 'Edit Maps';
				this.maps.item = Object.assign({}, model );
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