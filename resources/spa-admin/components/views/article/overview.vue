<template>
	<v-content>
	
		<data-model-manager :model="table.model" :row-actions="table.rowActions" :headers="table.headers"></data-model-manager>
		
		<v-dialog
				v-model="compose.dialog"
				fullscreen
				hide-overlay
				transition="dialog-bottom-transition"
		>
			<v-card tile>
				<v-toolbar card dark color="primary">
					<v-btn icon dark @click.native="compose.dialog = false">
						<v-icon>close</v-icon>
					</v-btn>
					<v-toolbar-title>Compose Article</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
					
					</v-toolbar-items>
					<v-spacer></v-spacer>
					<v-toolbar-items>
						<v-btn dark flat @click.native="saveCloseModel(compose.item);">Save</v-btn>
					</v-toolbar-items>
				</v-toolbar>
				<v-card-text style="min-height: 100% !important; position: relative !important;">
					<div style="max-width: 1140px; margin: 0px auto;">
						<v-text-field
								v-validate="'required|max:600'"
								:error-messages="errors.collect('teaser')"
								:counter="600"
								data-vv-name="teaser"
								:textarea="true"
								:rows="3"
								v-model="compose.item.teaser">
						</v-text-field>
						<vue-editor id="editor" v-model="compose.item.content"></vue-editor>
					</div>
				</v-card-text>
				<div style="flex: 1 1 auto;"></div>
			</v-card>
		</v-dialog>
		
		<v-dialog
				v-model="image.dialog"
				max-width="800px"
				fullscreen
				height="600px"
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
					<ArticleImageUploadManager
							:modelId="image.item.id"
							@uploaded="imageUploaded($event, image.item )"
							api-route="article/uploadImage">
					</ArticleImageUploadManager>
				</v-card-text>
			</v-card>
		</v-dialog>
		
		<v-dialog v-model="edit.dialog" max-width="800px">
			<v-card>
				<v-card-title>
					<span class="headline">Edit Article</span>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md>
						<v-layout wrap>
							<v-flex xs12>
								<v-text-field
										v-validate="'required|max:200'"
										:error-messages="errors.collect('title')"
										:counter="200"
										data-vv-name="title"
										label="Title"
										v-model="edit.item.title"
										required>
								</v-text-field>
							</v-flex>
							<v-flex xs12>
								<v-switch :true-value="'1'" :false-value="'0'" label="Active" v-model="edit.item.active"></v-switch>
							</v-flex>
							<v-flex xs12>
								<v-switch :true-value="'1'" :false-value="'0'" label="Highlighted" v-model="edit.item.highlighted"></v-switch>
							</v-flex>
							<v-flex xs12>
								<v-switch :true-value="'1'" :false-value="'0'" label="Commentable" v-model="edit.item.comments_enabled"></v-switch>
							</v-flex>
							<v-flex xs12>
								<CategoryModelSelector
										:validator="$validator"
										:vValidationRules="'required'"
										v-model="edit.item.categories"
										:multiple="true"
										label="Select Category">
								</CategoryModelSelector>
							</v-flex>
							<v-flex xs12>
								<EventModelSelector v-model="edit.item.event_id" label="Select Event"></EventModelSelector>
							</v-flex>
						</v-layout>
					</v-container>
					<small>*indicates required field</small>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="blue darken-1" flat @click.native="edit.dialog = false">Close</v-btn>
					<v-btn color="blue darken-1" flat @click.native="saveCloseModel(edit.item);">Save</v-btn>
				</v-card-actions>
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
	import Article from '../../../model/Article';
	import CategoryModelSelector from '../../CategoryModelSelector';
	import ArticleImageUploadManager from '../../ArticleImageUploadManager'
	import EventModelSelector from '../../EventModelSelector'
    import { VueEditor, Quill } from 'vue2-editor'

	export default {
		$_veeValidate: {
			validator: 'new'
		},
		components: { VueEditor, DataModelManager, CategoryModelSelector, ArticleImageUploadManager, EventModelSelector },
		data() {
			return {
				pageHint: false,
				fab: true,
				compose : {
					item : {
					    content: ''
					},
					dialog: false
				},
				edit : {
					item : {},
					dialog: false
				},
				image : {
					img : null,
					item : {},
					dialog: false
				},
				table: {
					rowActions : [
						{
							name : 'Edit',
							icon : 'edit',
							callback : this.editArticle
						},
						{
							name : 'Compose',
							icon : 'subject',
							callback : this.composeArticle
						},
						{
							name : 'Image',
							icon : 'image',
							callback : this.editImage
						}
					],
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Title', value: 'title', sortable: true, align: 'left' },
						{
							text: 'Activated',
							value: 'active',
							sortable: true,
							align: 'right',
							format: function( value, values ){
								return value == 1 ? 'Active' : 'Inactive';
							}
						},
						{ text: 'Created At', value: 'date_created', sortable: true, align: 'right' },
					],
					model: new Article()
				},
			}
		},
		watch : {
			'edit.dialog' : {
				handler : function() {
					this.$validator.reset()
				},
				deep: true
			},
			'compose.dialog' : {
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
				this.edit.item = { title: '', active: false, comments_enabled: true, highlighted: false, categories: [], event_id: null };
			},
			composeArticle( article ){
				this.compose.dialog = true;
				this.compose.item = Object.assign({}, article);
			},
			editImage( article ){
				this.image.dialog = true;
				this.image.item = article;
			},
			editArticle( article ){
				this.edit.dialog = true;
				this.edit.item = Object.assign({}, article);
			},
			saveCloseModel( model, dialog ){

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
					
					})
					.finally(() => {
						this.edit.item = {};
						this.tableFetchData();
					});
					dialog = false;
			},
			storeModel( article ){
				return (new Article).store( article )
			}
		}

	}
</script>

<style lang="scss">

</style>