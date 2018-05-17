<template>
    <div>
        &nbsp;<br>
        <button class = "btn btn-info" disabled>Invoice no. : {{ invoice_no }}</button>
        &nbsp;</br>
        &nbsp;</br>           
        <table class="table table-bordered table-striped table-dark hidden-print">
            <thead>
              <tr>
                <th>Item Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
              </tr>
            </thead>
          <tbody>
          <tr>
            <td><input type="text" list= "itemLists" class="form-control" placeholder="Item Name" v-model="searchVarItem" v-on:keyup="search_itemList()">
                <datalist id="itemLists">
                    <template v-for="list in itemList">
                        <option :value = "list.item_name">{{list.id}} - {{list.item_name}} </option>
                    </template>
                </datalist>
            </td>
            <td>{{ inputSale.item.itemprice.sell_price }}</td>
            <td><input type="number" class="form-control" placeholder="Qty" v-on:keyup="computeTotal()" v-model="inputSale.qty"></td>
            <td>{{ inputSale.total_sale }}</td>
          </tr>
          </tbody>
        </table>
        <button
           class="btn btn-sm btn-success hidden-print"
           v-on:click="addSale()"
           :disabled="$v.inputSale.$invalid">
           Add
        </button>

        &nbsp;</br>
        &nbsp;</br> 
          <table id = "invoice" class="table table-bordered table-striped table-dark">
              <thead>
              <tr>
                <th>Item Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
              </tr>
              </thead>
              <tbody>
                <tr v-for="sale, index in sales" :key="sale.item_id">
                    <td>{{ sale.item.item_name }}</td>
                    <td>{{ sale.item.itemprice.sell_price }}</td>
                    <td>{{ sale.qty }}</td>
                    <td>{{ sale.total_sale }}</td>
                    <div class="hidden-print">
                        <a
                            class="btn btn-xs btn-danger"
                            v-on:click="deleteEntry(index)">
                            Delete
                        </a>
                    </div>
                </tr>
                <tr>
                    <td colspan ="3">Total</td>
                    <td>{{ total_price }}</td>
                </tr>
                <tr>
                    <td colspan ="3">Paid</td>
                    <td><input type="number" v-on:keyup="computeTotal()" class="form-control hidden-print" placeholder="Paid" v-model="paid"><span class = "visible-print">{{ paid }}</span></td>
                </tr>
                <tr>
                    <td colspan ="3">Change</td>
                    <td>{{ change }}</td>
                </tr>
              </tbody>
          </table>
        <button 
        v-on:click="saveForm()" 
        class="btn btn-sm btn-warning hidden-print" 
        :disabled="$v.sales.$invalid">
            Print
        </button>
    </div>
</template>

<script>
    import { required,numeric,minLength } from 'vuelidate/lib/validators'
    export default {
        data: function () {
            return {
                inputSale : {
                    item_id : null,
                    qty : 0,
                    item : {
                        item_name : null,
                        itemprice : {
                            id : null,
                            sell_price : null,
                        },
                    },
                    total_sale : 0,
                },
                user : null,
                itemList : [],
                sales:[],
                qty_sale : 0,
                paid : 1000,
                change : 0,
                total_price : 0,
                searchVarItem : null,
                invoice_no : null,
                invoice_id : null,
                user_id : null,
            }
        },
        validations: {
            sales: {
                required,
                $each: {
                    item_id: {
                        required
                    },
                    qty: {
                        required
                    },
                }
            },
            inputSale : {
                item_id: {
                    required
                },
                qty: {
                    required
                },
            }
        },
        mounted() {
            var app = this;
            axios.get('multicom-pos/public/api/v1/salesinvoices/func/invoice')
                .then(function (resp) {
                    app.invoice_no = resp.data;
                    app.inputSale.invoice_no = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Communcation failure, please try again");
                });
            axios.get('multicom-pos/public/api/v1/salesinvoices/func/newid')
                .then(function (resp) {
                    app.invoice_id = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Communcation failure, please try again");
                });
            axios.get('multicom-pos/public/api/v1/salesinvoices/func/currentUser')
                .then(function (resp) {
                    app.user = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Communcation failure, please try again");
                });
        },
        methods: {
            search_itemList(){
                var app = this;
                var searchVar = app.searchVarItem;
                if((searchVar.length > 1)&&(searchVar != null)&&(searchVar != "")){
                    axios.get('multicom-pos/public/api/v1/items/search/' + searchVar)
                        .then(function (resp) {
                            if(resp.data != []){
                                app.itemList = resp.data;
                                app.inputSale.item = resp.data[0];
                                app.inputSale.item_id = resp.data[0].id;
                                app.computeTotal();
                            }
                        })
                        .catch(function (resp) {
                            console.log(resp);
                            alert("Could not load items");
                        });
                }
            },
            addSale(){
                var app = this;
                var newSale = app.inputSale;
                var index = app.qty_sale;
                this.sales.push(this.inputSale);
                this.inputSale = {
                    item_id : null,
                    qty : 0,
                    item : {
                        item_name : null,
                        itemprice : {
                            id : null,
                            sell_price : null,
                        },
                    },
                    total_sale : 0,
                };
                this.qty_sale = index+1;
                this.searchVarItem = null;
                app.computeTotal();
            },
            computeTotal(){
                var app = this;
                this.inputSale.total_sale = this.inputSale.qty * this.inputSale.item.itemprice.sell_price;
                var total = app.qty_sale;
                this.total_price = 0;
                for(var i=0; i<total; i++){
                    this.sales[i].total_sale = this.sales[i].qty * this.sales[i].item.itemprice.sell_price;
                    this.total_price += this.sales[i].total_sale;
                }
                this.change = this.paid - this.total_price;
            },
            deleteEntry(index) {
                if (confirm("Do you really want to delete it?")) {
                    var app = this;
                    var ind = app.qty_sale;
                    app.sales.splice(index, 1);
                    this.qty_sale = ind-1;
                    app.computeTotal();
                }
            },
            saveForm() {
                var app = this;
                var total = app.qty_sale;
                var newInvoice = {
                    user_id : app.user.id,
                    sale_invoice_no : app.invoice_no,
                    total : app.total_price,
                    paid : app.paid,
                    change : app.change,
                };
                axios.post('multicom-pos/public/api/v1/salesinvoices', newInvoice)
                    .then(function (resp) {
                        for(var i=0; i<total; i++){
                            var newSale = {
                                salesinvoice_id : app.invoice_id,
                                item_id : app.sales[i].item_id,
                                itemprice_id : app.sales[i].item.itemprice.id,
                                qty : app.sales[i].qty,
                                total_sale : app.sales[i].total_sale,
                            };
                            axios.post('multicom-pos/public/api/v1/sales', newSale)
                                .then(function (resp) {
                                })
                                .catch(function (error) {
                                    console.log(error);
                                    alert("Could not create your sale");
                                });
                        }
                        window.print();
                        location.reload();
                    })
                    .catch(function (error) {
                        console.log(error);
                        alert("Could not create your sale");
                    });
            },
        }
    }
</script>
