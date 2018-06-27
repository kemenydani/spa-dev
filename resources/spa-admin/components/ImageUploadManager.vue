
<template>
	<div class="fileUploader">
		
		<div>
			<v-btn @click="browseComputer" class="white--text" color="blue-grey">
				Select Image(s)
				<v-icon right dark>folder</v-icon>
			</v-btn>
			<v-btn @click="onUploadImages" class="white--text" color="blue-grey">
				Upload
				<v-icon right dark>cloud_upload</v-icon>
			</v-btn>
			<span>{{ imageQueue.length }} files selected</span>
		</div>
		<br>
		<div>
			<v-card v-for="queueItem in imageQueue" :color="queueItem.done === true ? '' : queueItem.done === false ? '' : ''">
				<v-card-title>{{ queueItem.file.name }} ({{ queueItem.size }} Bytes) {{ queueItem.index }}</v-card-title>
				<v-card-actions>
					<v-btn small outline fab @click="initCropDialog(queueItem)" color="teal" v-if="queueItem.done === null">
						<v-icon>crop_free</v-icon>
					</v-btn>
					<v-spacer></v-spacer>
					<v-btn icon @click.native="queueItem.preview = !queueItem.preview">
						<v-icon>{{ queueItem.preview ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</v-icon>
					</v-btn>
				</v-card-actions>
				<v-slide-y-transition>
					<v-card-text v-show="queueItem.preview" style="overflow: hidden; position: relative;">
						<img :src="queueItem.imageDataUrl" style="width: 500px; height: auto">
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
						<v-btn dark flat @click.native="cropImage(crop.item)">CROP</v-btn>
					</v-toolbar-items>
				</v-toolbar>
				
				<v-card-text>
					<vue-croppie style="width: 1000px"
							ref="croppieRef"
							:enableOrientation="true"
							:viewport="{ width: 800, height: 300, circle: false }"
							:boundary="{ width: 1200, height: 500 }">
					</vue-croppie>
					<img v-bind:src="crop.item.imageDataUrl" style="display: none;">
				</v-card-text>
				
				<div style="flex: 1 1 auto;"></div>
			</v-card>
		</v-dialog>
	
	</div>
</template>

<script>
	
	function dataURLtoBlob(dataUrl) {
		var arr = dataUrl.split(','), 
				mime = arr[0].match(/:(.*?);/)[1], 
				bstr = atob(arr[1]), 
				n = bstr.length, 
				u8arr = new Uint8Array(n);
		
		while(n--){ u8arr[n] = bstr.charCodeAt(n); }
		return new Blob([u8arr], { type : mime });
	}
	
	export default {
		name: "ImageUploadManager",
		data: () => {
			return {
				crop: {
					show : false,
					item : {
						index : 0,
						imageDataUrl : '',
					}
				},
				status : {
					busy : false,
					progress: 0,
				},
				imageQueue : [ ],
			}
		},
		methods:
		{
			onUploadImages()
			{
				this.uploadImages( this.imageQueue.filter( item => item.done === null ) );
			},
			browseComputer()
			{
				if(!this.status.busy) this.$refs['input'].click();
			},
			handleInputChange( event )
			{
				let files = event.target.files;
				if(!files) return false;
				
				for(let i = 0; i < files.length; i++)
				{
					(function(originalFile)
					{
						let reader = new FileReader();
		
						reader.onload = function(readerEvent)
						{
							let image = new Image();
							image.onload = function ( onImageLoadedEvent )
							{
								let canvas = document.createElement('canvas'),
									max_size = this.maxSize,
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
								let imageResized = dataURLtoBlob(dataUrl);
								
								this.imageQueue.push({
									index : i,
									file : imageResized,
									preview: false,
									size : imageResized.size,
									imageDataUrl : dataUrl,
									progress: 0,
									buffer : 0,
									done : null
								});
							}
							.bind(this);
							
							image.src = readerEvent.target.result;
						}
						.bind(this);
						
						reader.readAsDataURL(originalFile);
					}
					.call(this, files[i]));
				}
			},
			uploadImages( queueItems )
			{
				let formData = new FormData();
				
				queueItems.forEach( ( queueItem, index ) =>
				{
					formData.append('image_' + index, queueItem.file, index + '_' + queueItem.file.name,);
				});
				
				return new Promise( (resolve, reject) =>
				{
					
					this.axios.post('api/' + this.apiRoute, formData,
					{
						onUploadProgress: progressEvent =>
						{
							this.imageQueue.forEach( ( queueItem, index ) =>
							{
								if(queueItem.done === null)
								{
									this.imageQueue[index].progress = ((progressEvent.loaded / progressEvent.total) * 100) / 1.5;
									this.imageQueue[index].buffer = ((progressEvent.loaded / progressEvent.total) * 100);
								}
							});
						}
					})
					.then( response => 
					{
						if(!response.data.hasOwnProperty('result')) return false;
						
						let keys = response.data.result;
						
						this.imageQueue.forEach( ( queueItem, index ) =>
						{
							if(keys.hasOwnProperty('image_' + index) && queueItem.done === null)
							{
								this.imageQueue[index].buffer = 100;
								this.imageQueue[index].progress = 100;
								this.imageQueue[index].done = true;
								this.imageQueue[index].imageDataUrl = response.data.images['image_' + index];
							}
							else
							{
								this.imageQueue[index].buffer = 0;
								this.imageQueue[index].progress = 0;
								this.imageQueue[index].done = false;
							}
						})
					})
					.catch( () =>
					{
						
					})
				});
			},
			initCropDialog( queueItem )
			{
				this.crop.show = true;
				this.crop.item = queueItem;
				this.bind(queueItem.imageDataUrl)
			},
			bind( url )
			{
				this.$refs.croppieRef.bind({ url: url });
			},
			cropImage( item )
			{
				this.crop.show = false;
				let options = {
					format: 'jpeg',
				};
				this.$refs['croppieRef'].result(options, (result) => {
					this.imageQueue[item.index].imageDataUrl = result;
					let file = dataURLtoBlob(result);
					this.imageQueue[item.index].file = file;
					this.imageQueue[item.index].size = file.size;
				});
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
            maxSize : {
                type: Number,
                required: true,
				default: () => 1200
            },
            boundaryW : {
                type: Number,
                required: false
            },
            boundaryH : {
                type: Number,
                required: false
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
