
<template>
	<div class="fileUploader">
		
		<div>
			<v-btn @click="browseComputer" class="white--text" color="blue-grey">
				Select Image(s)
				<v-icon right dark>folder</v-icon>
			</v-btn>
			<v-btn @click="uploadImages" class="white--text" color="blue-grey">
				Upload
				<v-icon right dark>cloud_upload</v-icon>
			</v-btn>
			<span>{{ fileQueue.length }} files selected</span>
		</div>
		<br>
		<div>
			<v-card v-for="queueItem in fileQueue">
				<v-card-title>{{ queueItem.name }} ({{ queueItem.size }} Bytes)</v-card-title>
				<v-card-actions>
					<v-btn small outline fab @click="cropDialog(queueItem)" color="teal" v-if="queueItem.done === null">
						<v-icon>crop_free</v-icon>
					</v-btn>
					<v-spacer></v-spacer>
					<v-btn icon @click.native="queueItem.preview = !queueItem.preview" v-if="queueItem.done">
						<v-icon>{{ queueItem.preview ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</v-icon>
					</v-btn>
				</v-card-actions>
				<v-slide-y-transition>
					<v-card-text v-show="queueItem.preview" style="overflow: hidden; position: relative;">
						<img :src="queueItem.url" style="max-width: 100%; height: auto;">
					</v-card-text>
				</v-slide-y-transition>
				<v-progress-linear v-model="queueItem.progress"></v-progress-linear>
			</v-card>
		</div>
		
		<!--
		<div>
			<div v-if="crop">
				<vue-croppie
						ref="croppieRef"
						:mouseWheelZoom="false"
						:viewport="{ width: 200, height: 200 }"
						@result="fn"
				>
				</vue-croppie>
				<img v-bind:src="cropped">
			</div>
			<div v-else>
				foo
			</div>
		</div>
		--y
		<!-- hidden input -->
		<input type="file" style="display: none;" @change="handleInputChange" ref="input" :multiple="true" :accept="accept">
	</div>
</template>

<script>
	export default {
		name: "ImageUploadManager",
		data: () => {
			return {
				status : {
					busy : false
				},
				path : '',
				fileQueue : []
			}
		},
		methods: {
			uploadImages()
			{
				this.fileQueue.forEach( (queueItem, index) => {
					if(queueItem.done === null) this.uploadFile(queueItem).then( (success) => {
						this.fileQueue[index].done = (success === true);
					})
				})
			},
			browseComputer()
			{
				if(!this.status.busy) this.$refs['input'].click();
			},
			
			handleInputChange(event)
			{
				let files = event.target.files;
				if(!files) return false;
				
				for(let i = 0; i < files.length; i++)
				{
					let randName = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
					let ext = files[i].name.split('.').pop();
					let blob = files[i].slice(0, -1, files[i].type);
					
					let file = new File([blob], randName + '.' + ext, {
						type: files[i].type,
						size: files[i].size
					});
					
					this.fileQueue.push({
						index : i,
						file : file,
						name : file.name,
						size : file.size,
						preview : false,
						cropped: null,
						url : '',
						progress: 0,
						done : null
					});
				}
			},
			uploadFile( queueItem )
			{

				
				let formData = new FormData();
				
				formData.append('image', queueItem.file, queueItem.file.name);
				console.log(queueItem.file)
				return new Promise( (resolve, reject) =>
				{
					this.axios.post('api/' + this.apiRoute, formData,
					{
						onUploadProgress: progressEvent => {
							this.fileQueue[queueItem.index].progress = (progressEvent.loaded / progressEvent.total) * 100;
						}
					})
					.then(response => {
						this.fileQueue[queueItem.index].done = true;
						this.fileQueue[queueItem.index].url = response.data.path;
					}).catch( () => {
						this.fileQueue[queueItem.index].done = false;
					})
				});
			},
			cropDialog( queueItem ){
			
			},
			bind(file)
			{
				var reader = new FileReader();
				
				reader.onload = function(event)
				{
					element.bind({ url: event.target.result });
				};
				
				reader.readAsDataURL(file);
			},
			// CALBACK USAGE
			crop() {
				// Here we are getting the result via callback function
				// and set the result to this.cropped which is being
				// used to display the result above.
				let options = {
					format: 'jpeg',
					circle: false
				};
				this.$refs['croppieRef'].result(options, (output) => {
					this.cropped = output;
				});
			},
			// EVENT USAGE
			cropViaEvent() {
				this.$refs['croppieRef'].result(options);
			},
			result(output) {
				this.cropped = output;
			},
			update( val, index ) {
				this.fileQueue[index].cropped = val;
			},

		},
		props: {
		
			apiRoute : {
				type: String,
				required: true,
			},
			multiple : {
				required: false,
				type: Boolean,
				default: () => false
			},
			ratioX : {
				type: Number,
				required: false
			},
			ratioY : {
				type: Number,
				required: false
			},
			maxFileSize : {
				type: Number,
				required: false,
				default: () => 0
			},
			accept : {
				type : String,
				required:false,
				default: () => 'image/*'
			}
		},
		
	}
</script>

<style scoped>
	.viewport {
		width: 600px;
		height: 200px;
		overflow: hidden;
		background-repeat: no-repeat;
	}
	
	.files {
		display: flex;
		flex-direction: column;
		border: 1px solid black;
		padding: 2px 4px 2px 4px;
	}
	
	.file {
		margin: 2px;
		border: 1px solid red;
	}
	
</style>
