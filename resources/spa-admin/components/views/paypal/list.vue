<template>
	<v-content>
		
		<data-model-manager :model="table.model" :row-actions="table.rowActions" :actions-allowed="table.actions" :headers="table.headers"></data-model-manager>
		
		<v-dialog v-model="view.dialog" max-width="500px">
			<v-card>
				<v-card-title>
					<span class="headline">{{ view.title }}</span>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md>
						<v-layout wrap>
							<v-flex xs12>
								<v-text-field label="Name" v-model="view.item.id" required></v-text-field>
							</v-flex>
						</v-layout>
					</v-container>
					<small>*indicates required field</small>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="blue darken-1" flat @click.native="view.dialog = false">Close</v-btn>
					<v-btn color="blue darken-1" flat @click.native="saveCloseModel(view.item); view.dialog = false">Save</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
		
	</v-content>
</template>

<script>
	
	import DataModelManager from '../../DataModelManager';
	import PayPal from '../../../model/PayPal';
	
	export default {
		components: { DataModelManager},
		data() {
			return {
				contexts: [],
				view : {
					item : {},
					title : 'Manage',
					dialog: false
				},
				table: {
					rowActions : [
						{
							name : 'Details',
							icon : 'notes',
							callback : this.viewModel
						}
					],
					actions : ['delete'],
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Product', value: 'product_name', sortable: true, align: 'left' },
						{ text: 'Qunatity', value: 'quantity', sortable: true, align: 'center' },
						{
							text: 'Gross',
							value: 'gross',
							sortable: true,
							align: 'left',
							format: function( value, values ){
								return value + " " + values['currency'];
							}
						},
						{ text: 'Last Activity', value: 'last_updated', sortable: true, align: 'right' },
					],
					model: new PayPal()
				},
			}
		},
		methods : {
			viewModel( model ){
				this.view.dialog = true;
				this.view.title = 'View';
				this.view.item = Object.assign({}, model );
			},
		}

	}
</script>

<style lang="scss">

</style>