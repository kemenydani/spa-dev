<template>
	<v-content>
		<data-model-manager :model="table.model" :actions-allowed="table.actions"  :headers="table.headers">
		
		</data-model-manager>
		
		<router-link is="v-btn" :to="{ name: 'user.create' }"
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
	import User from '../../../model/User';
	
	export default {
		components: { DataModelManager },
		data() {
			return {
				table: {
					actions : ['delete'],
					headers: [
						{ text: 'Id', align: 'left', sortable: true, value: 'id', width: '40px'},
						{ text: 'Name', value: 'username', sortable: true, align: 'left' },
						{ text: 'Email', value: 'email', sortable: true, align: 'right', width: '200px' },
					],
					model: new User()
				},
			}
		},
		
	}
</script>

<style lang="scss">

</style>