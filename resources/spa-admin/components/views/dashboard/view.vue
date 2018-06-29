<template>
	<div>
		<ArticleImageUploadManager modelId="1" :max-size="1200" api-route="article/uploadImage"></ArticleImageUploadManager>
	</div>
</template>

<script>
	
	import ArticleImageUploadManager from '../../ArticleImageUploadManager'
	
	export default {
		name: 'dashboard-view',
		components: { ArticleImageUploadManager },
		data() {
			return {
				data_field: 'data_field'
			}
		}
	}
</script>

<style lang="scss">

</style>