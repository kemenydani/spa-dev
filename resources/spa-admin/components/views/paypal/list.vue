<template>
	<v-content>
		
		<data-model-manager :model="table.model" :selectable="false" :row-actions="table.rowActions" :actions-allowed="table.actions" :headers="table.headers"></data-model-manager>
		
		<v-dialog v-model="view.dialog" max-width="500px">
			<v-card>
				<v-card-title>
					<span class="headline">{{ view.title }}</span>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md>
						<v-layout wrap>
							<v-flex xs12>
								<b>ID:</b> {{ view.item.id }}
							</v-flex>
							<v-flex xs12>
								<b>Product ID:</b> {{ view.item.product_id }}
							</v-flex>
							<v-flex xs12>
								<b>Product Name:</b> {{ view.item.product_name }}
							</v-flex>
							<v-flex xs12>
								<b>Payment Status:</b> {{ view.item.payment_status }}
							</v-flex>
							<v-flex xs12>
								<b>Pending Reason:</b> {{ view.item.pending_reason }}
							</v-flex>
							<v-flex xs12>
								<b>Single Item Price:</b> {{ view.item.gross }} {{ view.item.currency}}
							</v-flex>
							<v-flex xs12>
								<b>Quantity:</b> {{ view.item.quantity }}
							</v-flex>
							<v-flex xs12>
								<b>Gross:</b> {{ view.item.gross }} {{ view.item.currency}}
							</v-flex>
							<v-flex xs12>
								<b>Currency:</b> {{ view.item.currency }}
							</v-flex>
							<v-flex xs12>
								<b>Payer Email:</b> {{ view.item.payer_email }}
							</v-flex>
							<v-flex xs12>
								<b>TXN ID:</b> {{ view.item.txn_id }}
							</v-flex>
							<v-flex xs12>
								<b>IPN Track ID:</b> {{ view.item.ipn_track_id }}
							</v-flex>
							<v-flex xs12>
								<b>Date Checkout:</b> {{ view.item.date_checkout }}
							</v-flex>
							<v-flex xs12>
								<b>Last Updated:</b> {{ view.item.last_updated }}
							</v-flex>
						</v-layout>
					</v-container>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="blue darken-1" flat @click.native="view.dialog = false">Close</v-btn>
					<v-btn color="blue darken-1" flat @click.native="saveCloseModel(view.item); edit.dialog = false;">Save</v-btn>
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
				pageHint: false,
				fab: true,
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
					actions : [],
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Product', value: 'product_name', sortable: true, align: 'left' },
						{ text: 'Status', value: 'payment_status', sortable: true, align: 'center' },
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