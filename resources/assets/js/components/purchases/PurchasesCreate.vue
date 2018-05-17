<template>
    <div>
        &nbsp;<br>
        <button class = "btn btn-info" disabled>Invoice no. : {{ invoice_no }}</button>
        &nbsp;</br>
        &nbsp;</br>
        <div class = "row">
            <div class="form-group col-sm-12">
                <label class="control-label col-xs-2">Supplier name</label>
                <div class = "col-xs-10">
                    <input type="text" list= "supplierLists" class="form-control" placeholder="Supplier Name" v-model="searchVarSupplier" v-on:keyup="search_supplierList()">
                    <datalist id="supplierLists">
                        <template v-for="list in supplierList">
                            <option :value = "list.name">{{list.id}} - {{list.name}} </option>
                        </template>
                    </datalist>
                </div>
            </div>
        </div>
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
            <td><input :disabled="validated == 0" type="text" list= "itemLists" class="form-control" placeholder="Item Name" v-model="searchVarItem" v-on:keyup="search_itemList()">
                <datalist id="itemLists">
                    <template v-for="list in itemList">
                        <option :value = "list.item_name">{{list.id}} - {{list.item_name}} </option>
                    </template>
                </datalist>
            </td>
            <td>{{ inputPurchase.item.itemprice.buy_price }}</td>
            <td><input :disabled="validated == 0" type="number" class="form-control" placeholder="Qty" v-on:keyup="computeTotal()" v-model="inputPurchase.qty"></td>
            <td>{{ inputPurchase.total_purchase }}</td>
          </tr>
          </tbody>
        </table>
        <button
           class="btn btn-sm btn-success hidden-print"
           v-on:click="addPurchase()"
           :disabled="$v.inputPurchase.$invalid">
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
                <tr v-for="purchase, index in purchases" :key="purchase.item_id">
                    <td>{{ purchase.item.item_name }}</td>
                    <td>{{ purchase.item.itemprice.buy_price }}</td>
                    <td>{{ purchase.qty }}</td>
                    <td>{{ purchase.total_purchase }}</td>
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
        :disabled="$v.purchases.$invalid">
            Print
        </button>
    </div>
</template>

