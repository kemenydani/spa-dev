<template>
	<v-content>
		
		<data-model-manager :model="table.model" :row-actions="table.rowActions" :headers="table.headers"></data-model-manager>
		
		<v-dialog v-model="edit.dialog" max-width="500px">
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
										v-validate="'required|max:500'"
										:error-messages="errors.collect('description')"
										:counter="500"
										data-vv-name="description"
										textarea
										label="About this squad"
										v-model="edit.item.description"
										required>
								</v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-switch :true-value="'1'" :false-value="'0'" label="Active" v-model="edit.item.active"></v-switch>
							</v-flex>
							<v-flex xs12>
								<v-switch :true-value="'1'" :false-value="'0'" label="Featured" v-model="edit.item.featured"></v-switch>
							</v-flex>
							<v-flex xs12>
								<CategoryModelSelector
										:vValidationRules="'required'"
										v-model="edit.item.game_id"
										:context="'game'"
										label="Select Game">
								</CategoryModelSelector>
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
					<SquadImageUploadManager
							:modelId="image.item.id"
							@uploaded="imageUploaded($event, image.item )">
					</SquadImageUploadManager>
				</v-card-text>
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
	import Squad from '../../../model/Squad';
	import CategoryModelSelector from '../../CategoryModelSelector';
	import SquadImageUploadManager from '../../SquadImageUploadManager'
	
	export default {
		$_veeValidate: {
			validator: 'new'
		},
		components: { DataModelManager, CategoryModelSelector, SquadImageUploadManager },
		data() {
			return {
				pageHint: false,
				fab: true,
				edit : {
					item : {},
					title : 'Manage',
					dialog: false,
					errors : {
						name: '',
					},
				},
				image : {
					item : {},
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
							callback : this.editImage
						}
					],
					headers: [
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
					model: new Squad()
				},
			}
		},
		watch : {
			'edit.dialog' : {
				handler : function() {
					this.$validator.reset()
				},
				deep: true
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
				this.edit.item = { name: '', active: false };
			},
			editModel( model ){
				this.edit.dialog = true;
				this.edit.title = 'Edit';
				this.edit.item = Object.assign({}, model );
			},
			editImage( model ){
				this.image.dialog = true;
				this.image.item = model;
			},
			saveCloseModel( model, dialog )
			{
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
				return (new Squad).store( model )
			}
		}
	}
</script>

<style lang="scss">

</style>