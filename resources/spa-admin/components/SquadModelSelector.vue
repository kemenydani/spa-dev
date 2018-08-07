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
		:vValidationRules="vValidationRules"
		@input="selection"
	>
	</model-selector>
</template>

<script>
	
	import Squad from '../model/Squad';
	import ModelSelectorMixin from './ModelSelectorMixin';
	
	export default {
		mixins: [ModelSelectorMixin],
		data() {
			return {
				textColumn  : 'name',
				valueColumn : 'id',
				model : Squad,
				baseQuery : { context : this.context }
			}
		}
	}
</script>

<style>

</style>