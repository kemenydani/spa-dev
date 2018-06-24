<template>
	<div>
		<v-content>
			<div id="images">
				<input type="file" @change="uploadImages" ref="input" name="images" accept="image/*" multiple>
				<div class="image" v-for="image in images">
					{{ image.name }}
				</div>
			</div>
			<v-btn
         fab
         bottom
         right
         color="amber accent-3"
         dark
         fixed
         @click="browseComputer"
			>
				<v-icon>add</v-icon>
			</v-btn>
			
		</v-content>
	</div>
</template>

<script>
	
	export default {
		data() {
			return {
				gallery : {
					id : null // get it from route
				},
				files : 0,
				images : []
			}
		},
		methods : {
			fetchGallery( id ){
			
			},
			uploadImages( event )
			{
				let files = event.target.files;
				
				for(let i = 0; i < files.length; i ++)
				{
					let formData = new FormData();
					formData.append('image', files[i], files[i].name);
	
					this.axios.post('api/gallery/uploadImage?id=' + this.$route.params.id, formData, {
						onUploadProgress: progressEvent => {
							console.log(progressEvent.loaded / progressEvent.total)
						}
					})
					.then(response => {
						console.log(response)
					})
					.catch(() => {
					
					})
					.finally(() => {
					
					});
				}
			},
			browseComputer(){
				this.$refs['input'].click();
			}
		},
		mounted() {
		
		}
	}
</script>

<style lang="scss">

</style>