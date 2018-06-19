<template>
	<v-content>
		<data-model-manager :model="table.model" :actions-allowed="table.actions"  :headers="table.headers">
		
		</data-model-manager>
		
		<router-link is="v-btn" :to="{ name: 'award.create' }"
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
	import Award from '../../../model/Award';
	
	export default {
		components: { DataModelManager },
		data() {
			return {
				table: {
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Title', value: 'event_name', sortable: true, align: 'left' },
						{ text: 'Place', value: 'place', sortable: true, align: 'center' },
						{ text: 'Date', value: 'award_date', sortable: true, align: 'right', width: '200px' },
					],
					model: new Award()
				},
			}
		},
		
	}
</script>

<style lang="scss">

</style>