
<template>
		<ImageUploadManager
				@uploaded="uploadedImage"
				:model-id="modelId"
				:multiple="true"
				:allow-crop="false"
				:force-crop="false"
				:max-width="maxWidth"
				:max-height="maxHeight"
				:api-route="apiRoute">
		</ImageUploadManager>
</template>

<script>
	
	import ImageUploaderMixin from './ImageUploaderMixin'
	
	export default {
		mixins: [ ImageUploaderMixin ],
		name: "GalleryImageUploader",
		props : {
			modelId : {
				required: true,
			},
			maxWidth : {
				type : Number,
				required: false,
				default: () => 1920
			},
			maxHeight : {
				type : Number,
				required: false,
				default: () => 1080
			}
		},
		data : () => {
			return {
				api: 'gallery/uploadGalleryImage?id='
			}
		}
	}
	
</script>

<style scoped>

</style>
