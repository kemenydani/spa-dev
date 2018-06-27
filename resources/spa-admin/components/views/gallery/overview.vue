<template>
	<v-content>
		
		<data-model-manager :model="table.model" :row-actions="table.rowActions" :headers="table.headers"></data-model-manager>
		
		<v-dialog v-model="edit.dialog" max-width="500px">
			<v-card>
				<v-card-title>
					<span class="headline">Edit Article</span>
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
				v-model="compose.dialog"
				fullscreen
				hide-overlay
				transition="dialog-bottom-transition"
				scrollable
		>
			<v-card tile>
				<v-toolbar color="primary" dark tabs>
					<v-toolbar-title>Gallery Image Manager</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-btn icon dark @click.native="compose.dialog = false">
						<v-icon>close</v-icon>
					</v-btn>
					<v-tabs
						slot="extension"
						v-model="tabs"
						centered
						color="grey darken-3"
					>
						<v-tab>GALLERY IMAGES</v-tab>
						<v-tab>UPLOAD IMAGES</v-tab>
					</v-tabs>
				</v-toolbar>
				<v-card-text>
				<v-tabs-items v-model="tabs">
					<v-tab-item>
						<v-card flat>
							<v-card-text>a</v-card-text>
						</v-card>
					</v-tab-item>
					<v-tab-item>
						<v-card flat>
							<v-card-text>
								<GalleryImageUploadManager :model-id="compose.item.id"></GalleryImageUploadManager>
							</v-card-text>
						</v-card>
					</v-tab-item>
				</v-tabs-items>
				</v-card-text>
			</v-card>
		</v-dialog>

		<!-- fab -->
		<v-btn
				@click="addModel"
				fab
				bottom
				right
				color="amber accent-3"
				dark
				fixed>
			<v-icon>add</v-icon>
		</v-btn>
		
	</v-content>
</template>

<script>
	
	import DataModelManager from '../../DataModelManager';
	import Gallery from '../../../model/Gallery';
	import GalleryImageUploadManager from '../../GalleryImageUploadManager';

	export default {
		components: { DataModelManager, GalleryImageUploadManager },
		data() {
			return {
			    tabs: 0,
				compose : {
					item : {},
					dialog: false
				},
				edit : {
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
							name : 'Upload',
							icon : 'image',
							callback : this.composeModel
						}
					],
					headers: [
						{ text: 'Title', value: 'name', sortable: true, align: 'left' },
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
					model: new Gallery()
				},
			}
		},
		methods : {
			editGallery( item ){
				console.log(item.id)
			},
			uploadImages( item ){
				this.$router.push({ name: 'gallery.upload', params: { id: item.id }})
			},
			
			tableFetchData(){
				this.$app.$emit('tableFetchData');
			},
			addModel(){
				this.edit.dialog = true;
				this.edit.item = { name: '', active: false };
			},
			composeModel( model ){
				this.compose.dialog = true;
				this.compose.item = Object.assign({}, model);
			},
			editModel( model ){
				this.edit.dialog = true;
				this.edit.item = Object.assign({}, model );
			},
			saveCloseModel( model, dialog ){
				console.log(model)
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
				return (new Gallery).store( model )
			}
		}
	}
</script>

<style lang="scss">

</style>