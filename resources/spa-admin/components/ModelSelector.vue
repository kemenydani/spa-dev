<template>
	<v-select
			:label="label"
			:multiple="multiple"
			:search-input.sync="search"
			:items="items"
			:value="value"
			:autocomplete="autoComplete"
			v-validate="vValidationRules"
			:error-messages="errors.collect('select')"
			data-vv-name="select"
			:cache-items="true"
			@input="selection">
	</v-select>
</template>

<script>
	
	//import ModelSelectorMixin from './ModelSelectorMixin';
	
	export default {
		
      $_veeValidate: {
          validator: this.validator
      },
	  //	mixins: [ModelSelectorMixin],
			name: "ModelSelector",
			props: {
			// VeeValidate props
			//
			validator: null,
			value : {
				required : true
			},
			vValidationRules : {
				type: String,
				required: false,
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
			},
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
				    if(!v) return;
				    console.log('search: ', v)
					console.log(this.value)
					console.log(this.items)
					this.fetchLazy()
				}
			},
			value : {
				handler : function(v) {
					this.$validator.reset()
				},
				deep: true
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
			    console.log(item)
			    console.log(this.search)
				console.log(this.value)
				console.log(this.items)
				this.$emit('input', item)
			}
		},
		created(){
			this.fetchModels()
		}
	}
</script>
