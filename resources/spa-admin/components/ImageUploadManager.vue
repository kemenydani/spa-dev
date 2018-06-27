
<template>
	<div class="fileUploader">
		
		<v-card color="grey lighten-3">
			<v-card-text>
				<v-btn @click="browseComputer" :disabled="status.busy" class="white--text" color="grey darken-3">
					BROWSE
					<v-icon right dark>folder</v-icon>
				</v-btn>
				<v-btn @click="onUploadImages" :loading="status.busy" :disabled="activeItemCount === 0 || status.busy" class="white--text" color="success">
					Upload ({{ activeItemCount }} IN QUEUE)
					<v-icon right dark>cloud_upload</v-icon>
				</v-btn>
				<div style="margin-left: 8px; margin-right: 8px; margin-top: 10px;" v-if="forceCrop" small color="grey" type="warning" icon="crop_free" :value="true">
					Image(s) must be resized before upload.
				</div>
			</v-card-text>
		</v-card>
		<br>
		<div>
			<v-card color="grey lighten-3" class="" style="margin-bottom: 10px;" :key="key"  v-if="!queueItem.removed" v-for="(queueItem, key) in imageQueue">
				<v-card-title>
					<b>{{ queueItem.fileName }}</b>&nbsp;<small>({{ (queueItem.size * 0.000001).toFixed(2) }} MB)</small>
				</v-card-title>
				<v-card-media v-if="queueItem.progress">
					<v-progress-linear :color="queueItem.done === true ? 'success' : ''" :buffer-value="queueItem.buffer" v-model="queueItem.progress" buffer></v-progress-linear>
				</v-card-media>
				<v-card-actions>
							<v-btn small flat @click="removeQueueItem(queueItem.index)" class="" v-if="queueItem.done !== true">
								<v-icon color="error">delete_sweep</v-icon>&nbsp;remove
							</v-btn>
							<v-btn small flat @click="initCropDialog(queueItem)"class="" v-if="allowCrop && queueItem.done === null">
								<v-icon color="amber darken-2">crop_free</v-icon>&nbsp;resize
							</v-btn>
							<v-spacer></v-spacer>
							<v-btn flat class="" small @click.native="queueItem.preview = !queueItem.preview">
								show
								<v-icon light>{{ queueItem.preview ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</v-icon>
							</v-btn>
				</v-card-actions>
				<v-slide-y-transition>
					<v-card-text v-show="queueItem.preview" style="overflow: hidden; position: relative;">
						<img :src="queueItem.imageDataUrl" style="min-width: 100%; max-width: 100%; height: auto">
						<br>
						Real dimensions: {{ queueItem.width }} x {{ queueItem.height }}
					</v-card-text>
				</v-slide-y-transition>
			</v-card>
		</div>
		<input type="file" style="display: none;" @change="handleInputChange" ref="input" :multiple="multiple" :accept="accept">
		
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
					<v-toolbar-title>Crop Image:  {{ crop.item.fileName }}</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
						<v-btn dark flat @click.native="cropImage(crop.item)">CROP</v-btn>
					</v-toolbar-items>
				</v-toolbar>
				
				<v-card-text>
					
					<div style="text-align: center; margin-bottom: 10px;">
						Required size for this image: {{ maxWidth }} x {{ maxHeight }}
					</div>
					
					<div :style="{ minWidth: maxWidth +'px', minHeight: maxHeight +'px'}">
					<vue-croppie :style="{ minWidth: maxWidth +'px', minHeight: maxHeight +'px'}"
					             ref="croppieRef"
					             :enableExif="true"
					             :enableResize="false"
					             :viewport="{ width: maxWidth, height: maxHeight, circle: false }"
					             :boundary="{ width: maxWidth + 30, height: maxHeight + 30}">
					</vue-croppie>
					<img v-bind:src="crop.item.imageDataUrl" style="display: none;">
					</div>
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
				imageQueue : [],
			}
		},
		computed :
			{
			activeItemCount()
			{
				let queueItems = (this.imageQueue.filter( item => item.done === null )).length || 0;
				
				if(queueItems && this.forceCrop)
				{
					queueItems = (this.imageQueue.filter( item => { return (item.done === null && item.cropped === true) } )).length || 0;
				}
				
				return queueItems;
			}
		},
		methods:
			{
				removeQueueItem(index){
					this.imageQueue[index].removed = true;
					this.imageQueue[index].done = false;
				},
				onUploadFinished(){
					this.$emit('uploaded', this.imageQueue)
				},
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
					let ql = this.imageQueue.length;
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
										max_size = this.maxWidth,
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
										index : ql + i,
										width: width,
										height: height,
										file : imageResized,
										fileName : originalFile.name,
										preview: false,
										size : imageResized.size,
										imageDataUrl : dataUrl,
										progress: 0,
										buffer : 0,
										done : null,
										removed : false,
										cropped: false,
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
						formData.append('image_' + queueItem.index, queueItem.file, queueItem.index + '_' + queueItem.file.name,);
					});
					
					return new Promise( (resolve, reject) =>
					{
						this.status.busy = true;
						
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
									if(queueItem.done === null)
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
											this.imageQueue[index].done = false;
										}
									}
								});
								this.status.busy = false;
								
								this.onUploadFinished();
							})
							.catch( () =>
							{
								this.status.busy = false;
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
					this.$refs['croppieRef'].result(options, ( result ) =>
					{
						this.imageQueue[item.index].imageDataUrl = result;
						let file = dataURLtoBlob(result);
						let img = new Image();
						
						this.imageQueue[item.index].file = file;
						this.imageQueue[item.index].size = file.size;
						this.imageQueue[item.index].cropped = true;
						
						img.onload = function()
						{
							this.imageQueue[item.index].width = img.width;
							this.imageQueue[item.index].height = img.height;
						}
						.bind(this);
						
						img.src = result;
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
			allowCrop : {
				type: Boolean,
				required: false,
				default: () => false
			},
			forceCrop : {
				type: Boolean,
				required: false,
				default: () => false
			},
			maxWidth : {
				type: Number,
				required: true,
			},
			maxHeight : {
				type: Number,
				required: true,
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
