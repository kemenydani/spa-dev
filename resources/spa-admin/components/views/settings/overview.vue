<template>
	<v-content>
			<form>
				<div v-for="config in formConfig">
					<component style=""
							v-model="config.data.val"
							:is="config.inputType"
							:error-messages="errors.collect(config.data.codename)"
							v-bind="config.props"
							@focus="config.focused = true"
							@blur="config.focused = false"
		          @change="saveSetting.call(this, config)"
							></component>
				</div>
			</form>
		
		<v-dialog v-model="pageHint" max-width="600">
			<v-card>
				<v-card-title class="headline">Help</v-card-title>
				<v-card-text>
					<h3>Text fields</h3>
					In order to update your changes, always hit the save icon after modifying a field value.<br><br>
					<h3>Checkboxes</h3>
					Checkboxes automatically update themselves after you change them.
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="blue" flat="flat" @click.native="pageHint  = false">close</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
		
		<!-- fab -->
		<v-btn
				@click="pageHint = true"
				fab
				bottom
				right
				color="blue"
				dark
				fixed>
			<v-icon>help</v-icon>
		</v-btn>
		
	</v-content>
</template>

<script>
	
	export default {
		$_veeValidate: {
			validator: 'new'
		},
		name: 'settings-overview',
		data: () => ({
			pageHint: false,
			formConfig : [],
			dictionary: {
				attributes: {
					email: 'E-mail Address'
					// custom attributes
				},
				custom: {
					name: {
						required: () => 'Name can not be empty',
						max: 'The name field may not be greater than 10 characters'
						// custom messages
					},
					select: {
						required: 'Select field is required'
					}
				}
			}
		}),
		methods: {
			fetchSettings(){
				this.$app.$emit('toast', 'Loading settings...', 'info');
				return this.axios.get('/api/setting/fetchSettings')
					.then((response) =>
					{
						if(response.status === 200) {
							this.$app.$emit('toast', 'Settings loaded', 'success');
							return response.data;
						}
						throw new Error(response.statusText);
					})
					.catch( error =>
					{
						this.$app.$emit('toast', 'Database error: could not fetch settings', 'error');
					})
			},
			saveSetting( config, event = 'change' )
			{
					if(event === 'change' && config.inputType !== 'v-checkbox' ) return false;
					
					this.$app.$emit('toast', 'Updating ' + config.props.label + '...' , 'info');
				
					config.props.loading = true;
					
					this.updateSetting( config.data ).then((response) =>
					{
						config.props.loading = false;
						this.$app.$emit('toast', 'Updated ' + config.props.label , 'success');
					});
			},
			updateSetting( data ){
				
				return this.axios.post('/api/setting/updateSetting', { data : data } )
					.then( response => {
					
					}).catch( error => {
					
					})
			},
			parseColumnToConfig( data )
			{
				let config = {};
				config.data = data;
				
				let type = data.hasOwnProperty('input_type') ? data.input_type : null;
				
				config.props = {};
				
				config.props.label = data.alias ? data.alias : data.codename;
				config.focused = false;
				config.props.loading = false;
				
				switch ( type ) {
					case 'text' :
						config.inputType = 'v-text-field';
						config.props['append-icon'] = 'save';
						config.props['append-icon-cb'] = this.saveSetting.bind(this, config, 'click');
						break;
					case 'textarea' :
						config.inputType = 'wysiwyg';
						config.textarea = true;
						break;
					case 'bool' :
						config.inputType = 'v-checkbox';
						break;
				}
	
				return config;
			},
			submit () {
				//this.$validator.validateAll()
			},
			renderForm(){
				this.fetchSettings().then(( data ) => {
					data.forEach(( column ) => {
						let config = this.parseColumnToConfig(column);
						this.formConfig.push(config);
					})
				})
			},
		},
		mounted () {
			this.renderForm();
			this.$validator.localize('en', this.dictionary)
		},
	}
</script>

<style lang="scss">
	.setting-field {
		i {
			cursor:pointer;
		}
	}
</style>