<template>
    <div>
	    &nbsp;<br>
	    <button class="btn btn-primary" v-on:click="show_search()">
		  <span class="glyphicon glyphicon-search"></span>
		</button>
		<div class = "row">
			<div class = "col-md-2">
				<input type="date" class="form-control" placeholder="Start Date" v-model="searchVar.start_date">
			</div>
			<div class = "col-md-1 text-center">
				-
			</div>
			<div class = "col-md-2">
				<input type="date" class="form-control" placeholder="End Date" v-model="searchVar.end_date">
			</div>
			<div class = "col-md-2" v-if = "new Date(searchVar.start_date) < new Date(searchVar.end_date)">
				<button class="btn btn-info" v-on:click="refresh()">
					Refresh
				</button>
			</div>
		</div>
	      <table class="table table-bordered table-striped table-dark">
	          <thead>
	          <tr>
	          	<th v-on:click="sort('item_code',0)">Item Code &nbsp;<span v-show="showAsc[0]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[0]" class="glyphicon glyphicon-chevron-down"></span></th>
	          	<th v-on:click="sort('item_name',1)">Item Name &nbsp;<span v-show="showAsc[1]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[1]" class="glyphicon glyphicon-chevron-down"></span></th>
	          	<th v-on:click="sort('department_code',2)">Department Code &nbsp;<span v-show="showAsc[2]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[2]" class="glyphicon glyphicon-chevron-down"></span></th>
	          	<th v-on:click="sort('department_name',3)">Department Name &nbsp;<span v-show="showAsc[3]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[3]" class="glyphicon glyphicon-chevron-down"></span></th>
	          	<th v-on:click="sort('supplier_code',4)">Supplier Code &nbsp;<span v-show="showAsc[4]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[4]" class="glyphicon glyphicon-chevron-down"></span></th>
	          	<th v-on:click="sort('supplier_name',5)">Supplier Name &nbsp;<span v-show="showAsc[5]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[5]" class="glyphicon glyphicon-chevron-down"></span></th>
				<th v-on:click="sort('qty_purchased',6)">Qty Purchased &nbsp;<span v-show="showAsc[6]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[6]" class="glyphicon glyphicon-chevron-down"></span></th>
	            <th v-on:click="sort('total_purchased',7)">Total Purchased &nbsp;<span v-show="showAsc[7]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[7]" class="glyphicon glyphicon-chevron-down"></span></th>
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
						<input type="text" class="form-control" placeholder="Search" v-model="searchVar.item_name" v-on:keyup="search()">
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
						<input type="text" class="form-control" placeholder="Search" v-model="searchVar.department_name" v-on:keyup="search()">
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
						<input type="text" class="form-control" placeholder="Search" v-model="searchVar.supplier_name" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				<!--
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="number" class="form-control" placeholder="Search" v-model="searchVar.qty_sold" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				-->
				</td>
				<td>
					<!--
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="number" class="form-control" placeholder="Search" v-model="searchVar.total_sold" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				  -->
				</td>
				<td>
					<!--
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="number" class="form-control" placeholder="Search" v-model="searchVar.profit" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				  -->
				</td>
			</tr>
			</template>
	          	<tr v-for="report, index in reports">
	          		<td>{{ report.item_code }}</td>
					<td>{{ report.item_name }}</td>
					<td>{{ report.department_code }}</td>
					<td>{{ report.department_name }}</td>
					<td>{{ report.supplier_code }}</td>
					<td>{{ report.supplier_name }}</td>
					
					<td>{{ report.qty_purchased }}</td>
					<td>{{ report.total_purchased }}</td>
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
                reports:[],
                offset: 4,
                searchVar : {
                	start_date : '2011-01-01',
                	end_date : '2020-01-01',
                	item_code : null,
					item_name : null,
					department_code : null,
					department_name : null,
					supplier_code : null,
					supplier_name : null,
					qty_purchased : null,
					total_purchased : null,
				},
				showAsc : [false,false,false,false,false,false,false,false,false,false],
				showDesc : [false,false,false,false,false,false,false,false,false,false],
				isAsc : 'desc',
                orderVar : null,
                showSearch : false,
            }
        },
        mounted() {
            var app = this;
            axios.get('multicom-pos/public/api/v1/items/func/reportpurchase?page='+ app.pagination.current_page)
                .then(function (resp) {
                    app.pagination = resp.data;
                    app.reports = resp.data.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load reports");
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
					app.showAsc = [false,false,false,false,false,false,false,false,false,false];
					app.showDesc = [false,false,false,false,false,false,false,false,false,false];
					app.showDesc.splice(index, 1, true);
				} else {
					app.isAsc = 'asc';
					app.showAsc = [false,false,false,false,false,false,false,false,false,false];
					app.showDesc = [false,false,false,false,false,false,false,false,false,false];
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
				var ajaxCall = 'multicom-pos/public/api/v1/items/func/reportpurchase?';
				var x = app.searchVar;
				var y = app.orderVar;
				var page = app.pagination.current_page;
				
				ajaxCall += 'page=';
				ajaxCall += page;
				if ((x.start_date != null)&&(x.start_date != "")&&(x.end_date != null)&&(x.end_date != "")){
					ajaxCall += '&searchVarStartDate=';
					ajaxCall += x.start_date;
					ajaxCall += '&searchVarEndDate=';
					ajaxCall += x.end_date;
				}
				if ((x.item_name != null)&&(x.item_name != "")){
					ajaxCall += '&searchVarItemName=';
					ajaxCall += x.item_name;
				}
				if ((x.item_code != null)&&(x.item_code != "")){
					ajaxCall += '&searchVarItemCode=';
					ajaxCall += x.item_code;
				}
				if ((x.department_name != null)&&(x.department_name != "")){
					ajaxCall += '&searchVarDepartmentName=';
					ajaxCall += x.department_name;
				}
				if ((x.department_code != null)&&(x.department_code != "")){
					ajaxCall += '&searchVarDepartmentCode=';
					ajaxCall += x.department_code;
				}
				if ((x.supplier_name != null)&&(x.supplier_name != "")){
					ajaxCall += '&searchVarSupplierName=';
					ajaxCall += x.supplier_name;
				}
				if ((x.supplier_code != null)&&(x.supplier_code != "")){
					ajaxCall += '&searchVarSupplierCode=';
					ajaxCall += x.supplier_code;
				}
				/*
				if ((x.qty_sold != null)&&(x.qty_sold != "")){
					ajaxCall += '&searchVarQtySold=';
					ajaxCall += x.qty_sold;
				}
				if ((x.total_sold != null)&&(x.total_sold != "")){
					ajaxCall += '&searchVarTotalSold=';
					ajaxCall += x.total_sold;
				}
				if ((x.profit != null)&&(x.profit != "")){
					ajaxCall += '&searchVarProfit=';
					ajaxCall += x.profit;
				}
				*/
				if (y != null){
					ajaxCall += '&orderVar=';
					ajaxCall += y;
					ajaxCall += '&isAsc=';
					ajaxCall += app.isAsc;
				}
				axios.get(ajaxCall)
					.then(function (resp) {
						app.pagination = resp.data;
						app.reports = resp.data.data;
						console.log(ajaxCall);
					})
					.catch(function (resp) {
						console.log(resp);
						alert("No result found");
					});
			},
        }
    }
</script>
