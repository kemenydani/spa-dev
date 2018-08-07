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
		:vValidationRules="vValidationRules"
	>
	</model-selector>
</template>

<script>
	
	import Category from '../model/Category';
	import ModelSelectorMixin from './ModelSelectorMixin';
	
	export default {
		mixins: [ModelSelectorMixin],
		data() {
			return {
				textColumn  : 'name',
				valueColumn : 'id',
				model : Category,
				baseQuery : { context : this.context }
			}
		}
	}
</script>

<style>

</style>