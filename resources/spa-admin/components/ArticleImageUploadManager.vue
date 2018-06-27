
<template>
		<ImageUploadManager @uploaded="uploadedImage" :model-id="modelId" :multiple="false" :allow-crop="true" :force-crop="true" :crop="true" :max-width="maxWidth" :max-height="maxHeight" :api-route="apiRoute"></ImageUploadManager>
</template>

<script>
	
	import ImageUploaderMixin from './ImageUploaderMixin'
	
	export default {
		mixins: [ ImageUploaderMixin ],
		name: "ArticleImageUploader",
		props : {
			modelId : {
				required: true,
			},
			maxWidth : {
				type : Number,
				required: false,
				default: () => 700
			},
			maxHeight : {
				type : Number,
				required: false,
				default: () => 400
			}
		},
		data : () => {
			return {
				apiRoute: 'article/uploadArticleImage?id=1'
			}
		}
	}
	
</script>

<style scoped>

</style>
