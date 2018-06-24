<template>
	<v-content>
		<data-model-manager :row-actions="table.rowActions" :model="table.model" :headers="table.headers"></data-model-manager>
		
		<v-dialog v-model="edit.dialog" max-width="800px">
			<v-card>
				<v-card-title>
					<span class="headline">{{ edit.title }}</span>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md>
						<v-layout wrap>
							<v-flex xs12>
								<v-text-field label="Name" v-model="edit.item.name" required></v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-text-field label="Website" v-model="edit.item.website"></v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-switch :true-value="'1'" :false-value="'0'" label="Active" v-model="edit.item.active"></v-switch>
							</v-flex>
							<v-flex xs12>
								<v-switch :true-value="'1'" :false-value="'0'" label="Commentable" v-model="edit.item.comments_enabled"></v-switch>
							</v-flex>
							<v-flex xs12>
								<v-menu
										ref="startDatePickerMenu"
										:close-on-content-click="false"
										v-model="edit.startDatePickerMenu"
										:nudge-right="40"
										:return-value.sync="edit.item.start_date"
										lazy
										transition="scale-transition"
										offset-y
										full-width
										min-width="290px"
								>
									<v-text-field
											slot="activator"
											v-model="edit.item.start_date"
											label="Picker in menu"
											prepend-icon="event"
											readonly
									></v-text-field>
									<v-date-picker v-model="edit.item.start_date" no-title scrollable>
										<v-spacer></v-spacer>
										<v-btn flat color="primary" @click="edit.startDatePickerMenu = false">Cancel</v-btn>
										<v-btn flat color="primary" @click="$refs.startDatePickerMenu.save(edit.item.start_date)">OK</v-btn>
									</v-date-picker>
								</v-menu>
							</v-flex>
							<v-flex xs12>
								<v-menu
										ref="endDatePickerMenu"
										:close-on-content-click="false"
										v-model="edit.endDatePickerMenu"
										:nudge-right="40"
										:return-value.sync="edit.item.end_date"
										lazy
										transition="scale-transition"
										offset-y
										full-width
										min-width="290px"
								>
									<v-text-field
											slot="activator"
											v-model="edit.item.end_date"
											label="Picker in menu"
											prepend-icon="event"
											readonly
									></v-text-field>
									<v-date-picker v-model="edit.item.end_date" no-title scrollable>
										<v-spacer></v-spacer>
										<v-btn flat color="primary" @click="edit.endDatePickerMenu = false">Cancel</v-btn>
										<v-btn flat color="primary" @click="$refs.endDatePickerMenu.save(edit.item.end_date)">OK</v-btn>
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
	import Event from '../../../model/Event';
	
	export default {
		components: { DataModelManager },
		data() {
			return {
				edit : {
					item : {},
					title : 'Manage',
					dialog: false,
					startDatePickerMenu: false,
					endDatePickerMenu: false,
				},
				table: {
					rowActions : [
						{
							name : 'Edit',
							icon : 'edit',
							callback : this.editModel
						},
						{
							name : 'Image',
							icon : 'image',
							callback : function(){}
						}
					],
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Name', value: 'name', sortable: true, align: 'left' },
						{
							text: 'Activated',
							value: 'active',
							sortable: true,
							align: 'right',
							width: '200px',
							format: function( value, values ){
								return value == 1 ? 'Active' : 'Inactive';
							}
						},
					],
					model: new Event()
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
				this.edit.item = { name: '', active: false };
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
							this.$app.$emit('toast', 'Saved : ' + response.data.name  , 'success');
						} else {
							this.$app.$emit('toast', 'Save failed: ' + response.data.name, 'error');
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
				return (new Event).store( model )
			}
		}
	}
</script>

<style lang="scss">

</style>