<template>
	<v-content>
		<data-model-manager :model="table.model" :actions-allowed="table.actions" :headers="table.headers"></data-model-manager>
		
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
	import Comment from '../../../model/Comment';
	
	export default {
		components: { DataModelManager },
		data() {
			return {
				pageHint: false,
				table: {
					actions : ['delete'],
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Message', value: 'text', sortable: true, align: 'left' },
						{ text: 'User', value: 'join1__username', sortable: true, align: 'left' },
						{ text: 'Posted', value: 'date_posted', sortable: true, align: 'right' },
					],
					model: new Comment()
				},
			}
		},
		
	}
</script>

<style lang="scss">

</style>