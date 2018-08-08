<template>
	<v-content>
		<data-model-manager :model="table.model" :row-actions="table.rowActions" :actions-allowed="table.actions"  :headers="table.headers"></data-model-manager>
		
		<v-dialog v-model="edit.dialog" max-width="800px">
			<v-card>
				<v-card-title>
					<span class="headline">{{ edit.title }}</span>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md>
						<v-layout wrap>
							<v-flex xs12>
								<v-text-field
										v-validate="'required|max:100'"
										:error-messages="errors.collect('event_name')"
										:counter="100"
										data-vv-name="event_name"
										label="Name"
										v-model="edit.item.event_name"
										required>
								</v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-text-field
										v-validate="'required|max:1000'"
										:error-messages="errors.collect('event_name')"
										:counter="1000"
										data-vv-name="event_name"
										textarea
										label="About this award"
										v-model="edit.item.description">
								</v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-text-field
										v-validate="'required'"
										:error-messages="errors.collect('place')"
										data-vv-name="place"
										label="Place"
										v-model="edit.item.place"
										required>
								</v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-menu
										ref="datePickerMenu"
										:close-on-content-click="false"
										v-model="edit.datePickerMenu"
										:nudge-right="40"
										:return-value.sync="edit.item.award_date"
										lazy
										transition="scale-transition"
										offset-y
										full-width
										min-width="290px"
								>
									<v-text-field
											v-validate="'required'"
											:error-messages="errors.collect('award_date')"
											data-vv-name="award_date"
											slot="activator"
											v-model="edit.item.award_date"
											label="Picker in menu"
											prepend-icon="event"
											readonly
									></v-text-field>
									<v-date-picker v-model="edit.item.award_date" no-title scrollable>
										<v-spacer></v-spacer>
										<v-btn flat color="primary" @click="edit.datePickerMenu = false">Cancel</v-btn>
										<v-btn flat color="primary" @click="$refs.datePickerMenu.save(edit.item.award_date)">OK</v-btn>
									</v-date-picker>
								</v-menu>
							</v-flex>
							<v-flex xs12>
								<SquadModelSelector v-model="edit.item.squad_id" label="Select Squad"></SquadModelSelector>
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
	import Award from '../../../model/Award';
	import SquadModelSelector from '../../SquadModelSelector';
	
	export default {
		$_veeValidate: {
			validator: 'new'
		},
		components: { DataModelManager, SquadModelSelector },
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
				table: {
					rowActions : [
						{
							name : 'Edit',
							icon : 'edit',
							callback : this.editModel
						},
					],
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Name', value: 'event_name', sortable: true, align: 'left' },
						{ text: 'Place', value: 'place', sortable: true, align: 'center' },
						{ text: 'Date', value: 'award_date', sortable: true, align: 'right', width: '200px' },
					],
					model: new Award()
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
			tableFetchData(){
				this.$app.$emit('tableFetchData');
			},
			addModel(){
				this.edit.dialog = true;
				this.edit.title = 'Add';
				this.edit.item = { event_name: '', place: '', squad_id: null, active: false, description: '', event_id: null };
			},
			editModel( model ){
				this.edit.dialog = true;
				this.edit.title = 'Edit';
				this.edit.item = Object.assign({}, model );
			},
			saveCloseModel( model, dialog )
			{
                if(!this.$validator.validate()) return false;

				this.$app.$emit('toast', 'Saving...', 'info');
				
				this.storeModel( model )
					.then((response) => {
						if(response.success) {
							this.$app.$emit('toast', 'Saved : ' + response.data.event_name  , 'success');
						} else {
							this.$app.$emit('toast', 'Save failed: ' + response.event_name, 'error');
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
				return (new Award).store( model )
			}
		}
		
	}
</script>

<style lang="scss">

</style>