<script>
    import { required,minValue } from 'vuelidate/lib/validators'
    export default {
        data: function () {
            return {
                inputPurchase : {
                    item_id : null,
                    supplier_id : 0,
                    qty : 0,
                    item : {
                        item_name : null,
                        itemprice : {
                            id : null,
                            buy_price : null,
                        },
                    },
                    total_purchase : 0,
                },
                user : null,
                itemList : [],
                supplierList : [],
                purchases:[],
                qty_purchase : 0,
                paid : 1000,
                change : 0,
                total_price : 0,
                searchVarItem : null,
                searchVarSupplier : null,
                invoice_no : null,
                invoice_id : null,
                validated : 0,
            }
        },
        validations: {
            purchases: {
                required,
                $each: {
                    item_id: {
                        required
                    },
                    qty: {
                        required,
                        minValue : 1,
                    },
                }
            },
            inputPurchase : {
                item_id: {
                    required
                },
                qty: {
                    required,
                    minValue : 1,
                },
            }
        },
        mounted() {
            var app = this;
            axios.get('multicom-pos/public/api/v1/purchasesinvoices/func/invoice')
                .then(function (resp) {
                    app.invoice_no = resp.data;
                    app.inputPurchase.invoice_no = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Communcation failure, please try again");
                });
            axios.get('multicom-pos/public/api/v1/purchasesinvoices/func/newid')
                .then(function (resp) {
                    app.invoice_id = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Communcation failure, please try again");
                });
            axios.get('multicom-pos/public/api/v1/purchasesinvoices/func/currentUser')
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
                var supplier = app.inputPurchase.supplier_id;
                var searchVarItem = app.searchVarItem;
                if((supplier != null)&&(supplier != "")){
                    axios.get('multicom-pos/public/api/v1/items/supplierBasedItemSearch/'+ supplier +'/' + searchVarItem)
                        .then(function (resp) {
                            if(resp.data != []){
                                app.inputPurchase.item = resp.data[0];
                                app.inputPurchase.item_id = resp.data[0].id;
                                app.computeTotal();
                            }
                        })
                        .catch(function (resp) {
                            console.log(resp);
                            app.inputPurchase.item_id = null;
                        });
                }
            },
            search_supplierList(){
                var app = this;
                var searchVar = app.searchVarSupplier;
                if((searchVar.length > 1)&&(searchVar != null)&&(searchVar != "")){
                    axios.get('multicom-pos/public/api/v1/suppliers/search/' + searchVar)
                        .then(function (resp) {
                            if(resp.data != []){
                                app.supplierList = resp.data;
                                console.log(app.supplierList.length);
                                if (app.supplierList.length == 1){
                                    app.inputPurchase.supplier_id = resp.data[0].id;
                                    app.computeTotal();
                                    app.validated = 1;
                                    var supplier = app.inputPurchase.supplier_id;
                                    axios.get('multicom-pos/public/api/v1/items/supplierBasedSearch/'+ supplier)
                                        .then(function (resp) {
                                            if(resp.data != []){
                                                app.itemList = resp.data;
                                            }
                                        })
                                        .catch(function (resp) {
                                            console.log(resp);
                                        });
                                }
                            }
                        })
                        .catch(function (resp) {
                            console.log(resp);
                        });
                }
            },
            addPurchase(){
                var app = this;
                var newPurchase = app.inputPurchase;
                var index = app.qty_purchase;
                var supplier_id = app.inputPurchase.supplier_id;
                this.purchases.push(this.inputPurchase);
                this.inputPurchase = {
                    item_id : null,
                    supplier_id : supplier_id,
                    qty : 0,
                    item : {
                        item_name : null,
                        itemprice : {
                            id : null,
                            buy_price : null,
                        },
                    },
                    total_purchase : 0,
                };
                this.qty_purchase = index+1;
                this.searchVarItem = null;
                app.computeTotal();
            },
            computeTotal(){
                var app = this;
                this.inputPurchase.total_purchase = this.inputPurchase.qty * this.inputPurchase.item.itemprice.buy_price;
                var total = app.qty_purchase;
                this.total_price = 0;
                for(var i=0; i<total; i++){
                    this.purchases[i].total_purchase = this.purchases[i].qty * this.purchases[i].item.itemprice.buy_price;
                    this.total_price += this.purchases[i].total_purchase;
                }
                this.change = this.paid - this.total_price;
            },
            deleteEntry(index) {
                if (confirm("Do you really want to delete it?")) {
                    var app = this;
                    var ind = app.qty_purchase;
                    app.purchases.splice(index, 1);
                    this.qty_purchase = ind-1;
                    app.computeTotal();
                }
            },
            saveForm() {
                var app = this;
                var total = app.qty_purchase;
                var newInvoice = {
                    user_id : app.user.id,
                    supplier_id : app.inputPurchase.supplier_id,
                    purchase_invoice_no : app.invoice_no,
                    total : app.total_price,
                    paid : app.paid,
                    change : app.change,
                };
                axios.post('multicom-pos/public/api/v1/purchasesinvoices', newInvoice)
                    .then(function (resp) {
                        for(var i=0; i<total; i++){
                            var newPurchase = {
                                purchasesinvoice_id : app.invoice_id,
                                item_id : app.purchases[i].item_id,
                                itemprice_id : app.purchases[i].item.itemprice.id,
                                qty : app.purchases[i].qty,
                                total_purchase : app.purchases[i].total_purchase,
                            };
                            axios.post('multicom-pos/public/api/v1/purchases', newPurchase)
                                .then(function (resp) {
                                })
                                .catch(function (error) {
                                    console.log(error);
                                    alert("Could not create your purchase");
                                });
                        }
                        window.print();
                        location.reload();
                    })
                    .catch(function (error) {
                        console.log(error);
                        alert("Could not create your purchases");
                    });
            },
        }
    }
</script>
