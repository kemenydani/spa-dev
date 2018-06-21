<template>
	<div>
		<ArticleImageUploadManager modelId="1" api-route="article/uploadImage"></ArticleImageUploadManager>
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
		},
		mounted() {
			console.log('new component mounted');
		}
	}
</script>

<style lang="scss">

</style>