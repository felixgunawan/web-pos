<template>
	<div>
		&nbsp;<br>
		<button class="btn btn-info" v-on:click="recount()">
			Recount
		</button>
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
		&nbsp;<br>
		<div class = "row">
			<div class = "col-md-6">
				<strong>Summary</strong>
				<table class="table table-bordered table-striped table-dark">
					<thead>
						<tr>
							<th>Name</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Profit</td>
							<td>{{ profit }}</td>
						</tr>
						<tr>
							<td>Profit Margin</td>
							<td>{{ (profit/income)*100 }} %</td>
						</tr>
						<tr>
							<td>Nett Income</td>
							<td>{{ income - outcome }}</td>
						</tr>
						<tr>
							<td>Income</td>
							<td>{{ income }}</td>
						</tr>
						<tr>
							<td>Outcome</td>
							<td>{{ outcome }}</td>
						</tr>
						<tr>
							<td>Total Debt</td>
							<td>{{ total_debt }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class = "col-md-6">
				<strong>Upcoming Debts</strong>
				<table class="table table-bordered table-striped table-dark">
					<thead>
						<tr>
							<th>Supplier's Name</th>
							<th>Deadline</th>
							<th>Total</th>
							<th>Paid</th>
							<th>Debt</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="debt,idex in soon_debt">
			          		<td>{{ debt.name }}</td>
							<td>{{ debt.payment_date }}</td>
							<td>{{ debt.total }}</td>
							<td>{{ debt.paid }}</td>
							<td>{{ debt.total-debt.paid }}</td>
			            </tr>
					</tbody>
				</table>
			</div>
		</div>
		<appChart v-if="show" :startDate="searchVar.start_date" :endDate="searchVar.end_date"></appChart>
	</div>
</template>

<script>
	import chart from './InOutChart.vue'
	export default {
		data: function () {
			return {
				profit : 0,
				income : 0, 
				outcome : 0,
				total_debt : 0,
				searchVar : {
					start_date : '2011-01-01',
					end_date : '2020-01-01',
				},
				soon_debt : null,
				show : true,
			}
		},
		components: {
  			appChart: chart
 		},
		mounted() {
			var app = this;
			app.get_profit();
			app.get_income();
			app.get_outcome();
			app.get_totalDebt();
			app.get_soonDebt();
		},
		methods: {
			recount(){
        		axios.get('multicom-pos/public/api/v1/items/func/recount')
        		.then(function (resp) {
	                })
	                .catch(function (resp) {
	                    console.log(resp);
	                    alert("Failed to recount");
	                });
	            axios.get('multicom-pos/public/api/v1/salesinvoices/func/recount')
        		.then(function (resp) {
	                })
	                .catch(function (resp) {
	                    console.log(resp);
	                    alert("Failed to recount");
	                });
	            axios.get('multicom-pos/public/api/v1/purchasesinvoices/func/recount')
        		.then(function (resp) {
	                })
	                .catch(function (resp) {
	                    console.log(resp);
	                    alert("Failed to recount");
	                });
	            location.reload();
        	},
			get_soonDebt(){
				var app = this;
				axios.get('multicom-pos/public/api/v1/purchasesinvoices/func/soondebt')
	            	.then(function (resp) {
	                    app.soon_debt = resp.data;
	                })
	                .catch(function (resp) {
	                    console.log(resp);
	                    alert("Could not load debts");
	                });
			},
			get_totalDebt(){
				var app = this;
				axios.get('multicom-pos/public/api/v1/purchasesinvoices/func/totaldebt')
	            	.then(function (resp) {
	                    app.total_debt = resp.data;
	                })
	                .catch(function (resp) {
	                    console.log(resp);
	                    alert("Could not load total_debt");
	                });
			},
			get_profit(){
				var app = this;
				var x = app.searchVar;
				axios.get('multicom-pos/public/api/v1/sales/func/profit?searchVarStartDate=' + x.start_date + '&searchVarEndDate=' + x.end_date)
	            	.then(function (resp) {
	                    app.profit = resp.data;
	                })
	                .catch(function (resp) {
	                    console.log(resp);
	                    alert("Could not load profit");
	                });
			},
			get_income(){
				var app = this;
				var x = app.searchVar;
				axios.get('multicom-pos/public/api/v1/salesinvoices/func/income?searchVarStartDate=' + x.start_date + '&searchVarEndDate=' + x.end_date)
	            	.then(function (resp) {
	                    app.income = resp.data;
	                })
	                .catch(function (resp) {
	                    console.log(resp);
	                    alert("Could not load income");
	                });
			},
			get_outcome(){
				var app = this;
				var x = app.searchVar;
				axios.get('multicom-pos/public/api/v1/purchasesinvoices/func/outcome?searchVarStartDate=' + x.start_date + '&searchVarEndDate=' + x.end_date)
	            	.then(function (resp) {
	                    app.outcome = resp.data;
	                })
	                .catch(function (resp) {
	                    console.log(resp);
	                    alert("Could not load outcome");
	                });
			},
			refresh(){
				var app = this;
				app.get_profit();
				app.get_income();
				app.get_outcome();
				app.get_totalDebt();
			    app.get_soonDebt();
			    app.show = false;
     			Vue.nextTick(function (){
       				app.show = true;
     			})
			},
		}
	}
</script>
