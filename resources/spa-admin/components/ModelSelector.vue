<template>
	<v-select :label="label" @input="selection" :multiple="multiple" :search-input.sync="search" :items="items" :value="value" :autocomplete="autoComplete"></v-select>
</template>

<script>
	export default {
		name: "ModelSelector",
		props: {
			value : {
				required: true,
			},
			label: {
				type: String,
				required: true,
			},
			multiple : {
				type: Boolean,
				required: false,
				default : () => false
			},
			textColumn: {
				type: String,
				required: true,
			},
			valueColumn: {
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
				search : '',
				loading : false,
				items: [],
				searchMethod: null,
			}
		},
		watch : {
			search: {
				handler: function(v) {
					this.fetchLazy()
				}
			}
		},
		methods : {
			fetchModels(){
					return this.fetchLazy();
			},
			fetchStrict(){
				return this.model.instance().findAll(  { baseQuery : this.baseQuery, search : this.search } ).then( response => {
					this.items = response.map( ( ri ) =>
					{
						return { text: ri[this.textColumn], value: ri[this.valueColumn]  }
					});
				})
			},
			fetchLazy(){
				return this.model.instance().findAllLike( { baseQuery : this.baseQuery, search : this.search } ).then( response => {
					this.items = response.map( ( ri ) =>
					{
						return { text: ri[this.textColumn], value: ri[this.valueColumn]  }
					});
				})
			},
			selection(item) {
				this.$emit('input', item)
			}
		},
		created() {
			this.fetchModels();
		}
	}
</script>
