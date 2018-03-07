<template>
	<div>
		role overview
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
					Create Role
				</v-card-title>
				<v-container grid-list-sm class="pa-4">
					<v-layout row wrap>
						<v-flex xs12 align-center justify-space-between>
							<v-layout align-center>
								<v-avatar size="40px" class="mr-3">
									<img
											src="//ssl.gstatic.com/s2/oz/images/sge/grey_silhouette.png"
											alt=""
									>
								</v-avatar>
								<v-text-field
										placeholder="Name"
								></v-text-field>
							</v-layout>
						</v-flex>
						<v-flex xs12>
							<permission-field @update="categoriesUpdated($event)" :selected="formModel.categories"></permission-field>
							<v-text-field
									prepend-icon="playlist_add_check"
									placeholder="Permissions"
							></v-text-field>
						</v-flex>
						<v-flex xs12>
							<v-text-field
									prepend-icon="notes"
									placeholder="Notes"
							></v-text-field>
						</v-flex>
					</v-layout>
				</v-container>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn flat color="primary" @click="dialog = false">Cancel</v-btn>
					<v-btn flat @click="dialog = false">Save</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>

<script>
	
	export default {
		name: 'role-overview',
		data() {
			return {
				dialog: false,
				data_field: 'data_field',
				items: [
					{ icon: 'contacts', text: 'Contacts' },
					{ icon: 'history', text: 'Frequently contacted' },
					{ icon: 'content_copy', text: 'Duplicates' },
					{
						icon: 'keyboard_arrow_up',
						'icon-alt': 'keyboard_arrow_down',
						text: 'Labels',
						model: true,
						children: [
							{ icon: 'add', text: 'Create label' }
						]
					},
					{
						icon: 'keyboard_arrow_up',
						'icon-alt': 'keyboard_arrow_down',
						text: 'More',
						model: false,
						children: [
							{ text: 'Import' },
							{ text: 'Export' },
							{ text: 'Print' },
							{ text: 'Undo changes' },
							{ text: 'Other contacts' }
						]
					},
					{ icon: 'settings', text: 'Settings' },
					{ icon: 'chat_bubble', text: 'Send feedback' },
					{ icon: 'help', text: 'Help' },
					{ icon: 'phonelink', text: 'App downloads' },
					{ icon: 'keyboard', text: 'Go to the old version' }
				]
			}
		},
		mounted() {
			console.log('new component mounted');
		}
	}
</script>

<style lang="scss">

</style>