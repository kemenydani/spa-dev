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
					<v-btn color="blue darken-1" flat @click.native="saveCloseModel(view.item); view.dialog = false">Save</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
		
		<v-dialog v-model="pageHint" max-width="600">
			<v-card>
				<v-card-title class="headline">Help</v-card-title>
				<v-card-text>
					<h3>How to delete?</h3>
					<p>
						Select the user(s) you want to delete with using the checkboxes on the left side, then select the delete action from the action dropdown.
					</p>
					<h3>How to modify?</h3>
					<p>
						Click the pencil icon in the rows and the user editor opens in a separate dialog.
					</p>
					<h3>How to change password?</h3>
					<p>
						By security reasons, admins are not allowed to change anyone's password. The users need to do it themselves. <br>
						Users can reset / change their password using the forgot password page.<br>
						After changing password, the user needs to accept it via email to take effect.<br>
						If someone asks you to change his password, tell him his email instead and he can do it himself.<br>
					</p>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="blue" flat="flat" @click.native="pageHint  = false">close</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
		
		<!-- fab -->
		<v-btn
				@click="pageHint = true"
				fab
				bottom
				right
				color="blue"
				dark
				fixed>
			<v-icon>help</v-icon>
		</v-btn>
		
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
					actions : ['delete'],
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