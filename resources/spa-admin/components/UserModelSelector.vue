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
		@blur.prevent
		:vValidationRules="vValidationRules"
	>
	</model-selector>
</template>

<script>
	
	import User from '../model/User';
	import ModelSelectorMixin from './ModelSelectorMixin';
	
	export default {
		mixins: [ModelSelectorMixin],
		data() {
			return {
				textColumn  : 'username',
				valueColumn : 'id',
				model : User,
				baseQuery : { context : this.context }
			}
		},
        methods : {
            selection(item) {
                this.$emit('input', item)
            },
        },

    }
</script>

<style>

</style>