<template>
    <div id="wrapper">
         <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="row">
                <div class="col-md-9"></div>
                <div class="col-md-2">
                    <span class="span-username">Hi, {{ this.userName }}</span>
                </div>
                <div class="col-md-1">
                    <navbarRight></navbarRight>
                </div>
            </div>
        </nav>
        <div id="page-wrapper">
            <div class="div-spacing"></div>     
            <div class="header">
                <h1 class="page-header">
                    <img class="lafil-logo" :src="logoLink">
                    <b>5S PORTAL - USER</b>
                </h1>
                <breadcrumb></breadcrumb>
            </div>
            <div id="page-inner">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">User List</h3>
                        </div> 
                        <div class="col text-right">
                            <a href="javascript.void(0)" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">Add new</a>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-xl-4 mb-2 mt-3 float-right">
                            <input type="text" class="form-control" placeholder="Search" v-model="keywords" id="keywords">
                        </div> 
                    </div>
                </div>
                <!-- Locations table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Company</th>
                            <th scope="col">Department</th>
                            <th scope="col">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, u) in filteredQueues" v-bind:key="u">
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" data-toggle="modal" data-target="#editModal" style="cursor: pointer" @click="copyObject(user)">Edit</a>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(user)">Delete</a>
                                    </div>
                                </div>
                            </td>
                            <td scope="row">{{ user.id }}</td>
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>
                                <span v-for="(company, c) in user.companies" :key="c">
                                    {{ company.name + ' - ' + user.location.name }} <br/>
                                </span>
                            </td>
                            <td>{{ user.department.name }} </td>
                            <td>
                                <span v-for="(role, r) in user.roles" :key="r">
                                    {{ role.name }} <br/>
                                </span> 
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row mb-3 mt-3 ml-3" v-if="filteredQueues.length ">
                <div class="col-6">
                    <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                        <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                    <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                </div>
                <div class="col-6 text-right">
                    <span>{{ filteredQueues.length }} Filtered User(s)</span><br>
                    <span>{{ users.length }} Total User(s)</span>
                </div>
            </div>
        </div>
        <!-- Add User Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-header">
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Add User</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="user_added">
                            <strong>Success!</strong> User succesfully added
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Name*</label> 
                                    <input type="text" id="name" class="form-control" v-model="user.name" placeholder="User name">
                                    <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Email*</label> 
                                    <input type="text" id="name" class="form-control" v-model="user.email" placeholder="Email address">
                                    <span class="text-danger" v-if="errors.email">{{ errors.email[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Company*</label> 
                                    <select class="form-control" v-model="user.company" @change="fetchCompanyLocation(user.company)">
                                        <option v-for="(company,c) in companies" v-bind:key="c" :value="company.id"> {{ company.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.company">{{ errors.company[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Company location*</label> 
                                    <select class="form-control" v-model="user.company_location">
                                        <option v-for="(location,l) in locations" v-bind:key="l" :value="location.id"> {{ location.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.company_location">{{ errors.company_location[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Department*</label> 
                                    <select class="form-control" v-model="user.department">
                                        <option v-for="(department,d) in departments" v-bind:key="d" :value="department.id"> {{ department.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.department">{{ errors.department[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Role*</label> 
                                    <select class="form-control" v-model="user.role">
                                        <option v-for="(role,r) in roles" v-bind:key="r" :value="role.id"> {{ role.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.role">{{ errors.role[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="addUser(user)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-header">
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Edit User</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="user_updated">
                            <strong>Success!</strong> User succesfully updated
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Name*</label> 
                                    <input type="text" id="name" class="form-control" v-model="user_copied.name" placeholder="Company name">
                                    <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Email*</label> 
                                    <input type="text" id="name" class="form-control" v-model="user_copied.email" placeholder="Email address">
                                    <span class="text-danger" v-if="errors.email">{{ errors.email[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Company*</label> 
                                    <select class="form-control" v-model="user_copied.company_id" @change="fetchCompanyLocation(user_copied.company_id)">
                                        <option v-for="(company,c) in companies" v-bind:key="c" :value="company.id"> {{ company.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.company">{{ errors.company[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Company location*</label> 
                                    <select class="form-control" v-model="user_copied.location_id">
                                        <option v-for="(location,l) in locations" v-bind:key="l" :value="location.id"> {{ location.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.company_location">{{ errors.company_location[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Department*</label> 
                                    <select class="form-control" v-model="user_copied.department_id">
                                        <option v-for="(department,d) in departments" v-bind:key="d" :value="department.id"> {{ department.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.department_id">{{ errors.department_id[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Role*</label> 
                                    <select class="form-control" v-model="user_copied.role_id">
                                        <option v-for="(role,r) in roles" v-bind:key="r" :value="role.id"> {{ role.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.role">{{ errors.role[0] }}</span>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateUser(user_copied)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete User Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Are you sure you want to delete this User?
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-warning" @click="deleteUser">Delete</button>
                </div>
                </div>
            </div>
        </div>

</div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<script>
    import Multiselect from 'vue-multiselect';
    import vSelect from 'vue-select';
    import navbarRight from '../NavbarRight';
    import breadcrumb from '../Breadcrumb';
    export default {
        props: ['userName'],
        components:{
            Multiselect,
            vSelect,
            navbarRight,
            breadcrumb
        },
        data(){
            return {
                users : [],
                user: [],
                user_copied: [],
                user_id: '',
                user_added: false,
                user_updated: false,
                companies: [],
                company:[],
                locations: [],
                location: [],
                departments: [],
                department: '',
                roles: [],
                role: [], 
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: '',
            }
        },
        created(){
            this.fetchUsers();
            this.fetchCompanies();
            this.fetchDepartments();
            this.fetchRoles();
        },
        methods:{
            customLabelLocation (company) {
                return `${company.name}`
            },
            copyObject(user){
                this.errors = [];
                this.user_updated = false;
                this.user_id = user.id;
                this.user_copied = Object.assign({}, user);
                this.user_copied.company_id = user.companies[0].id;
                this.user_copied.location_id = user.location.id;
                this.user_copied.role_id = user.roles[0].id
                this.fetchCompanyLocation(user.location.id);
            },
            fetchUsers(){
                axios.get('/users-all')
                .then(response => { 
                    this.users = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            fetchCompanies(){
                axios.get('companies-all')
                .then(response => {
                    this.companies = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            fetchDepartments(){
                axios.get('departments-all')
                .then(response => {
                    this.departments = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            fetchRoles(){
                axios.get('roles-all')
                .then(response => {
                    this.roles = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            addUser(user){
                document.getElementById('add_btn').disabled = true;
                this.errors = [];
                axios.post('/user', {
                    name: user.name,
                    email: user.email,
                    company: user.company,
                    company_location: user.company_location,
                    department_id: user.department,
                    role: user.role
                })
                .then(response => {
                    this.users.unshift(response.data);
                    this.user_added = true;
                    document.getElementById('add_btn').disabled = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.user_added = false;
                    document.getElementById('add_btn').disabled = false;
                })
            },
            updateUser(user_copied){
                document.getElementById('edit_btn').disabled = true;
                this.user_updated = false;
                this.errors = [];
                var index = this.users.findIndex(item => item.id == user_copied.id);
                axios.post(`/user/${user_copied.id}`, {
                    name: user_copied.name,
                    email: user_copied.email,
                    company: user_copied.company_id,
                    company_location: user_copied.location_id,
                    department_id: user_copied.department_id,
                    role: user_copied.role_id,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.user_updated = true;
                    this.users.splice(index,1,response.data);
                    document.getElementById('edit_btn').disabled = false;
                    // this.loading = false;
                })
                .catch(error => {
                    this.user_updated = false;
                    this.errors = error.response.data.errors;
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            deleteUser(){
                var index = this.users.findIndex(item => item.id == this.user_id);
                axios.delete(`/user/${this.user_id}`)
                .then(response => {
                    $('#deleteModal').modal('hide');
                    alert('User successfully deleted');
                    this.users.splice(index,1);
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            },
            fetchCompanyLocation(company){
                axios.get(`/company-location/${company}`)
                .then(response => { 
                    this.locations = response.data.locations;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            },
            setPage(pageNumber) {
                this.currentPage = pageNumber;
            },

            resetStartRow() {
                this.currentPage = 0;
            },

            showPreviousLink() {
                return this.currentPage == 0 ? false : true;
            },

            showNextLink() {
                return this.currentPage == (this.totalPages - 1) ? false : true;
            }   
        },  
        computed:{
            filteredUsers(){
                let self = this;
                return self.users.filter(user => {
                    return user.name.toLowerCase().includes(this.keywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(this.users.length / this.itemsPerPage);
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredUsers.slice(index, index + this.itemsPerPage);

                if(this.currentPage >= this.totalPages) {
                    this.currentPage = this.totalPages - 1
                }

                if(this.currentPage == -1) {
                    this.currentPage = 0;
                }

                return queues_array;
            },
            logoLink(){
                return window.location.origin+'/img/lafil-logo.png';
            },
        }
    }
</script>
