<template>
    <div>
	    &nbsp;<br>
	    <button class="btn btn-primary" v-on:click="show_search()">
		  <span class="glyphicon glyphicon-search"></span>
		</button>
		<button class="btn btn-info" v-on:click="recount()">
			Recount
		</button>
		&nbsp;<br>
		&nbsp;<br>
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
	          	<th v-on:click="sort('purchases_invoices.created_at',0)">Date &nbsp;<span v-show="showAsc[0]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[0]" class="glyphicon glyphicon-chevron-down"></span></th>
	          	<th v-on:click="sort('users.name',1)">User's Name &nbsp;<span v-show="showAsc[1]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[1]" class="glyphicon glyphicon-chevron-down"></span></th>
	          	<th v-on:click="sort('suppliers.name',2)">Supplier's Name &nbsp;<span v-show="showAsc[2]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[2]" class="glyphicon glyphicon-chevron-down"></span></th>
				<th v-on:click="sort('purchase_invoice_no',3)">Purchase Invoice No &nbsp;<span v-show="showAsc[3]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[3]" class="glyphicon glyphicon-chevron-down"></span></th>
	            <th v-on:click="sort('total',4)">Total &nbsp;<span v-show="showAsc[4]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[4]" class="glyphicon glyphicon-chevron-down"></span></th>
				<th v-on:click="sort('paid',5)">Paid  &nbsp;<span v-show="showAsc[5]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[5]" class="glyphicon glyphicon-chevron-down"></span></th>
	          </tr>
	          </thead>
	          <tbody>
	          <template v-if = "showSearch">
			  <tr>
			  	<td>
			  	</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="text" class="form-control" placeholder="Search" v-model="searchVar.username" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="text" class="form-control" placeholder="Search" v-model="searchVar.suppliername" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="text" class="form-control" placeholder="Search" v-model="searchVar.purchase_invoice_no" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="number" class="form-control" placeholder="Search" v-model="searchVar.total" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="number" class="form-control" placeholder="Search" v-model="searchVar.paid" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
			</tr>
			</template>
	          	<tr v-for="purchaseinvoice, index in purchasesinvoices">
					<td>{{ purchaseinvoice.created_at }}</td>
					<td>{{ purchaseinvoice.user.name }}</td>
					<td>{{ purchaseinvoice.supplier.name }}</td>
					<td>{{ purchaseinvoice.purchase_invoice_no }}</td>
					<td>{{ purchaseinvoice.total }}</td>
					<td>{{ purchaseinvoice.paid }}</td>
					<td>
						<a
							 class="btn btn-xs btn-warning"
							 v-on:click="partialPay(purchaseinvoice.id,index)">
							 Partial
						</a>
						<a
							 class="btn btn-xs btn-success"
							 v-on:click="fullPay(purchaseinvoice.id,index)">
							Full
						</a>
					</td>
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
                purchasesinvoices:[],
                offset: 4,
                searchVar : {
                	start_date : '2011-01-01',
                	end_date : '2020-01-01',
                	username : null,
                	suppliername : null,
					purchase_invoice_no : null,
					total : null,
					paid : null,
				},
				showAsc : [false,false,false,false,false,false],
				showDesc : [false,false,false,false,false,false],
				isAsc : 'desc',
                orderVar : null,
                showSearch : false,
            }
        },
        mounted() {
            var app = this;
            axios.get('multicom-pos/public/api/v1/purchasesinvoices?page='+ app.pagination.current_page)
                .then(function (resp) {
                    app.pagination = resp.data;
                    app.purchasesinvoices = resp.data.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load purchasesinvoices");
                });
        },
        methods: {
        	fullPay(id,index){
				var app = this;
				app.purchasesinvoices[index].paid = app.purchasesinvoices[index].total;
				var newPaid = app.purchasesinvoices[index];
				axios.patch('multicom-pos/public/api/v1/purchasesinvoices/' + id, newPaid)
					.then(function (resp) {
						console.log(1);
							alert(app.purchasesinvoices[index].supplier.name + " " + app.purchasesinvoices[index].total + " was updated");
					})
					.catch(function (resp) {
							console.log(resp);
							alert("Could not edit your debt");
					});
				location.reload();
			},
        	recount(){
        		axios.get('multicom-pos/public/api/v1/purchasesinvoices/func/recount')
        		.then(function (resp) {
	                    location.reload();
	                })
	                .catch(function (resp) {
	                    console.log(resp);
	                    alert("Failed to recount");
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
				var ajaxCall = 'multicom-pos/public/api/v1/purchasesinvoices?';
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
				if ((x.username != null)&&(x.username != "")){
					ajaxCall += '&searchVarUserName=';
					ajaxCall += x.username;
				}
				if ((x.suppliername != null)&&(x.suppliername != "")){
					ajaxCall += '&searchVarSupplierName=';
					ajaxCall += x.suppliername;
				}
				if ((x.purchase_invoice_no != null)&&(x.purchase_invoice_no != "")){
					ajaxCall += '&searchVarPurchaseInvoiceNo=';
					ajaxCall += x.purchase_invoice_no;
				}
				if ((x.total != null)&&(x.total != "")){
					ajaxCall += '&searchVarTotal=';
					ajaxCall += x.total;
				}
				if ((x.paid != null)&&(x.paid != "")){
					ajaxCall += '&searchVarPaid=';
					ajaxCall += x.paid;
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
						app.purchasesinvoices = resp.data.data;
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
