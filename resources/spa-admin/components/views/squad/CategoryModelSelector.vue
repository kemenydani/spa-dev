<template>
	<model-selector
		:label="label"
		:model="model"
		:text="text"
		:value="value"
		:baseQuery="baseQuery"
		:auto-complete="autoComplete"
	>
	</model-selector>
</template>

<script>
	
	import Category from '../../../model/Category';
	import ModelSelector from './ModelSelector';
	
	export default {
		components: { ModelSelector },
		props: ['label', 'autoComplete'],
		data() {
			return {
				value : 'id',
				text  : 'name',
				model : Category,
				baseQuery : { context : 'game' }
			}
		},
		methods: {},
		mounted() {}
	}
</script>

<style>

</style>