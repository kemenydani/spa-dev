<template>
	<div>
		category overview
		<v-btn
				fab
				bottom
				right
				color="red"
				dark
				fixed
				@click.stop="dialog = !dialog"
		>
			<v-icon>add</v-icon>
		</v-btn>
		<v-dialog v-model="dialog" width="800px">
			<v-card>
				<v-card-title
						class="grey lighten-4 py-4 title"
				>
					Create Category
				</v-card-title>
				<v-container grid-list-sm class="pa-4">
					<v-layout row wrap>
						<v-flex xs6>
							<v-text-field
									prepend-icon="business"
									placeholder="Name"
									v-model="formModel.name"
							></v-text-field>
						</v-flex>
						<v-flex xs6>
							<v-text-field
									placeholder="Short name"
									v-model="formModel.name_short"
							></v-text-field>
						</v-flex>
					</v-layout>
				</v-container>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn flat color="primary" @click="dialog = false">Cancel</v-btn>
					<v-btn flat @click="save()">Save</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>

<script>
	
	import Category from '../../../models/Category';
	
	export default {
		name: 'category-overview',
		data() {
			return {
				dialog: false,
				formModel: {
					name: '',
					name_short: '',
				}
			}
		},
		methods: {
			save() {
				//let valid = this.$validator.validateAll();
				//TODO: fix this
				let valid = true;
				
				if (valid) {
					let A = new Category();
					
					A.create({
						'name': this.formModel.name,
						'name_short': this.formModel.name_short,
					}).then(response => {
						this.dialog = false
					}).catch(error => {
						alert(error);
					});
				}
			},
			created() {
			
			},
			mounted() {
			
			}
		}
	}
</script>

<style lang="scss">

</style>