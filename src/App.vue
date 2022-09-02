<template>
  <v-app>
    <v-container>
      <v-row>
        <v-col
			cols="12"
			sm="4"
			md="4"
			lg="4"
        >
        <v-text-field
			style="margin-top: 40px;"
			outlined
			label="Todo Task"
			v-model="todo.tasks"
        ></v-text-field>
		<v-btn
			color="primary"
			@click="taskSubmit"
			:disabled="isDisable"
		>
			{{ this.todo.id == '' ? 'Add Task' : 'Update Task' }}
		</v-btn>
        </v-col>
		<v-col
			cols="12"
			sm="8"
			md="8"
			lg="8"
		>
			<v-simple-table dense style="margin-top: 25px;">
				<template v-slot:default>
					<tbody>
						<tr
							v-for="task in tasks"
							:key="task.id"
						>
						<td>
							<v-checkbox
								color="blue"
								:label="task.tasks"
								v-model="task.status"
								@change="taskDone(task)"
							></v-checkbox>
						</td>
						<td>
							<v-btn
								class="mx-2"
								fab
								dark
								small
								color="blue"
								:value="task.id"
								@click="editMode(task)"
							>
								<v-icon dark>
									mdi-pencil
								</v-icon>
							</v-btn>
							<v-btn
								class="mx-2"
								fab
								dark
								small
								color="red darken-2"
								:value="task.id"
								@click="delTask(task.id)"
							>
								<v-icon dark>
									mdi-trash-can
								</v-icon>
							</v-btn>
						</td>
					</tr>
					</tbody>
				</template>
			</v-simple-table>
		</v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
export default {
    name: 'App',
	data() {
		return {
			todo: {
				id: '',
				tasks: ''
			},
			dialog: false,
			headers: [
				{ text: 'Task List', value: 'task' },
				{ text: 'Actions', value: 'actions', sortable: false }
			],
			rows: [],
			loading: false,
			tasks: [],
		}
	},

	created() {
		this.getTasks();
	},

	computed: {
		isDisable() {
			if (this.todo.tasks.length > 0) {
				return false;
			} else {
				return true;
			}
		}
	},

	methods: {
		async getTasks() {
			await this.$http.get(this.$dir + '/api/task/getTasks')
			.then(res => {
				this.tasks = [];
				for (let i = 0; i < res.data.length; i++) {
					let status = res.data[i]['status'] == 1 ? false : true;
					this.tasks.push({
						id: res.data[i]['id'],
						tasks: res.data[i]['tasks'],
						status: status
					});
				}
			});
		},

		async taskDone(task) {
			await this.$http.post(this.$dir + '/api/task/updateTaskStatus/' + task.id, { status: task.status })
			.then(() => { this.getTasks(); })
			.catch(err => { console.log(err); });
		},

		async editMode(task) {
			this.todo.id = await task.id;
			this.todo.tasks = await task.tasks
			this.dialog = true;
		},

		async delTask(taskId) {
			await this.$http.delete(this.$dir + '/api/task/deleteTask/' + taskId)
			.then(() => { this.getTasks(); })
			.catch(err => { console.log(err); });
		},

		async taskSubmit() {
			if (this.todo.id == '') {
				await this.$http.post(this.$dir + '/api/task/addTask', {
					task: this.todo.tasks
				})
				.then(res => {
					if (res.data.success) {
						this.todo.id = '';
						this.todo.tasks = '';
						this.getTasks();	
					}
				})
				.catch(err => { console.log(err); });	
			} else {
				await this.$http.post(this.$dir + '/api/task/updateTask/' + this.todo.id, {
					task: this.todo.tasks
				})
				.then(res => {
					if (res.data.success) {
						this.todo.id = '';
						this.todo.tasks = '';
						this.getTasks();	
					}
				})
				.catch(err => { console.log(err); });
			}
		}
	}
}
</script>

<style>
	.blue--text label {
		text-decoration: line-through;
	}
</style>