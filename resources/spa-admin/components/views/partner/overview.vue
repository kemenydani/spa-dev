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
								<v-text-field label="Name" v-model="edit.item.name" required></v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-text-field label="Webiste" v-model="edit.item.website_url" required></v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-text-field :textarea="true" :rows="3" v-model="edit.item.description"></v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-switch :true-value="'1'" :false-value="'0'" label="Featured Top" v-model="edit.item.featured_top"></v-switch>
							</v-flex>
							<v-flex xs12>
								<v-switch :true-value="'1'" :false-value="'0'" label="Featured Bottom" v-model="edit.item.featured_bottom"></v-switch>
							</v-flex>
							<v-flex xs12>
								<v-switch :true-value="'1'" :false-value="'0'" label="Active" v-model="edit.item.active"></v-switch>
							</v-flex>
							<v-flex xs12>
								<v-switch :true-value="'1'" :false-value="'0'" label="Dark colored" v-model="edit.item.dark_colored"></v-switch>
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
					<PartnerImageUploadManager
							:modelId="image.item.id"
							@uploaded="imageUploaded($event, image.item )"
							api-route="article/uploadImage">
					</PartnerImageUploadManager>
				</v-card-text>
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
	import Partner from '../../../model/Partner';
	import PartnerImageUploadManager from '../../PartnerImageUploadManager';

	export default {
		components: { DataModelManager, PartnerImageUploadManager },
		data() {
			return {
				contexts: [],
                image : {
					item : {},
					dialog: false
				},
				edit : {
					item : {},
					title : 'Manage',
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
						{ text: 'Website', value: 'website_url', sortable: true, align: 'left' },
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
					model: new Partner()
				},
			}
		},
		methods : {
            imageUploaded( images, model ){
                this.$app.$emit('tableFetchData', true,  () => {
                    let image = images[0];
                    if(image) this.image.item.imageDataUrl = image[Object.keys(image)[0]].encoded;
                });
            },
			tableFetchData(){
				this.$app.$emit('tableFetchData');
			},
			addModel(){
				this.edit.dialog = true;
				this.edit.title = 'Add';
				this.edit.item = { name: '', website_url: '', active: false, featured_top : false, featured_bottom: false, dark_colored: false, description: '' };
			},
			editModel( model ){
				this.edit.dialog = true;
				this.edit.title = 'Edit';
				this.edit.item = Object.assign({}, model );
			},
            editImage( model){
                this.image.dialog = true;
                this.image.item = model;
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
				return (new Partner).store( model )
			},
		}
		
	}
</script>

<style lang="scss">

</style>