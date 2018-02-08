<template>
	<v-container>
		<v-layout row>
			<v-flex md7 lg7>
			<v-form>
				
				<!-- title -->
				<v-layout row>
					<v-text-field
						v-model="formModel.title"
						label="Title"
						:counter="10"
						:error-messages="errors.collect('title')"
						v-validate="'required|max:10'"
						data-vv-name="title"
						required
				></v-text-field>
				</v-layout>
				
				<!-- teaser -->
				<v-layout row>
					<v-text-field
						v-model="formModel.teaser"
						label="Teaser"
						:error-messages="errors.collect('teaser')"
						v-validate="'required'"
						data-vv-name="teaser"
						required
				></v-text-field>
				</v-layout>
				
				<!-- categories -->
				
				<v-layout row>
					
					<category-field @update="categoriesUpdated($event)" :selected="formModel.categories"></category-field>
					
				</v-layout>
				
				<!-- content -->
				<v-layout row>
					<v-text-field
						v-model="formModel.content"
						label="Content"
						:counter="500"
						:error-messages="errors.collect('content')"
						v-validate="'required|max:500'"
						data-vv-name="content"
						required
						textarea
						:rows="15"
				></v-text-field>
				</v-layout>
				
				<!-- activate -->
				<!---
				<v-layout row wrap>
					<v-flex sm12>
						<v-checkbox
								label="Activate Immediately"
								v-model="formModel.activate"
						></v-checkbox>
					</v-flex>
				</v-layout>
				<v-layout row wrap>
				
					<v-flex v-show="!formModel.activate" sm12 lg5>
						<v-dialog
								persistent
								v-model="modal"
								lazy
								full-width
								width="290px"
						>
							<v-text-field
									slot="activator"
									label="Select activation date"
									v-model="date"
									append-icon="event"
									readonly
							></v-text-field>
							<v-date-picker v-model="date" scrollable actions>
								<template slot-scope="{ save, cancel }">
									<v-card-actions>
										<v-spacer></v-spacer>
										<v-btn flat color="primary" @click="cancel">Cancel</v-btn>
										<v-btn flat color="primary" @click="save">OK</v-btn>
									</v-card-actions>
								</template>
							</v-date-picker>
						</v-dialog>
					</v-flex>
					<v-spacer></v-spacer>
					<v-flex v-show="!formModel.activate" sm12 lg5>
						<v-dialog
								persistent
								v-model="modal2"
								lazy
								full-width
								width="290px"
						>
							<v-text-field
									slot="activator"
									label="Select activation time"
									v-model="time"
									append-icon="access_time"
									readonly
							></v-text-field>
							<v-time-picker v-model="time" actions>
								<template slot-scope="{ save, cancel }">
									<v-card-actions>
										<v-btn flat color="primary" @click="cancel">Cancel</v-btn>
										<v-btn flat color="primary" @click="save">Save</v-btn>
									</v-card-actions>
								</template>
							</v-time-picker>
						</v-dialog>
					</v-flex>

				</v-layout>
				-->
				
				<v-btn @click="submit">submit</v-btn>
				<v-btn @click="clear">clear</v-btn>
			</v-form>
			</v-flex>
		</v-layout>
		
		<v-btn @click="$router.back()"
		       fab
		       bottom
		       right
		       color="red"
		       dark
		       fixed
		>
			<v-icon>arrow_back</v-icon>
		</v-btn>
		
	</v-container>
</template>

<script>
	
	import Article from '../../../models/Article';
	import CategoryField from '../../fields/category';
	
	export default {
		components: { CategoryField },
		name: 'article-create',
		$validates: true,
		data () {
			return {
				formModel: {
					title: '',
					teaser: '',
					content: '',
					categories: [],
					activate: true,
				},
				title: '',
				teaser: '',
				content: '',
			}
		},
		methods: {
			remove(item) {
				this.chips.splice(this.chips.indexOf(item), 1)
				this.chips = [...this.chips]
			},
			categoriesUpdated( categories )
			{
				this.formModel.categories = categories;
			},
			submit ()
			{
				let valid = this.$validator.validateAll();
				
				if( valid )
				{
					let A = new Article();
					
					A.create({
						'title'      : this.formModel.title,
						'teaser'     : this.formModel.teaser,
						'content'    : this.formModel.content,
						'categories' : this.formModel.categories,
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