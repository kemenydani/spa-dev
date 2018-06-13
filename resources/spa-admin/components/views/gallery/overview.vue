<template>
	<v-content>
		<data-model-manager :model="table.model" :headers="table.headers">
		
		</data-model-manager>
		
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
						{ text: 'Created At', value: 'date_created', sortable: true, align: 'right', width: '200px' },
					],
					model: new Gallery()
				},
			}
		},
		
	}
</script>

<style lang="scss">

</style>