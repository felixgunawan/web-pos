<template>
	<div>
		&nbsp;<br>
			<table class="table table-bordered table-striped table-dark">
				<thead>
					<tr>
						<th v-on:click="sort('code',0)">Code &nbsp;<span v-show="showAsc[0]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[0]" class="glyphicon glyphicon-chevron-down"></span></th>
						<th v-on:click="sort('name',1)">Name &nbsp;<span v-show="showAsc[1]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[1]" class="glyphicon glyphicon-chevron-down"></span></th>
						<th v-on:click="sort('details',2)">Details &nbsp;<span v-show="showAsc[2]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[2]" class="glyphicon glyphicon-chevron-down"></span></th>
						<th><button class="btn btn-primary" v-on:click="show_search()">
						<span class="glyphicon glyphicon-search"></span>
						</button></th>
					</tr>
				</thead>
				<tbody>
					<template v-if = "showSearch">
					<tr>
						<td>
							<div class="row">
							<div class="col-xs-6 col-md-12">
								<div class="input-group">
								<input type="text" class="form-control" placeholder="Search" v-model="searchVar.code" v-on:keyup="search()">
								</div>
							</div>
							</div>
						</td>
						<td>
							<div class="row">
							<div class="col-xs-6 col-md-12">
								<div class="input-group">
								<input type="text" class="form-control" placeholder="Search" v-model="searchVar.name" v-on:keyup="search()">
								</div>
							</div>
							</div>
						</td>
						<td>
						</td>
						<td>
						</td>
					</tr>
					</template>
					<tr v-for="department, index in departments">
						<template v-if = "!editInput[index]">
							<td>{{ department.code }}</td>
							<td>{{ department.name }}</td>
							<td>{{ department.details }}</td>
							<td>
								<a
									 class="btn btn-xs btn-warning"
									 v-on:click="editEntry(index)">
									 Edit
								</a>
								<a
									 class="btn btn-xs btn-danger"
									 v-on:click="deleteEntry(department.id, index)">
									Delete
								</a>
							</td>
						</template>
						<template v-else>
							<td><input type="text" v-model="department.code" class="form-control"><p style="color:red;" v-if="!$v.departments.$each[index].code.required">The code field is required!</p></td>
							<td><input type="text" v-model="department.name" class="form-control"><p style="color:red;" v-if="!$v.departments.$each[index].name.required">The name field is required!</p></td>
							<td><input type="text" v-model="department.details" class="form-control"><p style="color:red;" v-if="!$v.departments.$each[index].details.required">The details field is required!</p></td>
							<button
								 class="btn btn-xs btn-success"
								 v-on:click="updateEntry(department.id,index)" type="button" :disabled="$v.departments.$invalid">
								 Update
							</button>
							<button
								 class="btn btn-xs btn-default"
								 v-on:click="cancelEntry(index)">
								 Cancel
							</button>
						</template>
					</tr>
				</tbody>
			</table>
		<ul class = "pagination">
			<li v-if="pagination.current_page > 1">
					<a href="javascript:void(0)" aria-label="Previous" v-on:click="changePage(pagination.current_page - 1)">
							<span aria-hidden="true">«</span>
							</a>
					</li>
			<li v-for="page in pagesNumber" :class="{'active': page == pagination.current_page}">
					<a href="javascript:void(0)" v-on:click="changePage(page)">{{ page }}</a>
					</li>
			<li v-if="pagination.current_page < pagination.last_page">
					<a href="javascript:void(0)" aria-label="Next" v-on:click="changePage(pagination.current_page + 1)">
							<span aria-hidden="true">»</span>
							</a>
					</li>
		</ul>
	</div>
</template>

