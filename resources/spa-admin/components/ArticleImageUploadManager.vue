
<template>
		<ImageUploadManager :model-id="modelId" :crop="true" :max-size="maxSize" :api-route="apiRoute"></ImageUploadManager>
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
			maxSize : {
				type : Number,
				required: true,
				default: () => 1200
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
