<template>
	<v-form style="width: 100%">
		<v-text-field
				v-model="title"
				label="Title"
				:counter="10"
				:error-messages="errors.collect('title')"
				v-validate="'required|max:10'"
				data-vv-name="title"
				required
		></v-text-field>
		<v-text-field
				v-model="teaser"
				label="Teaser"
				:error-messages="errors.collect('teaser')"
				v-validate="'required'"
				data-vv-name="teaser"
				required
		></v-text-field>
		<v-text-field
				v-model="content"
				label="Content"
				:counter="500"
				:error-messages="errors.collect('content')"
				v-validate="'required|max:500'"
				data-vv-name="content"
				required
				textarea
		></v-text-field>
		
		<v-btn @click="submit">submit</v-btn>
		<v-btn @click="clear">clear</v-btn>
	</v-form>
</template>

<script>
	
	import Article from '../../../core/Article';
	
	export default {
		name: 'article-create',
		$validates: true,
		data () {
			return {
				title: '',
				teaser: '',
				content: '',
			}
		},
		methods: {
			submit ()
			{
				let valid = this.$validator.validateAll();
				
				if( valid )
				{
					let A = new Article();
					
					A.create({
						'title'   : this.title,
						'teaser'  : this.teaser,
						'content' : this.content
					}).then( response => {
						console.log(response);
					}).catch( error => {
						alert(error);
					});
				}
			},
			clear () {
				this.title = '';
				this.teaser = '';
				this.content = '';
				this.$validator.reset();
			}
		}
	}
</script>

<style lang="scss">

</style>