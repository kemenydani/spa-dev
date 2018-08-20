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
								<SquadModelSelector
										:vValidationRules="'required'"
										v-model="edit.item.squad_id"
										label="Select Squad">
								</SquadModelSelector>
							</v-flex>
							<v-flex xs12>
								<EnemyModelSelector
										:vValidationRules="'required'"
										v-model="edit.item.enemy_team_id"
										label="Select Opponent Team">
								</EnemyModelSelector>
							</v-flex>
							<v-flex xs12>
								<CategoryModelSelector
										:vValidationRules="'required'"
										:context="'game'"
										v-model="edit.item.game_id"
										label="Select Game">
								</CategoryModelSelector>
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
											v-validate="'required'"
											:error-messages="errors.collect('date_played')"
											data-vv-name="date_played"
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
					<v-btn color="blue darken-1" flat @click.native="saveCloseModel(edit.item); edit.dialog = false;">Save</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
		
		<v-dialog v-model="compose.dialog" max-width="800px">
			<v-card>
				<v-card-title>
					<span class="headline">{{ compose.title }}</span>
				</v-card-title>
				<v-card-text>
					<v-flex style="height: 40px; min-height: 40px; max-height: 40px; overflow: hidden;">
						<v-progress-linear v-if="compose.loading" :indeterminate="true"></v-progress-linear>
					</v-flex>
					<v-layout wrap>
						<v-flex xs12 v-for="m in compose.maps">
							<v-card color="grey lighten-3">
								<v-card-text>
									<v-layout row wrap>
										<v-flex xs-8>
											<label>Map name</label>
											<v-text-field placeholder="Map name" prepend-icon="map" v-model="m.name"></v-text-field>
										</v-flex>
										<v-flex xs-2>
											<label>Home score</label>
											<v-text-field type="number" prepend-icon="exposure" style="text-align: center;" v-model="m.score_home"></v-text-field>
										</v-flex>
										<v-flex xs-2>
											<label>Enemy score</label>
											<v-text-field type="number" prepend-icon="exposure" style="text-align: center;" v-model="m.score_enemy"></v-text-field>
										</v-flex>
									</v-layout>
								</v-card-text>
								<v-card-actions>
									<v-spacer></v-spacer><v-btn :disabled="compose.loading" flat @click.native="removeMap(m.id)" color="orange">REMOVE</v-btn>
								</v-card-actions>
							</v-card>
							<br>
						</v-flex>
					</v-layout>
				</v-card-text>
				<v-card-actions>
					<v-btn :disabled="compose.loading" flat color="orange" @click.native="addMap(compose.item.id)">ADD MAP +</v-btn>
					<v-spacer></v-spacer>
					<v-btn :disabled="compose.loading" flat @click.native="storeMaps()" color="orange">SAVE</v-btn>
					<v-btn :disabled="compose.loading" color="orange" flat @click.native="compose.dialog = false">Close</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
		
		<v-btn
				v-model="fab"
				color="primary"
				:bottom="true"
				:right="true"
				:direction="'top'"
				@click="addModel"
				fixed
				fab
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
		$_veeValidate: {
			validator: 'new'
		},
		components: { DataModelManager, SquadModelSelector, CategoryModelSelector, EventModelSelector, EnemyModelSelector },
		data() {
			return {
				pageHint: false,
				fab: true,
				edit : {
					item : {},
					title : 'Manage',
					dialog: false,
					datePickerMenu: false,
				},
				compose : {
					loading: true,
					maps: [],
					item : {},
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
		watch : {
			'edit.dialog' : {
				handler : function() {
					this.$validator.reset()
				},
				deep: true
			}
		},
		methods : {
			addMap(id){
                let data = { id: null, match_id: id, name: '', score_home: 0, score_enemy: 0 };
                this.compose.maps.push(data);
			},
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
                this.compose.loading = true;
				this.compose.dialog = true;
				this.compose.title = 'Edit Maps';
				this.compose.item = Object.assign({}, model );
				this.compose.maps = [];
				this.compose.loading = true;
				(new Match()).getMaps(model.id).then( ( response ) => {
				    this.compose.maps = [];
				    response.forEach( ( map ) => this.compose.maps.push(map) );
                    this.compose.loading = false;
				})
			},
			removeMap( id ){
                this.compose.loading = true;
                (new Match())
                    .deleteMap(id)
                    .then( ( response ) => {
                        this.compose.maps = [];
                        response.forEach( ( map ) => this.compose.maps.push(map) );
                        this.compose.loading = false;
                    });
			},
			storeMaps()
			{
                this.compose.loading = true;
				(new Match())
					.storeMaps(this.compose.maps)
					.then( ( response ) => {
                        this.compose.maps = [];
                        response.forEach( ( map ) => this.compose.maps.push(map) );
                        this.compose.loading = false;
					});
			},
			saveCloseModel( model, dialog )
			{
                if(!this.$validator.validate()) return false;

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