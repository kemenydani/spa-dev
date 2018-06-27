
import ImageUploadManager from './ImageUploadManager'

export default {
	components : { ImageUploadManager },
	props: {
		multiple : {
			type: Boolean,
			required: false,
			default : () => false
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
	}
}
