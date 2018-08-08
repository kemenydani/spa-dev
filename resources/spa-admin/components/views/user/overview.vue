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
										v-validate="'required|max:50'"
										:error-messages="errors.collect('username')"
										:counter="50"
										data-vv-name="username"
										label="Username"
										v-model="edit.item.username"
										required>
								</v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-text-field
										v-validate="'required|max:345'"
										:error-messages="errors.collect('email')"
										:counter="345"
										data-vv-name="email"
										label="Email"
										v-model="edit.item.email"
										required>
								</v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-switch label="Admin access" v-model="edit.item.is_admin" required></v-switch>
							</v-flex>
							<v-flex xs12>
								<CountryModelSelector v-model="edit.item.country_code" :autoComplete="true" label="Select Country"></CountryModelSelector>
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
		
	</v-content>
</template>

<script>
	
	import DataModelManager from '../../DataModelManager';
	import User from '../../../model/User';
	import CountryModelSelector from "../../CountryModelSelector";
	
	export default {
		components: {CountryModelSelector, DataModelManager },
		data() {
			return {
				pageHint: false,
				edit : {
					item : {},
					title : 'Manage',
					dialog: false
				},
				/*
				editpw : {
					item : {},
					title : 'Edit Password',
					dialog: false
				},
				*/
				table: {
					actions : ['delete'],
					rowActions : [
						{
							name : 'Edit',
							icon : 'edit',
							callback : this.editModel
						},
						/*
						{
							name : 'Edit Password',
							icon : 'vpn_key',
							callback : this.editPassword
						},
						*/
					],
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Name', value: 'username', sortable: true, align: 'left' },
						{ text: 'Email', value: 'email', sortable: true, align: 'right', width: '200px' },
					],
					model: new User()
				},
			}
		},
		methods : {
			tableFetchData(){
				this.$app.$emit('tableFetchData');
			},
			editModel( model ){
				this.edit.dialog = true;
				this.edit.title = 'Edit';
				this.edit.item = Object.assign({}, model );
			},
			editPassword( model ){
				this.editpw.dialog = true;
				this.editpw.title = 'Edit Password';
				this.editpw.item = Object.assign({}, model );
				this.editpw.item.passwod = null;
				this.editpw.item.passwod_repeat = null;
			},
			saveCloseModel( model, dialog )
			{
                if(!this.$validator.validate()) return false;

				this.$app.$emit('toast', 'Saving...', 'info');
				
				this.storeModel( model )
					.then((response) => {
						if(response.success) {
							this.$app.$emit('toast', 'Saved : ' + response.data.username  , 'success');
						} else {
							this.$app.$emit('toast', 'Save failed: ' + response.data.username, 'error');
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
				return (new User).store( model )
			}
		}
	}
</script>

<style lang="scss">

</style>