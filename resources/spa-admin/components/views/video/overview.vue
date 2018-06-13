<template>
	<v-content>
		<data-model-manager :model="table.model" :actions-allowed="table.actions"  :headers="table.headers">
		
		</data-model-manager>
		
		<router-link is="v-btn" :to="{ name: 'video.create' }"
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
	import Video from '../../../model/Video';
	
	export default {
		components: { DataModelManager },
		data() {
			return {
				table: {
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Title', value: 'title', sortable: true, align: 'left' },
						{ text: 'Created By', value: 'created_by', sortable: true, align: 'left' },
						{ text: 'Created At', value: 'date_created', sortable: true, align: 'right', width: '200px' },
					],
					model: new Video()
				},
			}
		},
		
	}
</script>

<style lang="scss">

</style>