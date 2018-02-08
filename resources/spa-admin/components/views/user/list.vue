<template>
	<v-content>
		
		<data-model-manager :Model="table.model" :headers="table.headers">
		
		</data-model-manager>
	
	</v-content>
</template>

<script>
	
	import DataModelManager from '../../DataModelManager';
	import User from '../../../models/User';
	
	export default {
		components: { DataModelManager },
		data() {
			return {
				table: {
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Username', value: 'username', sortable: true, align: 'left' },
						{ text: 'Email', value: 'email', sortable: true, align: 'left' },
						{ text: 'Date Joined', value: 'date_created', sortable: true, align: 'right', width: '200px' },
					],
					model: new User()
				},
			}
		},
		
	}
</script>

<style lang="scss">

</style>