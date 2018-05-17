<template>
    <div>
	    &nbsp;<br>
	      <table class="table table-bordered table-striped table-dark">
	          <thead>
	          <tr>
	          	<th v-on:click="sort('code',0)">Code &nbsp;<span v-show="showAsc[0]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[0]" class="glyphicon glyphicon-chevron-down"></span></th>
				<th v-on:click="sort('name',1)">Name &nbsp;<span v-show="showAsc[1]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[1]" class="glyphicon glyphicon-chevron-down"></span></th>
	            <th v-on:click="sort('address',2)">Address &nbsp;<span v-show="showAsc[2]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[2]" class="glyphicon glyphicon-chevron-down"></span></th>
				<th v-on:click="sort('city',3)">City &nbsp;<span v-show="showAsc[3]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[3]" class="glyphicon glyphicon-chevron-down"></span></th>
	            <th v-on:click="sort('phone',4)">Phone &nbsp;<span v-show="showAsc[4]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[4]" class="glyphicon glyphicon-chevron-down"></span></th>
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
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="text" class="form-control" placeholder="Search" v-model="searchVar.address" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="text" class="form-control" placeholder="Search" v-model="searchVar.city" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="text" class="form-control" placeholder="Search" v-model="searchVar.phone" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				</td>
			</tr>
			</template>
	          <tr v-for="location, index in locations">
	            <template v-if = "!editInput[index]">
								<td>{{ location.code }}</td>
								<td>{{ location.name }}</td>
								<td>{{ location.address }}</td>
								<td>{{ location.city }}</td>
								<td>{{ location.phone }}</td>
	             <td>
	                <a
	                   class="btn btn-xs btn-warning"
	                   v-on:click="editEntry(index)">
	                   Edit
	                </a>
					<a
					   class="btn btn-xs btn-danger"
					   v-on:click="deleteEntry(location.id, index)">
						Delete
					</a>
				</td>
	            </template>
	            <template v-else>
					<td><input type="text" v-model="location.code" class="form-control"><p style="color:red;" v-if="!$v.locations.$each[index].code.required">The code field is required!</p></td>
					<td><input type="text" v-model="location.name" class="form-control"><p style="color:red;" v-if="!$v.locations.$each[index].name.required">The name field is required!</p></td>
					<td><input type="text" v-model="location.address" class="form-control"><p style="color:red;" v-if="!$v.locations.$each[index].address.required">The address field is required!</p></td>
					<td><input type="text" v-model="location.city" class="form-control"><p style="color:red;" v-if="!$v.locations.$each[index].city.required">The city field is required!</p></td>
					<td><input type="text" v-model="location.phone" class="form-control"><p style="color:red;" v-if="!$v.locations.$each[index].phone.required">The phone field is required!</p></td>
					<button
	                 	class="btn btn-xs btn-success"
	                 	v-on:click="updateEntry(location.id,index)" type="button" :disabled="$v.locations.$invalid">
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
                locations:[],
                offset: 4,
                editInput: [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],
                searchVar : {
					code : null,
					name : null,
					address : null,
					city : null,
					phone : null,
				},
				showAsc : [false,false,false,false,false,false],
				showDesc : [false,false,false,false,false,false],
				isAsc : 'desc',
                orderVar : null,
                showSearch : false,
            }
        },
		validations: {
			locations: {
				$each: {
					code: {
						required
					},
					name: {
						required
					},
					address: {
						required
					},
					city: {
						required
					},
					phone: {
						required
					},
				}
			}
		},
        mounted() {
            var app = this;
            axios.get('multicom-pos/public/api/v1/locations?page='+ app.pagination.current_page)
                .then(function (resp) {
                    app.pagination = resp.data;
                    app.locations = resp.data.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load locations");
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
				var ajaxCall = 'multicom-pos/public/api/v1/locations?';
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
				if ((x.address != null)&&(x.address != "")){
					ajaxCall += '&searchVarAddress=';
					ajaxCall += x.address;
				}
				if ((x.city != null)&&(x.city != "")){
					ajaxCall += '&searchVarCity=';
					ajaxCall += x.city;
				}
				if ((x.phone != null)&&(x.phone != "")){
					ajaxCall += '&searchVarPhone=';
					ajaxCall += x.phone;
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
						app.locations = resp.data.data;
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
              var newSupplier = app.locations[index];
              axios.patch('multicom-pos/public/api/v1/locations/' + id, newSupplier)
                  .then(function (resp) {
                  	alert(app.locations[index].code + " " + app.locations[index].name + " was updated");
                  })
                  .catch(function (resp) {
                      console.log(resp);
                      alert("Could not edit your location");
                  });
                app.editInput.splice(index, 1, false);
                location.reload();
            },
            deleteEntry(id, index) {
                if (confirm("Do you really want to delete it?")) {
                    var app = this;
                    axios.delete('multicom-pos/public/api/v1/locations/' + id)
                        .then(function (resp) {
                        	alert(app.locations[index].code + " " + app.locations[index].name + " was deleted");
                            app.locations.splice(index, 1);
                        })
                        .catch(function (resp) {
                            alert("Could not delete location");
                        });
                    location.reload();
                }
            },
        }
    }
</script>
