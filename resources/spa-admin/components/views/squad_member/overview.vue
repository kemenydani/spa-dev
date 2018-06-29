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
								<UserModelSelector v-model="edit.item.user_id" :auto-complete="true" label="Select User"></UserModelSelector>
							</v-flex>
							<v-flex xs12>
								<v-text-field label="Name" v-model="edit.item.name" required></v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-text-field label="Position" v-model="edit.item.position"></v-text-field>
							</v-flex>
							<v-flex xs12>
								<SquadModelSelector v-model="edit.item.squad_id" label="Select Squad"></SquadModelSelector>
							</v-flex>
							<v-flex xs12>
								<v-text-field :textarea="true"  :rows="3" v-model="edit.item.description"></v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-switch :true-value="'1'" :false-value="'0'" label="Active" v-model="edit.item.active"></v-switch>
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

		<v-dialog
				v-model="image.dialog"
				fullscreen
				transition="dialog-bottom-transition"
		>
			<v-card>
				<v-toolbar card dark color="primary">
					<v-toolbar-title>Edit Image</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>

					</v-toolbar-items>
					<v-spacer></v-spacer>
					<v-btn icon dark @click.native="image.dialog = false">
						<v-icon>close</v-icon>
					</v-btn>
				</v-toolbar>
				<v-card-text>
					<v-card color="grey darken-4">
						<v-card-text style="text-align: center;">
							<img :src="image.item.imageDataUrl">
						</v-card-text>
					</v-card>
					<br>
					<SquadMemberImageUploadManager
							:modelId="image.item.id"
							@uploaded="imageUploaded($event, image.item )">
					</SquadMemberImageUploadManager>
				</v-card-text>
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
		
		<v-speed-dial
				v-model="fab"
				:bottom="true"
				:right="true"
				:direction="'top'"
				fixed
		>
			<v-btn
					slot="activator"
					v-model="fab"
					color="amber"
					dark
					fab
			>
				<v-icon>add</v-icon>
			</v-btn>
			<v-btn
					@click="pageHint = true"
					fab
					color="blue"
					dark
					small
			>
				<v-icon>help</v-icon>
			</v-btn>
			<v-btn
					@click="addModel"
					fab
					color="success"
					dark
					small
			>
				<v-icon>library_add</v-icon>
			</v-btn>
		</v-speed-dial>
	
	</v-content>
</template>

<script>
	
	import DataModelManager from '../../DataModelManager';
    import SquadMember from "../../../model/SquadMember";
    import SquadModelSelector from '../../SquadModelSelector';
    import UserModelSelector from '../../UserModelSelector';
	import SquadMemberImageUploadManager from '../../SquadMemberImageUploadManager'

	export default {
		components: { DataModelManager, SquadModelSelector, UserModelSelector, SquadMemberImageUploadManager },
		data() {
			return {
				pageHint: false,
				fab: true,
				contexts: [],
				edit : {
					item : {},
					title : 'Manage',
					dialog: false
				},
                compose : {
                    item : {},
                    title : 'Members',
                    dialog: false
                },
                image : {
                    img : null,
                    item : {},
                    dialog: false
                },
				table: {
					actions : ['delete','activate', 'deactivate'],
					rowActions : [
						{
							name : 'Edit',
							icon : 'edit',
							callback : this.editModel
						},

						{
							name : 'Image',
							icon : 'image',
							callback : this.editImage
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
							format: function( value, values ){
								return value == 1 ? 'Active' : 'Inactive';
							}
						},
					],
					model: new SquadMember()
				},
			}
		},
		methods : {
            imageUploaded( images, model ){
                this.$app.$emit('tableFetchData', true,  () => {

                    let image = images[0];

                    if(image)
                    {
                        this.image.item.imageDataUrl = image[Object.keys(image)[0]].encoded;
                    }
                });
                //this.image.imageDataUrl
            },
			tableFetchData(){
				this.$app.$emit('tableFetchData');
			},
			addModel(){
				this.edit.dialog = true;
				this.edit.title = 'Add';
				this.edit.item = { name: '', squad_id: null, user_id: null };
			},
            editImage( model ){
                this.image.dialog = true;
                this.image.item = model;
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
							this.$app.$emit('toast', 'Saved : ' + response.data.id , 'success');
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
				return (new SquadMember).store( model )
			},
		}
		
	}
</script>

<style lang="scss">

</style>