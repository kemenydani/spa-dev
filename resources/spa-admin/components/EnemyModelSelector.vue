<template>
	<model-selector
		:label="label"
		:multiple="multiple"
		:model="model"
		:textColumn="textColumn"
		:valueColumn="valueColumn"
		:value="value"
		:baseQuery="baseQuery"
		:autoComplete="autoComplete"
		@input="selection"
	>
	</model-selector>
</template>

<script>
	
	import Enemy from '../model/Enemy';
	import ModelSelectorMixin from './ModelSelectorMixin';
	
	export default {
		mixins: [ModelSelectorMixin],
		data() {
			return {
				textColumn  : 'name',
				valueColumn : 'id',
				model : Enemy,
				baseQuery : { }
			}
		}
	}
</script>

<style>

</style>