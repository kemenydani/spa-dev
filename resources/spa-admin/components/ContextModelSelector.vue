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
	
	import Context from '../model/Context';
	import ModelSelectorMixin from './ModelSelectorMixin';
	
	export default {
		mixins: [ModelSelectorMixin],
		data() {
			return {
				textColumn  : 'name',
				valueColumn : 'name',
				model : Context,
				baseQuery : { }
			}
		}
	}
</script>

<style>

</style>