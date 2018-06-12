<template>
	<v-content>
		<data-model-manager :Model="table.model" :headers="table.headers"></data-model-manager>
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
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Message', value: 'text', sortable: true, align: 'left' },
						{ text: 'User', value: 'user_id', sortable: true, align: 'left' },
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