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
										v-validate="'required|max:100'"
										:error-messages="errors.collect('name')"
										:counter="100"
										data-vv-name="name"
										label="Name"
										v-model="edit.item.name"
										required>
								</v-text-field>
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
					<v-btn color="blue darken-1" flat @click.native="saveCloseModel(edit.item); edit.dialog = false;">Save</v-btn>
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
						<v-tab>UPLOAD IMAGES</v-tab>
						<v-tab>GALLERY IMAGES</v-tab>
					</v-tabs>
				</v-toolbar>
				<v-card-text>
				<v-tabs-items v-model="tabs">
					<v-tab-item>
						<v-card flat>
							<v-card-text>
								<GalleryImageUploadManager
										@uploaded="imagesUploaded.call(this, $event, compose.item)"
										:model-id="compose.item.id">
								</GalleryImageUploadManager>
							</v-card-text>
						</v-card>
					</v-tab-item>
					<v-tab-item>
						<v-card flat>
							<v-card-text>
								<v-flex xs12 sm12 md12 lg12 xl12>
									<v-card flat>
										<v-container v-bind="{ [`grid-list-lg`]: true }" fluid>
											<v-flex style="height: 40px; min-height: 40px; max-height: 40px; overflow: hidden;">
												<v-progress-linear v-if="compose.loadingImages" :indeterminate="true"></v-progress-linear>
											</v-flex>
											<v-layout row wrap>
												<v-flex xs12 sm6 md4 lg3 xl2
												        v-for="(image, key) in compose.item.images"
												        :key="key"
												>
													<v-card  tile>
														<v-card-media
																:src="image.path"
																height="280px"
														>
														</v-card-media>
														<v-card-actions>
															<v-spacer></v-spacer>
															<v-btn @click="deleteImage.call(this, compose.item, image)" small flat color="error">
																<v-icon color="error">delete_sweep</v-icon>&nbsp;DELETE
															</v-btn>
														</v-card-actions>
													</v-card>
												</v-flex>
											</v-layout>
										</v-container>
									</v-card>
								</v-flex>
							</v-card-text>
						</v-card>
					</v-tab-item>
				</v-tabs-items>
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
	import Gallery from '../../../model/Gallery';
	import GalleryImageUploadManager from '../../GalleryImageUploadManager';
	import GalleryImage from "../../../model/GalleryImage";

	export default {
		$_veeValidate: {
			validator: 'new'
		},
		components: { DataModelManager, GalleryImageUploadManager },
		data() {
			return {
				pageHint: false,
				fab: true,
			    tabs: 0,
				compose : {
					item : {},
					dialog: false,
					loadingImages : false
				},
				edit : {
					item : { },
					title: 'Edit',
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
		watch : {
			'edit.dialog' : {
				handler : function() {
					this.$validator.reset()
				},
				deep: true
			}
		},
		methods : {
			imagesUploaded( images, model )
			{
				this.fetchGalleryImages(model.id).then( ( response ) => {
					this.compose.item.images = response;
				})
			},
			deleteImage(model, image)
			{
				this.compose.loadingImages = true;
				GalleryImage.instance().deleteIn([image.id])
					.then( () => {
						this.fetchGalleryImages(model.id).then( ( response ) => {
							this.compose.item.images = response;
						})
					});
			},
			fetchGalleryImages( galleryId )
			{
				this.compose.loadingImages = true;
				return Gallery.instance()
					.fetchImages(galleryId)
					.then( response =>
					{
						this.compose.loadingImages = false;
						return response;
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
			composeModel( model ){
				  this.compose.dialog = true;
				  this.compose.item = model;
					this.fetchGalleryImages(model.id).then( ( response ) => {
						this.compose.item.images = response;
					})
			},
			editModel( model ){
				this.edit.dialog = true;
				this.edit.title = 'Edit';
				this.edit.item = Object.assign({}, model);
			},
			saveCloseModel( model, dialog )
			{
                if(!this.$validator.validate()) return false;

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