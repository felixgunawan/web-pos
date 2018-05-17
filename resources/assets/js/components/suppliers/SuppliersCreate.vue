<template>
    <div>
        <form class = "form-horizontal" v-on:submit="saveForm()">
			<div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Supplier code</label>
                    <div class = "col-xs-10">
                        <input type="text" v-model="supplier.code" class="form-control">
    					<p style="color:red;" v-if="!$v.supplier.code.required">The code field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Supplier name</label>
                    <div class = "col-xs-10">
                        <input type="text" v-model="supplier.name" class="form-control">
					   <p style="color:red;" v-if="!$v.supplier.name.required">The name field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Supplier address</label>
                    <div class = "col-xs-10">
                        <input type="text" v-model="supplier.address" class="form-control">
					   <p style="color:red;" v-if="!$v.supplier.address.required">The address field is required!</p>
                    </div>
                </div>
            </div>
			<div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Supplier city</label>
                    <div class = "col-xs-10">
                        <input type="text" v-model="supplier.city" class="form-control">
					   <p style="color:red;" v-if="!$v.supplier.city.required">The city field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Supplier phone</label>
                    <div class = "col-xs-10">
                        <input type="text" v-model="supplier.phone" class="form-control">
    					<p style="color:red;" v-if="!$v.supplier.phone.required">The phone field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Supplier deadline</label>
                    <div class = "col-xs-10">
                        <input type="number" v-model="supplier.deadline" class="form-control">
    					<p style="color:red;" v-if="!$v.supplier.deadline.required">The deadline field is required!</p>
    					<p style="color:red;" v-if="!$v.supplier.deadline.numeric">The deadline field must be numeric!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-success" :disabled="$v.supplier.$invalid" v-on:click= "saveForm()">Create</button>
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
                supplier: {
					          code: '',
                    name: '',
                    address: '',
					          city:'',
                    phone: '',
                    deadline: '',
                }
            }
        },
		validations: {
			supplier: {
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
				deadline: {
					required,
					numeric
				},			
			}
		},
        methods: {
            saveForm() {
                event.preventDefault();
                var app = this;
                var newSupplier = app.supplier;
                console.log("test");
                axios.post('multicom-pos/public/api/v1/suppliers', newSupplier)
                    .then(function (resp) {
                        alert("Supplier created successfully");
                        app.supplier = [];
                        location.reload();
                    })
                    .catch(function (error) {
                        console.log(error);
                        alert("Could not create your supplier");
                    });
            }
        }
    }
</script>
