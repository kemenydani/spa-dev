<template>

				<v-layout row wrap>
					<v-flex sm12>
						<v-select
								label="Select"
								v-bind:items="available"
								v-model="collection"
								item-text="name"
								item-value="item"
								multiple
								chips
								max-height="auto"
								autocomplete
								@input="change()"
						>
							<template slot="selection" slot-scope="data">
								<v-chip
										close
										@input="data.parent.selectItem(data.item)"
										:selected="data.selected"
										class="chip--select-multi"
										:key="JSON.stringify(data.item)"
								>
									{{ data.item.name }}
								</v-chip>
							</template>
							<template slot="item" slot-scope="data">
								<template v-if="typeof data.item !== 'object'">
									<v-list-tile-content v-text="data.item"></v-list-tile-content>
								</template>
								<template v-else>
									<v-list-tile-content>
										<v-list-tile-title v-html="data.item.name"></v-list-tile-title>
									</v-list-tile-content>
								</template>
							</template>
						</v-select>
					</v-flex>
				</v-layout>
</template>

<script>
	
	import Category from '../../model/Category';
	
	export default {
		data() {
			return {
				collection: [
				
				],
				available: [
	
				]
			}
		},
		props: {
			selected: {
				type: Array,
				required: false,
				function () {
					return [];
				}
			}
		},
		watch: {
			'collection': function(b, a){
				console.log( b )
				console.log( a )
			}
		},
		methods: {
			change(){
				this.$emit('update', this.collection)
			},
			getAll()
			{
				let C = new Category();
				
				C.getAll().then( categories => {
					this.available = categories
				})
			},
		},
		created()
		{
			this.collection = this.selected;
			this.getAll();
		},
	}
</script>

<style>

</style>