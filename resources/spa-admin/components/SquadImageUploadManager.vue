
<template>
		<ImageUploadManager
				@uploaded="uploadedImage"
				:model-id="modelId"
				:multiple="false"
				:allow-crop="true"
				:force-crop="true"
				:crop="true"
				:max-width="maxWidth"
				:max-height="maxHeight"
				:api-route="apiRoute">
		</ImageUploadManager>
</template>

<script>
	
	import ImageUploaderMixin from './ImageUploaderMixin'
	
	export default {
		mixins: [ ImageUploaderMixin ],
		name: "SquadImageUploadManager",
		props : {
			modelId : {
				required: true,
			},
			maxWidth : {
				type : Number,
				required: false,
				default: () => 1400
			},
			maxHeight : {
				type : Number,
				required: false,
				default: () => 360
			}
		},
		data : () => {
			return {
				api: 'squad/uploadSquadImage?id='
			}
		}
	}
	
</script>

<style scoped>

</style>
