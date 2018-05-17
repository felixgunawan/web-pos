<template>
    <div>
        <form class = "form-horizontal" v-on:submit="saveForm()">
			<div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Supplier code</label>
                    <div class = "col-xs-10">
                        <input type="text" v-model="location.code" class="form-control">
    					<p style="color:red;" v-if="!$v.location.code.required">The code field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Supplier name</label>
                    <div class = "col-xs-10">
                        <input type="text" v-model="location.name" class="form-control">
					   <p style="color:red;" v-if="!$v.location.name.required">The name field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Supplier address</label>
                    <div class = "col-xs-10">
                        <input type="text" v-model="location.address" class="form-control">
					   <p style="color:red;" v-if="!$v.location.address.required">The address field is required!</p>
                    </div>
                </div>
            </div>
			<div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Supplier city</label>
                    <div class = "col-xs-10">
                        <input type="text" v-model="location.city" class="form-control">
					   <p style="color:red;" v-if="!$v.location.city.required">The city field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Supplier phone</label>
                    <div class = "col-xs-10">
                        <input type="text" v-model="location.phone" class="form-control">
    					<p style="color:red;" v-if="!$v.location.phone.required">The phone field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-success" :disabled="$v.location.$invalid" v-on:click= "saveForm()">Create</button>
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
                location: {
					code: '',
                    name: '',
                    address: '',
					city:'',
                    phone: '',
                }
            }
        },
		validations: {
			location: {
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
		},
        methods: {
            saveForm() {
                event.preventDefault();
                var app = this;
                var newSupplier = app.location;
                console.log("test");
                axios.post('multicom-pos/public/api/v1/locations', newSupplier)
                    .then(function (resp) {
                        alert("Supplier created successfully");
                        app.location = [];
                        location.reload();
                    })
                    .catch(function (error) {
                        console.log(error);
                        alert("Could not create your location");
                    });
            }
        }
    }
</script>