<script>
	import { required,numeric } from 'vuelidate/lib/validators'
	export default {
		computed: {
			pagesNumber() {
				if (!this.pagination.to) {
					return [];
				}
				let from = this.pagination.current_page - this.offset;
				if (from < 1) {
					from = 1;
				}
				let to = from + (this.offset * 2);
				if (to >= this.pagination.last_page) {
					to = this.pagination.last_page;
				}
				let pagesArray = [];
				for (let page = from; page <= to; page++) {
					pagesArray.push(page);
				}
					return pagesArray;
			}
		},
		data: function () {
			return {
				pagination: {
					total: 0,
					per_page: 2,
					from: 1,
					to: 0,
					current_page: 1
				},
				departments:[],
				offset: 4,
				editInput: [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],
				searchVar : {
					code : null,
					name : null,
					details : null,
					},
				showAsc : [false,false,false,false,false,false],
				showDesc : [false,false,false,false,false,false],
				isAsc : 'desc',
				orderVar : null,
				showSearch : false,
			}
		},
		validations: {
			departments: {
				$each: {
					code: {
						required
					},
					name: {
						required
					},
					details: {
						required
					},
				}
			}
		},
		mounted() {
			var app = this;
			axios.get('multicom-pos/public/api/v1/departments?page='+ app.pagination.current_page)
					.then(function (resp) {
							app.pagination = resp.data;
							app.departments = resp.data.data;
					})
					.catch(function (resp) {
							console.log(resp);
							alert("Could not load departments");
					});
		},
		methods: {
			show_search(){
				var app = this;
				if(app.showSearch){
					app.showSearch = false;
				}else{
					app.showSearch = true;
				}
			},
			search(){
				var app=this;
				app.pagination.current_page = 1;
				app.refresh();
			},
			sort(orderVar_template,index){
				var app = this;
				app.orderVar = orderVar_template;
				app.pagination.current_page = 1;
				if (app.isAsc == 'asc'){
					app.isAsc = 'desc';
					app.showAsc = [false,false,false,false,false,false];
					app.showDesc = [false,false,false,false,false,false];
					app.showDesc.splice(index, 1, true);
				} else {
					app.isAsc = 'asc';
					app.showAsc = [false,false,false,false,false,false];
					app.showDesc = [false,false,false,false,false,false];
					app.showAsc.splice(index, 1, true);
				}
				app.refresh();
						},
						changePage(page) {
				var app = this;
				app.pagination.current_page = page;
				app.refresh();
						},
			refresh(){
				var app = this;
				app.editInput = [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false];
				var ajaxCall = 'multicom-pos/public/api/v1/departments?';
				var x = app.searchVar;
				var y = app.orderVar;
				var page = app.pagination.current_page;
				
				ajaxCall += 'page=';
				ajaxCall += page;
				if ((x.code != null)&&(x.code != "")){
					ajaxCall += '&searchVarCode=';
					ajaxCall += x.code;
				}
				if ((x.name != null)&&(x.name != "")){
					ajaxCall += '&searchVarName=';
					ajaxCall += x.name;
				}
				if ((x.details != null)&&(x.details != "")){
					ajaxCall += '&searchVarAddress=';
					ajaxCall += x.details;
				}
				if ((x.city != null)&&(x.city != "")){
					ajaxCall += '&searchVarCity=';
					ajaxCall += x.city;
				}
				if ((x.phone != null)&&(x.phone != "")){
					ajaxCall += '&searchVarPhone=';
					ajaxCall += x.phone;
				}
				if ((x.deadline != null)&&(x.deadline != "")){
					ajaxCall += '&searchVarDeadline=';
					ajaxCall += x.deadline;
				}
				if (y != null){
					ajaxCall += '&orderVar=';
					ajaxCall += y;
					ajaxCall += '&isAsc=';
					ajaxCall += app.isAsc;
				}
				axios.get(ajaxCall)
					.then(function (resp) {
						app.pagination = resp.data;
						app.departments = resp.data.data;
						console.log(ajaxCall);
					})
					.catch(function (resp) {
						console.log(resp);
						alert("No result found");
					});
			},
			editEntry(index){
				var app=this;
				app.editInput.splice(index, 1, true);
			},
			cancelEntry(index){
				var app=this;
				app.editInput.splice(index, 1, false);
				location.reload();
			},
			updateEntry(id, index){
				var app = this;
				var newDepartment = app.departments[index];
				axios.patch('multicom-pos/public/api/v1/departments/' + id, newDepartment)
						.then(function (resp) {
								alert(app.departments[index].code + " " + app.departments[index].name + " was updated");
						})
						.catch(function (resp) {
								console.log(resp);
								alert("Could not edit your department");
						});
					app.editInput.splice(index, 1, false);
					location.reload();
			},
			deleteEntry(id, index) {
					if (confirm("Do you really want to delete it?")) {
							var app = this;
							axios.delete('multicom-pos/public/api/v1/departments/' + id)
									.then(function (resp) {
										alert(app.departments[index].code + " " + app.departments[index].name + " was deleted");
											app.departments.splice(index, 1);
									})
									.catch(function (resp) {
										location.reload();
										alert("Could not delete department");
									});
							location.reload();
					}
			},
		}
	}
</script>
