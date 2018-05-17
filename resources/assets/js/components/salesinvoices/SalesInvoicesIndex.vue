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
	          	<th v-on:click="sort('sales_invoices.created_at',0)">Date &nbsp;<span v-show="showAsc[0]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[0]" class="glyphicon glyphicon-chevron-down"></span></th>
	          	<th v-on:click="sort('users.name',1)">User's Name &nbsp;<span v-show="showAsc[1]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[1]" class="glyphicon glyphicon-chevron-down"></span></th>
				<th v-on:click="sort('sale_invoice_no',2)">Sale Invoice No &nbsp;<span v-show="showAsc[2]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[2]" class="glyphicon glyphicon-chevron-down"></span></th>
	            <th v-on:click="sort('total',3)">Total &nbsp;<span v-show="showAsc[3]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[3]" class="glyphicon glyphicon-chevron-down"></span></th>
				<th v-on:click="sort('paid',4)">Paid  &nbsp;<span v-show="showAsc[4]" class="glyphicon glyphicon-chevron-up"></span> <span v-show="showDesc[4]" class="glyphicon glyphicon-chevron-down"></span></th>
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
						<input type="number" class="form-control" placeholder="Search" v-model="searchVar.user_name" v-on:keyup="search()">
					  </div>
					</div>
				  </div>
				</td>
				<td>
				  <div class="row">
					<div class="col-xs-6 col-md-12">
					  <div class="input-group">
						<input type="text" class="form-control" placeholder="Search" v-model="searchVar.sale_invoice_no" v-on:keyup="search()">
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
	          	<tr v-for="saleinvoice, index in salesinvoices">
					<td>{{ saleinvoice.created_at }}</td>
					<td>{{ saleinvoice.user.name }}</td>
					<td>{{ saleinvoice.sale_invoice_no }}</td>
					<td>{{ saleinvoice.total }}</td>
					<td>{{ saleinvoice.paid }}</td>
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
                salesinvoices:[],
                offset: 4,
                searchVar : {
                	start_date : '2011-01-01',
                	end_date : '2020-01-01',
                	user_name : null,
					sale_invoice_no : null,
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
            axios.get('multicom-pos/public/api/v1/salesinvoices?page='+ app.pagination.current_page)
                .then(function (resp) {
                    app.pagination = resp.data;
                    app.salesinvoices = resp.data.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load salesinvoices");
                });
        },
        methods: {
        	recount(){
        		axios.get('multicom-pos/public/api/v1/salesinvoices/func/recount')
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
				var ajaxCall = 'multicom-pos/public/api/v1/salesinvoices?';
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
				if ((x.sale_invoice_no != null)&&(x.sale_invoice_no != "")){
					ajaxCall += '&searchVarSaleInvoiceNo=';
					ajaxCall += x.sale_invoice_no;
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
						app.salesinvoices = resp.data.data;
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
