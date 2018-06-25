
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
			<v-card v-for="queueItem in fileQueue" :color="queueItem.done === true ? '' : queueItem.done === false ? '' : ''">
				<v-card-title>{{ queueItem.name }} ({{ queueItem.size }} Bytes) {{ queueItem.index }}</v-card-title>
				<v-card-actions>
					<v-btn small outline fab @click="cropDialog(queueItem)" color="teal" v-if="queueItem.done === null">
						<v-icon>crop_free</v-icon>
					</v-btn>
					<v-spacer></v-spacer>
					<v-btn icon @click.native="queueItem.preview = !queueItem.preview">
						<v-icon>{{ queueItem.preview ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</v-icon>
					</v-btn>
				</v-card-actions>
				<v-slide-y-transition>
					<v-card-text v-show="queueItem.preview" style="overflow: hidden; position: relative;">
						<img :src="queueItem.imageState" style="width: 500px; height: auto">
					</v-card-text>
				</v-slide-y-transition>
				<v-progress-linear :buffer-value="queueItem.buffer" v-model="queueItem.progress" buffer></v-progress-linear>
			</v-card>
		</div>
		<input type="file" style="display: none;" @change="handleInputChange" ref="input" :multiple="true" :accept="accept">
		
		<v-dialog
				v-model="crop.show"
				fullscreen
				hide-overlay
				transition="dialog-bottom-transition"
				scrollable
		>
			<v-card tile>
				<v-toolbar card dark color="primary">
					<v-btn icon dark @click.native="crop.show = false">
						<v-icon>close</v-icon>
					</v-btn>
					<v-toolbar-title>Settings</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
						<v-btn dark flat @click.native="crop.show = false">Save</v-btn>
					</v-toolbar-items>
				</v-toolbar>
				
				<v-card-text>
					<vue-croppie
							ref="croppieRef"
							:enableOrientation="true"
							@result="result"
							@update="update">
					</vue-croppie>
					<img v-bind:src="crop.item.imageState" style="width: 50%; height: auto">
					<hr>
					<button @click="bind()">Bind</button>
					<!-- Rotate angle is Number -->
					<button @click="rotate(-90)">Rotate Left</button>
					<button @click="rotate(90)">Rotate Right</button>
					<button @click="cropImage()">Crop Via Callback</button>
					<button @click="cropViaEvent()">Crop Via Event</button>
				</v-card-text>
				
				<div style="flex: 1 1 auto;"></div>
			</v-card>
		</v-dialog>
	
	</div>
</template>

<script>
	export default {
		name: "ImageUploadManager",
		data: () => {
			return {
				crop: {
					crop : false,
					item : {
						index : 0,
						imageState : '',
					}
				},
				status : {
					busy : false,
					progress: 0,
				},
				path : '',
				fileQueue : [],
			}
		},
		methods: {
			uploadImages()
			{
				this.uploadFiles( this.fileQueue.filter( item => item.done === null ) );
			},
			browseComputer()
			{
				if(!this.status.busy) this.$refs['input'].click();
			},
			handleInputChange(event)
			{
				let files = event.target.files;
				if(!files) return false;
				
				function dataURLtoBlob(dataurl) {
					var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
						bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
					while(n--){
						u8arr[n] = bstr.charCodeAt(n);
					}
					return new Blob([u8arr], {type:mime});
				}
				
				var self = this;
				
				for(let i = 0; i < files.length; i++)
				{
					(function(originalFile)
					{
						
						let reader = new FileReader();
		
						reader.onload = function(readerEvent)
						{
							let image = new Image();
							image.onload = function (e) {
								// Resize the image
								var canvas = document.createElement('canvas'),
									max_size = 1200,
									width = image.width,
									height = image.height;
								if (width > height) {
									if (width > max_size) {
										height *= max_size / width;
										width = max_size;
									}
								} else {
									if (height > max_size) {
										width *= max_size / height;
										height = max_size;
									}
								}
								canvas.width = width;
								canvas.height = height;
								canvas.getContext('2d').drawImage(image, 0, 0, width, height);
								let dataUrl = canvas.toDataURL('image/jpeg');
								let resizedImage = dataURLtoBlob(dataUrl);
								
								let fileName = originalFile.name;
								fileName = fileName.split('.')[0];
								
								self.fileQueue.push({
									index : i,
									file : resizedImage,
									name : fileName,
									preview: false,
									size : resizedImage.size,
									cropped: null,
									imageState : dataUrl,
									progress: 0,
									buffer : 0,
									done : null
								});
								
							};
							image.src = readerEvent.target.result;
						};
						
						reader.readAsDataURL(originalFile);
						
					}(files[i]));
				}
			},
			uploadFiles( queueItems )
			{
				let formData = new FormData();
				
				queueItems.forEach( ( queueItem, index ) => {
					formData.append('image_' + index, queueItem.file, index + '_' + queueItem.file.name);
				});
				
				return new Promise( (resolve, reject) =>
				{
					
					this.axios.post('api/' + this.apiRoute, formData,
					{
						onUploadProgress: progressEvent => {
							
							this.fileQueue.forEach( ( queueItem, ix ) =>
							{
								if(queueItem.done === null){
									this.fileQueue[ix].progress = ((progressEvent.loaded / progressEvent.total) * 100) / 1.5;
									this.fileQueue[ix].buffer = ((progressEvent.loaded / progressEvent.total) * 100);
								}
							});
						}
					})
					.then(response => {
						
						let keys = response.data.result;
						
						this.fileQueue.forEach( ( queueItem, ix ) => {
							if(keys.hasOwnProperty('image_' + ix) && queueItem.done === null){
								this.fileQueue[ix].buffer = 100;
								this.fileQueue[ix].progress = 100;
								this.fileQueue[ix].done = true;
								this.fileQueue[ix].imageState = response.data.images['image_' + ix];
							} else {
								this.fileQueue[ix].buffer = 0;
								this.fileQueue[ix].progress = 0;
								this.fileQueue[ix].done = false;
							}
						})
						
					}).catch( () => {
						//this.fileQueue[queueItem.index].done = false;
					})
				});
			},
			cropDialog( queueItem )
			{
				this.crop.show = true;
				this.crop.item = queueItem;
				this.bind(queueItem.imageState)
			},
			bind(file)
			{
				var reader = new FileReader();
				
				this.$refs.croppieRef.bind({
					url: file,
				});
				/*
				reader.onload = function(event)
				{
					this.$refs.croppieRef.bind({
						url: event.target.result,
					});
		
				}.bind(this);
				
				reader.readAsDataURL(file);
				*/
			},
			// CALBACK USAGE
			cropImage() {

				let options = {
					format: 'jpeg',
					circle: false
				};
				this.$refs['croppieRef'].result(options, (output) => {
					console.log(output)
				});
				
			},
			// EVENT USAGE
			cropViaEvent() {
				this.$refs['croppieRef'].result(options);
			},
			result(output) {
			
			},
			update( val, index ) {
	
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
