<template>
	<v-content>
		
		<data-model-manager :model="table.model" :row-actions="table.rowActions" :actions-allowed="table.actions" :headers="table.headers"></data-model-manager>
		
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
										v-validate="'required|max:50'"
										:error-messages="errors.collect('name')"
										:counter="50"
										data-vv-name="name"
										label="Name"
										v-model="edit.item.name"
										required>
								</v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-text-field
										v-validate="'required|max:10'"
										:error-messages="errors.collect('name_short')"
										:counter="10"
										data-vv-name="name_short"
										label="Short Name"
										v-model="edit.item.name_short"
										required>
								</v-text-field>
							</v-flex>
							<v-flex xs12>
								<ContextModelSelector
										:update="edit.dialog"
										:vValidationRules="'required'"
										v-validate="'required'"
										:error-messages="errors.collect('context')"
										data-vv-name="context"
										v-model="edit.item.context"
										required
										label="Select Context">
								</ContextModelSelector>
							</v-flex>
						</v-layout>
					</v-container>
					<small>*indicates required field</small>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="blue darken-1" flat @click.native="edit.dialog = false">Close</v-btn>
					<v-btn color="blue darken-1" flat @click.native="saveCloseModel(edit.item);">Save</v-btn>
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
	import Category from '../../../model/Category';
	import ContextModelSelector from '../../ContextModelSelector';
	
	export default {
		$_veeValidate: {
			validator: 'new'
		},
		components: { DataModelManager, ContextModelSelector },
		data() {
			return {
				pageHint: false,
				fab: true,
				contexts: [],
				edit : {
					errors : {
						name: '',
						name_short: '',
						context : ''
					},
					item : {},
					title : 'Manage',
					dialog: false
				},
				table: {
					actions : ['delete'],
					rowActions : [
						{
							name : 'Edit',
							icon : 'edit',
							callback : this.editModel
						}
					],
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Name', value: 'name', sortable: true, align: 'left' },
						{ text: 'Short', value: 'name_short', sortable: true, align: 'left' },
						{ text: 'Context', value: 'context', sortable: true, align: 'center' },
					],
					model: new Category()
				},
			}
		},
		watch : {
			'edit.dialog' : {
				handler : function(v) {
					this.$validator.reset()
				},
				deep: true
			}
		},
		mounted () {
			this.$validator.localize('en', this.dictionary)
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
				this.$validator.validateAll();
				return false;
				this.$app.$emit('toast', 'Saving...', 'info');
				
				this.storeModel( model )
					.then((response) => {
						if(response.success) {
							this.$app.$emit('toast', 'Saved : ' + response.data.title  , 'success');
						} else {
							this.$app.$emit('toast', 'Save failed: ' + response.data.title, 'error');
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
				return (new Category).store( model )
			},
		}

	}
</script>

<style lang="scss">

</style>