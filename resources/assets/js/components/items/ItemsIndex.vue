<template>
    <div>
	    &nbsp;<br>
	    <button class="btn btn-info" v-on:click="recount()">
			Recount
		</button>
	      <table class="table table-bordered table-striped table-dark">
	          <thead>
	          <tr>
	          	<th v-on:click="sort('item_code',0)">Item Code &nbsp;<span v-show="showAsc[0]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[0]" class="glyphicon glyphicon-chevron-down"></span></th>
				<th v-on:click="sort('suppliers.code',1)">Supplier Code &nbsp;<span v-show="showAsc[1]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[1]" class="glyphicon glyphicon-chevron-down"></span></th>
	            <th v-on:click="sort('departments.code',2)">Department Code &nbsp;<span v-show="showAsc[2]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[2]" class="glyphicon glyphicon-chevron-down"></span></th>
				<th v-on:click="sort('item_name',3)">Name &nbsp;<span v-show="showAsc[3]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[3]" class="glyphicon glyphicon-chevron-down"></span></th>
	            <th v-on:click="sort('unit',4)">Unit &nbsp;<span v-show="showAsc[4]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[4]" class="glyphicon glyphicon-chevron-down"></span></th>
	            <th v-on:click="sort('buy_price',5)">Buy Price &nbsp;<span v-show="showAsc[5]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[5]" class="glyphicon glyphicon-chevron-down"></span></th>
	            <th v-on:click="sort('sell_price',6)">Sell Price &nbsp;<span v-show="showAsc[6]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[6]" class="glyphicon glyphicon-chevron-down"></span></th>
	            <th v-on:click="sort('first_stock',7)">First Stock &nbsp;<span v-show="showAsc[7]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[7]" class="glyphicon glyphicon-chevron-down"></span></th>
	            <th v-on:click="sort('in_stock',8)">In Stock &nbsp;<span v-show="showAsc[8]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[8]" class="glyphicon glyphicon-chevron-down"></span></th>
	            <th v-on:click="sort('out_stock',9)">Out Stock &nbsp;<span v-show="showAsc[9]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[9]" class="glyphicon glyphicon-chevron-down"></span></th>
	            <th v-on:click="sort('now_stock',10)">Now Stock &nbsp;<span v-show="showAsc[10]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[10]" class="glyphicon glyphicon-chevron-down"></span></th>
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
						<input type="text" class="form-control" placeholder="Search" v-model="searchVar.item_code" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="text" class="form-control" placeholder="Search" v-model="searchVar.supplier_code" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="text" class="form-control" placeholder="Search" v-model="searchVar.department_code" v-on:keyup="search()">
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
						<input type="text" class="form-control" placeholder="Search" v-model="searchVar.unit" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="number" class="form-control" placeholder="Search" v-model="searchVar.buy_price" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="number" class="form-control" placeholder="Search" v-model="searchVar.sell_price" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="number" class="form-control" placeholder="Search" v-model="searchVar.first_stock" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="number" class="form-control" placeholder="Search" v-model="searchVar.in_stock" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="number" class="form-control" placeholder="Search" v-model="searchVar.out_stock" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="number" class="form-control" placeholder="Search" v-model="searchVar.now_stock" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				</td>
			</tr>
			</template>
	        <template v-for="item, index in items">
	          	<tr>
		            <template v-if = "!editInput[index]">
						<td v-on:click="show_item(item.id, index)"><strong>{{ item.item_code }}</strong></td>
						<td v-on:click="show_item(item.id, index)"><strong>{{ item.supplier.code }}</strong></td>
						<td v-on:click="show_item(item.id, index)"><strong>{{ item.department.code }}</strong></td>
						<td v-on:click="show_item(item.id, index)"><strong>{{ item.item_name }}</strong></td>
						<td v-on:click="show_item(item.id, index)"><strong>{{ item.unit }}</strong></td>
						<td v-on:click="show_item(item.id, index)"><strong>{{ item.itemprice.buy_price }}</strong></td>
						<td v-on:click="show_item(item.id, index)"><strong>{{ item.itemprice.sell_price }}</strong></td>
						<td v-on:click="show_item(item.id, index)"><strong>{{ item.first_stock }}</strong></td>
						<td v-on:click="show_item(item.id, index)"><strong>{{ item.in_stock }}</strong></td>
						<td v-on:click="show_item(item.id, index)"><strong>{{ item.out_stock }}</strong></td>
						<td v-on:click="show_item(item.id, index)"><strong>{{ item.now_stock }}</strong></td>
		            <td>
		                <a
		                   class="btn btn-xs btn-warning"
		                   v-on:click="editEntry(index)">
		                   Edit
		                </a>
						<a
						   class="btn btn-xs btn-danger"
						   v-on:click="deleteEntry(item.id, index)">
							Delete
						</a>
					</td>
		            </template>
		            <template v-else>
						<td><input type="text" v-model="item.item_code" class="form-control"><p style="color:red;" v-if="!$v.items.$each[index].item_code.required">The item_code field is required!</p></td>
						<td><input type="text" v-model="item.supplier.code" v-on:keyup="search_supplierCode(index)" list= "supplierList" class="form-control"><p style="color:red;" v-if="!$v.items.$each[index].supplier.code.required">The supplier.code field is required!</p></td>
							<datalist id="supplierList">
							    <template v-for="sup in suppliersCode">
							    	<option :value = "sup.code">{{sup.id}} - {{sup.code}} </option>
							    </template>
							</datalist>
						<td><input type="text" v-on:keyup="search_departmentCode(index)" list= "departmentList" v-model="item.department.code" class="form-control"><p style="color:red;" v-if="!$v.items.$each[index].department.code.required">The department.code field is required!</p></td>
							<datalist id="departmentList">
							    <template v-for="dep in departmentsCode">
							    	<option :value = "dep.code">{{dep.id}} - {{dep.code}} </option>
							    </template>
							</datalist>
						<td><input type="text" v-model="item.item_name" class="form-control"><p style="color:red;" v-if="!$v.items.$each[index].item_name.required">The name field is required!</p></td>
						<td><input type="text" v-model="item.unit" class="form-control"><p style="color:red;" v-if="!$v.items.$each[index].unit.required">The unit field is required!</p></td>
						<td><input type="number" v-model="item.itemprice.buy_price" class="form-control"><p style="color:red;" v-if="!$v.items.$each[index].itemprice.buy_price.required">The buy_price field is required!</p><p v-if="!$v.items.$each[index].itemprice.buy_price.numeric">The buy_price field must be numeric!</p></td>
						<td><input type="number" v-model="item.itemprice.sell_price" class="form-control"><p style="color:red;" v-if="!$v.items.$each[index].itemprice.sell_price.required">The sell_price field is required!</p><p v-if="!$v.items.$each[index].itemprice.sell_price.numeric">The sell_price field must be numeric!</p></td>
						<td>{{ item.first_stock }}</td>
						<td>{{ item.in_stock }}</td>
						<td>{{ item.out_stock }}</td>
						<td>{{ item.now_stock }}</td>
		              <button
		                 class="btn btn-xs btn-success"
		                 v-on:click="updateEntry(item.id,index)" type="button" :disabled="$v.items.$invalid">
		                 Update
		              </button>
		              <button
		                 class="btn btn-xs btn-default"
		                 v-on:click="cancelEntry(index)">
		                 Cancel
		              </button>
		            </template>
		        </tr>
		        <template v-if = "showItem[index]">
		        	<tr>
		        		<td>Location</td>
		        		<td>First Stock</td>
		        		<td>In Stock</td>
		        		<td>Out Stock</td>
		        		<td>Now Stock</td>
		        	</tr>
		        	<tr v-for="stock, index in stocks">
		        		<td> {{ stock.location_id }}</td>
		        		<td> {{ stock.first_stock }}</td>
		        		<td> {{ stock.in_stock }}</td>
		        		<td> {{ stock.out_stock }}</td>
		        		<td> {{ stock.now_stock }}</td>
		        	</tr>
			        <tr>
			        	<td rowspan ="6"></td>
			        	<td> Supplier Code </td>
			        	<td> {{ supplier.code }} </td>
			        	<td> Department Code </td>
			        	<td> {{ department.code }} </td>
			        </tr>
			        <tr>
			        	<td> Supplier Name </td>
			        	<td> {{ supplier.name }} </td>
			        	<td> Department Name </td>
			        	<td> {{ department.name }} </td>
			        </tr>
			        <tr>
			        	<td> Supplier Address </td>
			        	<td> {{ supplier.address }} </td>
			        </tr>
			        <tr>
			        	<td> Supplier City </td>
			        	<td> {{ supplier.city }} </td>
			        </tr>
			        <tr>
			        	<td> Supplier Phone </td>
			        	<td> {{ supplier.phone }} </td>
			        </tr>
			        <tr>
			        	<td> Supplier Deadline </td>
			        	<td> {{ supplier.deadline }} </td>
			        </tr>

			    </template>
	        </template>
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
                items:[],
                suppliersCode:[],
                departmentsCode:[],
                supplier:[],
                department:[],
                stocks:[],
                showItem: [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],
                offset: 4,
                editInput: [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],
                searchVar : {
					item_code : null,
					supplier_code : null,
					department_code : null,
					name : null,
					unit : null,
					buy_price : null,
					sell_price : null,
					first_stock : null,
					now_stock : null,
				},
				showAsc : [false,false,false,false,false,false,false,false,false,false,false,false],
				showDesc : [false,false,false,false,false,false,false,false,false,false,false,false],
				isAsc : 'desc',
                orderVar : null,
                showSearch : false,
            }
        },
		validations: {
			items: {
				$each: {
					item_code: {
						required
					},
					supplier: {
						code : {
							required
						}
					},
					department: {
						code : {	
							required
						}
					},
					item_name: {
						required
					},
					unit: {
						required
					},
					itemprice: {
						buy_price: {
							required,
							numeric
						},
						sell_price: {
							required,
							numeric
						},
					},
				}
			}
		},
        mounted() {
            var app = this;
            axios.get('multicom-pos/public/api/v1/items?page='+ app.pagination.current_page)
                .then(function (resp) {
                    app.pagination = resp.data;
                    app.items = resp.data.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load items");
                });
        },
        methods: {
        	recount(){
        		axios.get('multicom-pos/public/api/v1/items/func/recount')
        		.then(function (resp) {
	                    location.reload();
	                })
	                .catch(function (resp) {
	                    console.log(resp);
	                    alert("Failed to recount");
	                });
        	},
        	search_supplierCode(index){
        		console.log("searchsupplier");
        		var app = this;
        		var codesearch = app.items[index].supplier.code;
        		if((codesearch != null)&&(codesearch != "")){
	        		axios.get('multicom-pos/public/api/v1/suppliers/code/' + codesearch)
	                .then(function (resp) {
	                    app.suppliersCode = resp.data;
	                    console.log("a");
	                    console.log(codesearch);
	                })
	                .catch(function (resp) {
	                    console.log(resp);
	                    alert("Could not load supplier codes");
	                });
	                app.updateId(index);
	            }
        	},
        	search_departmentCode(index){
        		console.log("searchdepartment");
        		var app = this;
        		var codesearch = app.items[index].department.code;
        		if((codesearch != null)&&(codesearch != "")){
	        		axios.get('multicom-pos/public/api/v1/departments/code/' + codesearch)
	                .then(function (resp) {
	                    app.departmentsCode = resp.data;
	                    console.log("a");
	                    console.log(codesearch);
	                })
	                .catch(function (resp) {
	                    console.log(resp);
	                    alert("Could not load department codes");
	                });
	                app.updateId(index);
	            }
        	},
        	updateId(index){
        		var app = this;
              	var codesupplier = app.items[index].supplier.code;
              	axios.get('multicom-pos/public/api/v1/suppliers/code/' + codesupplier)
	                .then(function (resp) {
	                    app.items[index].supplier_id = resp.data[0].id;
	                    console.log(resp.data[0].id);
	                    console.log(app.items[index].supplier_id);
	                })
	                .catch(function (resp) {
	                	app.items[index].supplier_id = null;
	                });
	            var codedepartment = app.items[index].department.code;
              	axios.get('multicom-pos/public/api/v1/departments/code/' + codedepartment)
	                .then(function (resp) {
	                    app.items[index].department_id = resp.data[0].id;
	                    console.log(resp.data[0].id);
	                    console.log(app.items[index].department_id);
	                })
	                .catch(function (resp) {
	                	app.items[index].department_id = null;
	                });
        	},
        	show_item(id, index){
        		var app = this;
	            app.show_department(id);
	            app.show_supplier(id);
	            app.show_stocks(id);
	            if (app.showItem[index] == true){
	            	app.showItem = [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false];
	            } else {
	            	app.showItem = [false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false];
	            	app.showItem[index] = true;
	            }
        	},
        	show_department(id){
        		var app = this;
        		axios.get('multicom-pos/public/api/v1/items/'+ id +'/department')
	                .then(function (resp) {
	                    app.department = resp.data;
	                    console.log(resp.data);
	                })
	                .catch(function (resp) {
	                    console.log(resp);
	                    alert("Could not load item's department");
	                });
        	},
        	show_supplier(id, index){
        		var app = this;
        		axios.get('multicom-pos/public/api/v1/items/'+ id +'/supplier')
	                .then(function (resp) {
	                    app.supplier = resp.data;
	                    console.log(resp.data);
	                })
	                .catch(function (resp) {
	                    console.log(resp);
	                    alert("Could not load item's supplier");
	                });
        	},
        	show_stocks(id){
        		var app = this;
        		axios.get('multicom-pos/public/api/v1/items/'+ id +'/stocks')
	                .then(function (resp) {
	                    app.stocks = resp.data;
	                    console.log(resp.data);
	                })
	                .catch(function (resp) {
	                    console.log(resp);
	                    alert("Could not load item's stock");
	                });
        	},
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
					app.showAsc = [false,false,false,false,false,false,false,false,false,false,false,false];
					app.showDesc = [false,false,false,false,false,false,false,false,false,false,false,false];
					app.showDesc.splice(index, 1, true);
				} else {
					app.isAsc = 'asc';
					app.showAsc = [false,false,false,false,false,false,false,false,false,false,false,false];
					app.showDesc = [false,false,false,false,false,false,false,false,false,false,false,false];
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
				var ajaxCall = 'multicom-pos/public/api/v1/items?';
				var x = app.searchVar;
				var y = app.orderVar;
				var page = app.pagination.current_page;
				
				ajaxCall += 'page=';
				ajaxCall += page;
				if ((x.item_code != null)&&(x.item_code != "")){
					ajaxCall += '&searchVarItemCode=';
					ajaxCall += x.item_code;
				}
				if ((x.supplier_code != null)&&(x.supplier_code != "")){
					ajaxCall += '&searchVarSupplierCode=';
					ajaxCall += x.supplier_code;
				}
				if ((x.department_code != null)&&(x.department_code != "")){
					ajaxCall += '&searchVarDepartmentCode=';
					ajaxCall += x.department_code;
				}
				if ((x.name != null)&&(x.name != "")){
					ajaxCall += '&searchVarName=';
					ajaxCall += x.name;
				}
				if ((x.unit != null)&&(x.unit != "")){
					ajaxCall += '&searchVarUnit=';
					ajaxCall += x.unit;
				}
				if ((x.buy_price != null)&&(x.buy_price != "")){
					ajaxCall += '&searchVarBuyPrice=';
					ajaxCall += x.buy_price;
				}
				if ((x.sell_price != null)&&(x.sell_price != "")){
					ajaxCall += '&searchVarSellPrice=';
					ajaxCall += x.sell_price;
				}
				if ((x.first_stock != null)&&(x.first_stock != "")){
					ajaxCall += '&searchVarFirstStock=';
					ajaxCall += x.first_stock;
				}
				if ((x.in_stock != null)&&(x.in_stock != "")){
					ajaxCall += '&searchVarInStock=';
					ajaxCall += x.in_stock;
				}
				if ((x.out_stock != null)&&(x.out_stock != "")){
					ajaxCall += '&searchVarOutStock=';
					ajaxCall += x.out_stock;
				}
				if ((x.now_stock != null)&&(x.now_stock != "")){
					ajaxCall += '&searchVarNowStock=';
					ajaxCall += x.now_stock;
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
						app.items = resp.data.data;
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
              var newItem = {
                    supplier_id : app.items[index].supplier_id,
                    department_id : app.items[index].department_id,
                    item_code : app.items[index].item_code,
                    item_name : app.items[index].item_name,
                    unit : app.items[index].unit,
                    buy_price : app.items[index].itemprice.buy_price,
                    sell_price : app.items[index].itemprice.sell_price,
                };
              axios.patch('multicom-pos/public/api/v1/items/' + id, newItem)
                  .then(function (resp) {
                  	alert(app.items[index].item_code + " " + app.items[index].item_name + " was updated");
                  })
                  .catch(function (resp) {
                      console.log(resp);
                      alert("Could not edit your item");
                  });
                app.editInput.splice(index, 1, false);
                location.reload();
            },
            deleteEntry(id, index) {
                if (confirm("Do you really want to delete it?")) {
                    var app = this;
                    axios.delete('multicom-pos/public/api/v1/items/' + id)
                        .then(function (resp) {
                        	alert(app.items[index].item_code + " " + app.items[index].item_name + " was deleted");
                            app.items.splice(index, 1);
                        })
                        .catch(function (resp) {
                            alert("Could not delete item");
                        });
                    location.reload();
                }
            },
        }
    }
</script>
