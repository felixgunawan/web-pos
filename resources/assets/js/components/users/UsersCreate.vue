<template>
    <div>
        <form class="form-horizontal" v-on:submit="saveForm()">
            <div class="form-group">
                <label for="role" class="col-md-2 control-label">Role</label>
                <div class="col-md-9">
                    <select id="role" class="form-control" v-model="user.role">
                        <option value = "admin">Admin</option>
                        <option value = "cashier">Kasir</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="username" class="col-md-2 control-label">Username</label>
                <div class="col-md-9">
                    <input id="username" type="username" class="form-control" v-model="user.username">
                    <p style="color:red;" v-if="!$v.user.username.required">The username field is required!</p>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">Name</label>
                <div class="col-md-9">
                    <input id="name" type="text" class="form-control" v-model="user.name">
                    <p style="color:red;" v-if="!$v.user.name.required">The name field is required!</p>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-2 control-label">E-Mail Address</label>
                <div class="col-md-9">
                    <input id="email" type="email" class="form-control" v-model="user.email">
                    <p style="color:red;" v-if="!$v.user.email.required">The email field is required!</p>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-md-2 control-label">Password</label>
                <div class="col-md-9">
                    <input id="password" type="password" class="form-control" v-model="user.password">
                    <p style="color:red;" v-if="!$v.user.password.required">The password field is required!</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label class="control-label col-xs-2">Location</label>
                    <div class = "col-xs-10">
                        <input type="text" v-on:keyup="search_location()" list= "locationList" v-model="user.location.name" class="form-control">
                        <datalist id="locationList">
                            <template v-for="loc in locationsName">
                                <option :value = "loc.name">{{loc.id}} - {{loc.name}} </option>
                            </template>
                        </datalist>
                        <p style="color:red;" v-if="!$v.user.location.name.required">The location name field is required!</p>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-2 col-md-offset-2">
                    <button class="btn btn-success" :disabled="$v.user.$invalid" v-on:click= "saveForm()">Create</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import { required } from 'vuelidate/lib/validators'
    export default {
        data: function () {
            return {
                user: {
                    role: '',
                    username: '',
                    name : '',
                    email: '',
                    password : '',
                    location_id : '',
                    location:{
                        name : '',
                    },
                },
                locationsName:[]
            }
        },
        validations: {
            user: {
                role: {
                    required
                },
                username: {
                    required
                },
                name: {
                    required
                },
                email: {
                    required
                },
                password: {
                    required
                },
                location:{
                    name:{
                        required
                    }
                },
            }
        },
        methods: {
            search_location(){
                var app = this;
                var codesearch = app.user.location.name;
                if(codesearch.length > 1){
                    axios.get('multicom-pos/public/api/v1/locations/search/' + codesearch)
                        .then(function (resp) {
                            app.locationsName = resp.data;
                        })
                        .catch(function (resp) {
                            alert("Could not load locations");
                        });
                    app.updateId();
                }
            },
            updateId() {
                var app = this;
                var namelocation = app.user.location.name;
                axios.get('multicom-pos/public/api/v1/locations/search/' + namelocation)
                    .then(function (resp) {
                        app.user.location_id = resp.data[0].id;
                    })
                    .catch(function (resp) {
                    });
            },
            saveForm() {
                event.preventDefault();
                var app = this;
                app.updateId();
                var newUser = app.user;
                axios.post('multicom-pos/public/register', newUser)
                    .then(function (resp) {
                        alert("User created successfully");
                        app.user = [];
                        location.reload();
                    })
                    .catch(function (error) {
                        console.log(error);
                        alert("Could not create your user");
                    });
            }
        }
    }
</script>
