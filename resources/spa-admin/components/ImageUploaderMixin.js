
import ImageUploadManager from './ImageUploadManager'

export default {
	components : { ImageUploadManager },
	props: {
		multiple : {
			type: Boolean,
			required: false,
			default : () => false
		},
		modelId : {
			required: true,
			default : () => 0
		},
		crop : {
			type: Boolean,
			required: false,
			default : () => false
		},
	},
	methods : {
		uploadedImage( data ){
			this.$emit('uploaded', data)
		}
	},
	computed: {
		apiRoute()
		{
			return this.api + this.modelId;
		}
	}
}
