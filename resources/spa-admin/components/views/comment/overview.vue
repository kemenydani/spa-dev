<template>
	<v-content>
		<data-model-manager :model="table.model" :actions-allowed="table.actions" :headers="table.headers"></data-model-manager>
	</v-content>
</template>

<script>
	
	import DataModelManager from '../../DataModelManager';
	import Comment from '../../../model/Comment';
	
	export default {
		components: { DataModelManager },
		data() {
			return {
				table: {
					actions : ['delete'],
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Message', value: 'text', sortable: true, align: 'left' },
						{ text: 'User', value: 'username', sortable: true, align: 'left' },
						{ text: 'Posted', value: 'date_posted', sortable: true, align: 'right' },
					],
					model: new Comment()
				},
			}
		},
		
	}
</script>

<style lang="scss">

</style>