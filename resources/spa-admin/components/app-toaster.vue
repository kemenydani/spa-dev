<template>
	<v-snackbar v-bind="config" :color="color" v-model="active">
			<div v-html="html"></div>
			<v-btn dark flat @click.native="active = false">Close</v-btn>
	</v-snackbar>
</template>

<script>
	export default {
		name: "app-toaster",
		data () {
			return {
				active : false,
				html : 'Toast!',
				color : 'info',
				config : {
					'timeout' : 5000,
					'top' : true,
					'left' : true,
					'bottom' : false,
					'right' : false,
					'multi-line' : false,
					'vertical' : true,
					'auto-height' : true
				}
			}
		},
		created(){
			this.$app.$on('toast', ( html = '', color = 'info', config = {} ) =>
			{
				this.active = true;
				if(color) this.color = color;
				if(html)  this.html = html;
				Object.keys(config).forEach( key => this.config[key] = config[key])
			})
		}
	}
</script>