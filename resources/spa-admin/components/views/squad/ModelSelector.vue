<template>
	<v-select :label="label" :items="items" :return-object="true" :autocomplete="autoComplete"></v-select>
</template>

<script>
	export default {
		name: "selectCategory",
		props: {
			label: {
				type: String,
				required: true,
			},
			text: {
				type: String,
				required: true,
			},
			value: {
				type: String,
				required: true,
			},
			context: {
				type: String,
				required: false,
			},
			autoComplete : {
				type: Boolean,
				required: false,
				default: () => false
			},
			baseQuery : {
				type: Object,
				required: false,
				default: () => {}
			},
			model : {
				type: Function,
				required : true
			}
		},
		data: () => {
			return {
				loading : false,
				/* text : value */
				items: [],
				searchMethod: null,
			}
		},
		methods : {
			fetchModels(){
				return this.model.instance().findAll( this.baseQuery ).then( response => {
					this.items = response.map( ( ri ) => {
						return { text: ri[this.text], value: ri[this.value]  }
					})
					console.log(this.items)
				})
			}
		},
		created()
		{
			this.fetchModels();
		}
	}
</script>

<style scoped>

</style>