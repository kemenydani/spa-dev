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
	
	import Event from '../model/Event';
	import ModelSelectorMixin from './ModelSelectorMixin';
	
	export default {
		mixins: [ModelSelectorMixin],
		data() {
			return {
				textColumn  : 'name',
				valueColumn : 'id',
				model : Event,
				baseQuery : { context : this.context }
			}
		}
	}
</script>

<style>

</style>