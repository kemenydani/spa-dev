<template>
	<v-content>
		<data-model-manager :model="table.model" :row-actions="table.rowActions" :headers="table.headers"></data-model-manager>
		
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
								<v-switch :true-value="'1'" :false-value="'0'" label="Active" v-model="edit.item.active"></v-switch>
							</v-flex>
							<v-flex xs6>
								<v-text-field type="number" label="Price" v-model="edit.item.price"></v-text-field>
							</v-flex>
							<v-flex xs6>
								<v-text-field label="Currency" disabled  v-model="edit.item.currency"></v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-switch :true-value="'1'" :false-value="'0'" label="Featured" v-model="edit.item.featured"></v-switch>
							</v-flex>
							<v-flex xs12>
								<v-switch :true-value="'1'" :false-value="'0'" label="Comments" v-model="edit.item.comments_allowed"></v-switch>
							</v-flex>
							<v-flex xs12>
								<v-text-field type="number" label="Items in stock" v-model="edit.item.in_stock"></v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-text-field textarea label="Description" v-model="edit.item.description"></v-text-field>
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
	import Product from '../../../model/Product';
	
	export default {
		components: { DataModelManager },
		data() {
			return {
				edit : {
					item : {},
					title : 'Manage',
					dialog: false
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
					model: new Product()
				},
			}
	
		},
		methods: {
			tableFetchData(){
				this.$app.$emit('tableFetchData');
			},
			addModel(){
				this.edit.dialog = true;
				this.edit.title = 'Add';
				this.edit.item = { name: '', price: 0, currency: 'EUR', active: false, in_stock: 0, description: '', featured: false, comments_allowed: true };
			},
			editModel( model ){
				this.edit.dialog = true;
				this.edit.title = 'Edit';
				this.edit.item = Object.assign({}, model );
			},
			saveCloseModel( model, dialog ){
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
				return (new Product).store( model )
			}
		}
	}
</script>

<style lang="scss">

</style>