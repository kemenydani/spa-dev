<template>
	<v-content>
		

		
		<data-model-manager :model="table.model" :row-actions="table.rowActions" :headers="table.headers"></data-model-manager>
		
		<router-link is="v-btn" :to="{ name: 'article.create' }"
		             fab
		             bottom
		             right
		             color="red"
		             dark
		             fixed
		>
			<v-icon>add</v-icon>
		</router-link>
	</v-content>
</template>

<script>
	
	import DataModelManager from '../../DataModelManager';
	import Article from '../../../model/Article';
	
	export default {
		components: { DataModelManager },
		data() {
			return {
				table: {
					rowActions : [
						{
							name : 'Edit',
							icon : 'edit',
							callback : this.editGallery
						},
						{
							name : 'Compose',
							icon : 'subject',
							callback : this.editGallery
						},
						{
							name : 'Image',
							icon : 'image',
							callback : this.uploadImages
						}
					],
					headers: [
						//{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Title', value: 'title', sortable: true, align: 'left' },
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
						{ text: 'Created At', value: 'date_created', sortable: true, align: 'right', width: '200px' },
					],
					model: new Article()
				},
			}
		},

	}
</script>

<style lang="scss">

</style>