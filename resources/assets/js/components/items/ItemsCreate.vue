<template>
    <div>
        <form class = "form-horizontal" v-on:submit="saveForm()">
			<div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Item code</label>
                    <div class = "col-xs-10">
                        <input type="text" v-model="item.item_code" class="form-control">
					   <p style="color:red;" v-if="!$v.item.item_code.required">The item code field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Supplier code</label>
                    <div class = "col-xs-10">
                        <input type="text" v-on:keyup="search_supplierCode()" list= "supplierList"  v-model="item.supplier.code" class="form-control">
                        <datalist id="supplierList">
                            <template v-for="sup in suppliersCode">
                                <option :value = "sup.code">{{sup.id}} - {{sup.code}} </option>
                            </template>
                        </datalist>
                        <p style="color:red;" v-if="!$v.item.supplier.code.required">The supplier code field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Department code</label>
                    <div class = "col-xs-10">
                        <input type="text" v-on:keyup="search_departmentCode()" list= "departmentList" v-model="item.department.code" class="form-control">
                        <datalist id="departmentList">
                            <template v-for="dep in departmentsCode">
                                <option :value = "dep.code">{{dep.id}} - {{dep.code}} </option>
                            </template>
                        </datalist>
                        <p style="color:red;" v-if="!$v.item.department.code.required">The department code field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Item name</label>
                    <div class = "col-xs-10">
                        <input type="text" v-model="item.item_name" class="form-control">
    					<p style="color:red;" v-if="!$v.item.item_name.required">The name field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Item unit</label>
                    <div class = "col-xs-10">
                        <input type="text" v-model="item.unit" class="form-control">
				    </div>
                </div>
            </div>
			<div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Item buy price</label>
                    <div class = "col-xs-10">
                        <input type="number" v-model="item.buy_price" class="form-control">
    					<p style="color:red;" v-if="!$v.item.buy_price.required">The buy price field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Item sell price</label>
                    <div class = "col-xs-10">
                        <input type="number" v-model="item.sell_price" class="form-control">
					   <p style="color:red;" v-if="!$v.item.sell_price.required">The sell price field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Item first stock</label>
                    <div class = "col-xs-10">
                        <input type="number" v-model="item.first_stock" class="form-control">
                    </div>
				</div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Location</label>
                    <div class = "col-xs-10">
                        <input type="text" v-on:keyup="search_location()" list= "locationList" v-model="item.location.name" class="form-control">
                        <datalist id="locationList">
                            <template v-for="loc in locationsName">
                                <option :value = "loc.name">{{loc.id}} - {{loc.name}} </option>
                            </template>
                        </datalist>
                        <p style="color:red;" v-if="!$v.item.location.name.required">The location name field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-success" :disabled="$v.item.$invalid" v-on:click= "saveForm()">Create</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
	import { required,numeric } from 'vuelidate/lib/validators'
    export default {
        data: function () {
            return {
                item: {
                    supplier_id: '',
                    department_id: '',
                    location_id : '',
					item_code: '',
                    supplier:{
                        code : '',
                    },
                    department:{
                        code : '',
                    },
                    location:{
                        name : '',
                    },
                    item_name: '',
                    unit: '',
					buy_price:'',
                    sell_price: '',
                    first_stock: '',
                },
                suppliersCode:[],
                departmentsCode:[],
                locationsName:[]
            }
        },
		validations: {
			item: {
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
                location:{
                    name:{
                        required
                    }
                },
				item_name: {
					required
				},
				buy_price: {
					required
				},
				sell_price: {
					required
				},	
			}
		},
        methods: {
            search_location(){
                var app = this;
                var codesearch = app.item.location.name;
                if(codesearch.length > 1){
                    axios.get('multicom-pos/public/api/v1/locations/search/' + codesearch)
                        .then(function (resp) {
                            app.locationsName = resp.data;
                            console.log("a");
                        })
                        .catch(function (resp) {
                            alert("Could not load locations");
                        });
                    app.updateId();
                }
            },
            search_supplierCode(){
                console.log("searchsupplier");
                var app = this;
                var codesearch = app.item.supplier.code;
                if((codesearch.length > 1)&&(codesearch != null)&&(codesearch != "")){
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
                    app.updateId();
                }
            },
            search_departmentCode(){
                console.log("searchdepartment");
                var app = this;
                var codesearch = app.item.department.code;
                if((codesearch.length > 1)&&(codesearch != null)&&(codesearch != "")){
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
                        app.updateId();
                }
            },
            updateId() {
                var app = this;
                var codesupplier = app.item.supplier.code;
                axios.get('multicom-pos/public/api/v1/suppliers/code/' + codesupplier)
                    .then(function (resp) {
                        app.item.supplier_id = resp.data[0].id;
                        console.log(resp.data[0].id);
                        console.log(app.item.supplier_id);
                    })
                    .catch(function (resp) {
                    });
                var codedepartment = app.item.department.code;
                axios.get('multicom-pos/public/api/v1/departments/code/' + codedepartment)
                    .then(function (resp) {
                        app.item.department_id = resp.data[0].id;
                        console.log(resp.data[0].id);
                        console.log(app.item.department_id);
                    })
                    .catch(function (resp) {
                    });
                var namelocation = app.item.location.name;
                axios.get('multicom-pos/public/api/v1/locations/search/' + namelocation)
                    .then(function (resp) {
                        app.item.location_id = resp.data[0].id;
                        console.log(resp.data[0].id);
                        console.log(app.item.location_id);
                        console.log("loc");
                        console.log(location_id);
                    })
                    .catch(function (resp) {
                    });
            },
            saveForm() {
                event.preventDefault();
                var app = this;
                app.updateId();
                var newItem = app.item;
                console.log(app.item.location_id);
                axios.post('multicom-pos/public/api/v1/items', newItem)
                    .then(function (resp) {
                        alert("Item created successfully");
                        app.item = [];
                        location.reload();
                    })
                    .catch(function (error) {
                        console.log(error);
                        alert("Could not create your item");
                    });
            }
        }
    }
</script>
