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
	
	import Country from '../model/Country';
	import ModelSelectorMixin from './ModelSelectorMixin';
	
	export default {
		mixins: [ModelSelectorMixin],
		data() {
			return {
				textColumn  : 'country_name',
				valueColumn : 'country_code',
				model : Country,
				baseQuery : { }
			}
		},
	}
</script>

<style>

</style>