import ModelSelector from './ModelSelector';

export default {
	components: { ModelSelector },
	props: {
		// VeeValidate props
		vValidationRules : {
			type: String,
			required: false,
		},
		// self props
		value : {
			required : true
		},
		label : {
			type: String,
			required: false
		},
		context : {
			type: String,
			required: false
		},
		multiple : {
			type: Boolean,
			required: false,
			default : () => false
		},
		autoComplete : {
			type : Boolean,
			required: false
		}
	},
	methods : {
		selection(item) {
			this.$emit('input', item)
		},
	},
	
}