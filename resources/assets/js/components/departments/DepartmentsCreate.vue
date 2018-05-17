<template>
    <div>
        <form class = "form-horizontal" v-on:submit="saveForm()">
			<div class="row">
                <div class="form-group col-sm-12">
                    <label class="control-label col-xs-2">Department code</label>
                    <div class = "col-xs-10">
                        <input type="text" v-model="department.code" class="form-control">
    					<p style="color:red;" v-if="!$v.department.code.required">The code field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12">
                    <label class="control-label col-xs-2">Department name</label>
                    <div class = "col-xs-10">
                        <input type="text" v-model="department.name" class="form-control">
					   <p style="color:red;" v-if="!$v.department.name.required">The name field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12">
                    <label class="control-label col-xs-2">Department details</label>
                    <div class = "col-xs-10">
                        <input type="textarea" v-model="department.details" class="form-control">
					   <p style="color:red;" v-if="!$v.department.details.required">The details field is required!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-success" :disabled="$v.department.$invalid" v-on:click= "saveForm()">Create</button>
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
                department: {
				    code: '',
                    name: '',
                    details: '',
				}
            }
        },
		validations: {
			department: {
				code: {
					required
				},
				name: {
					required
				},
				details: {
					required
				},			
			}
		},
        methods: {
            saveForm() {
                event.preventDefault();
                var app = this;
                var newDepartment = app.department;
                axios.post('multicom-pos/public/api/v1/departments', newDepartment)
                    .then(function (resp) {
                        alert("Department successfully created");
                        app.department = [];
                        location.reload();
                    })
                    .catch(function (error) {
                        console.log(error);
                        alert("Could not create your department");
                    });
            }
        }
    }
</script>
