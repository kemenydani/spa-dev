import ModelSelector from './ModelSelector';

export default {
	components: { ModelSelector },
	props: {
		value : {
			type: [String, Array],
			required : true
		},
		label : {
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