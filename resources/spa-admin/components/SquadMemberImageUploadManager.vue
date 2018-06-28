
<template>
		<ImageUploadManager
				@uploaded="uploadedImage"
				:model-id="modelId"
				:multiple="false"
				:format="'png'"
				:force-crop="true"
				:allow-crop="true"
				:max-width="maxWidth"
				:max-height="maxHeight"
				:api-route="apiRoute">
		</ImageUploadManager>
</template>

<script>
	
	import ImageUploaderMixin from './ImageUploaderMixin'
	
	export default {
		mixins: [ ImageUploaderMixin ],
		name: "SquadMemberImageUploader",
		props : {
			modelId : {
				required: true,
			},
			maxWidth : {
				type : Number,
				required: false,
				default: () => 280
			},
			maxHeight : {
				type : Number,
				required: false,
				default: () => 380
			}
		},
		data : () => {
			return {
				api: 'squad_member/uploadSquadMemberAvatar?id='
			}
		}
	}
	
</script>

<style scoped>

</style>
