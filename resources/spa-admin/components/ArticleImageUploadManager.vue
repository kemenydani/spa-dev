
<template>
		<ImageUploadManager :model-id="modelId" :crop="true" :api-route="apiRoute"></ImageUploadManager>
</template>

<script>
	
	import ImageUploaderMixin from './ImageUploaderMixin'
	
	export default {
		mixins: [ ImageUploaderMixin ],
		name: "ArticleImageUploader",
		props : {
			modelId : {
				required: true,
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
