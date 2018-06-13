<template>
	<v-content>
		<data-model-manager :model="table.model" :row-actions="table.rowActions" :headers="table.headers"></data-model-manager>
		
		<router-link is="v-btn" :to="{ name: 'gallery.create' }"
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
	import Gallery from '../../../model/Gallery';
	
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
							name : 'Upload',
							icon : 'image',
							callback : this.uploadImages
						}
					],
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
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
			}
		}
	}
</script>

<style lang="scss">

</style>