
<template>
		<ImageUploadManager
				@uploaded="uploadedImage"
				:model-id="modelId"
				:multiple="false"
				:format="'png'"
				:max-width="maxWidth"
				:max-height="maxHeight"
				:api-route="apiRoute">
		</ImageUploadManager>
</template>

<script>
	
	import ImageUploaderMixin from './ImageUploaderMixin'
	
	export default {
		mixins: [ ImageUploaderMixin ],
		name: "PartnerImageUploader",
		props : {
			modelId : {
				required: true,
			},
			maxWidth : {
				type : Number,
				required: false,
				default: () => 600
			},
			maxHeight : {
				type : Number,
				required: false,
				default: () => 400
			}
		},
		data : () => {
			return {
				api: 'partner/uploadPartnerImage?id='
			}
		}
	}
	
</script>

<style scoped>

</style>
