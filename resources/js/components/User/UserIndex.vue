<template>
    <div id="wrapper">
         <loader v-if="loading"></loader>
         <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-8">
                            <span class="span-username">Hi, {{ this.userName }}</span>
                        </div>
                        <div class="col-md-4">
                            <navbarRight :user-role-level="userRoleLevel" :user-id="userId"></navbarRight>
                        </div>
                    </div>
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
                <breadcrumb :user-role-level="userRoleLevel"></breadcrumb>
            </div>
            <div id="page-inner">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">User List</h3>
                            </div> 
                            <div class="col text-right">
                                <a href="javascript.void(0)" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">Add new</a>
                                <a href="javascript.void(0)" class="btn btn-sm btn-success" data-toggle="modal" data-target="#exportModal">Export List</a>
                            </div>
                        </div>
                        <!--Search Filters-->
                        <h4 class="mt-4 mb-2">Search:</h4>
                        <div class="row align-items-center">
                            <!--Username-->
                            <div class="form-group col-2">
                                <input type="text" class="form-control rounded" placeholder="Search by name" v-model="keywords.name" @keyup="searchKeyUp">
                            </div>
                            <!--Company-->
                            <div class="form-group col-3">
                                <multiselect v-model="keywords.company" :options="companies" :multiple="false"
                                placeholder="Search by company" label="name" :show-labels="false" @input="searchKeyUp" />
                            </div>
                            <!--Department-->
                            <div class="form-group col-4">
                                <multiselect v-model="keywords.department" :options="departments" :multiple="false"
                                placeholder="Search by department" label="name" :show-labels="false" track-by="id" @input="searchKeyUp" />
                            </div>
                            <!--Role-->
                            <div class="form-group col-2">
                                <multiselect v-model="keywords.role" :options="roles" :multiple="false"
                                placeholder="Search by role" label="name" :show-labels="false" track-by="id" @input="searchKeyUp" />
                            </div>
                            <!--Reset Search-->
                            <div class="form-group col-1">
                                <button class="btn btn-muted" @click="keywords = {}">Reset Search</button>
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
                                <th scope="col">Created at</th>
                                <th scope="col">Last Login</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="isProcessing">
                                <td colspan="9" class="text-center">
                                    <!-- <table-spinner /> -->
                                    <h4 class="text-warning">Loading... Please wait...</h4>
                                </td>
                            </tr>
                            <tr v-if="!isProcessing && !items.length">
                                <td colspan="9" class="py-10">
                                    No results found
                                </td>
                            </tr>
                            <template v-if="!isProcessing">
                                <tr v-for="(user, u) in items" v-bind:key="u">
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow">
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
                                    <td>{{ user.created_at }}</td>
                                    <td>{{ user.last_login_at? user.last_login_at: '--' }}</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <table-pagination v-if="items.length > 0" :pagination="pagination" v-on:updatePage="goToPage" v-on:doChangeLimit="changePageCount"/>
                    </div>
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
                                    <span class="text-danger" v-if="errors.department_id">{{ errors.department_id[0] }}</span>
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
		<!-- Export Users Modal -->
		<div class="modal fade" id="exportModal" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel" v-if="items.length > 0">EXPORT USERS</h5>
					<h5 class="modal-title" id="exampleModalLabel" v-else>TABLE IS EMPTY!</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div v-if="items.length > 0">
						<h3>Export {{ this.pagination.total }} user/s to excel file?</h3><br>
						<div v-if="keywords.name || keywords.company || keywords.department || keywords.role">
							<h4>Search filters applied:</h4>
							<div v-if="keywords.name">Name contains: {{ this.keywords.name }}</div>
							<div v-if="keywords.company">Company: {{ this.keywords.company.name }}</div>
							<div v-if="keywords.department">Department: {{ this.keywords.department.name }}</div>
							<div v-if="keywords.role">Role: {{ this.keywords.role.name }}</div>
						</div>
					</div>
					<div v-else>No entries found. Please check your search filters.</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<a v-if="items.length > 0" class="btn btn-success" :href="exportUrl" @click="closeModal('#exportModal')">Export</a>
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
    import loader from '../Loader';
    export default {
        props: ['userName', 'userRoleLevel', 'userId'],
        components:{
            Multiselect,
            vSelect,
            navbarRight,
            breadcrumb,
            loader
        },
        data(){
            return {
                users : [],
                items : [],
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
				keyTimeout: null,
                keywords: {
                    name: '',
                    company: '',
                    department: '',
                    role: ''
                },
                loading: false,

                //pagination =====
				pagination: {},
				page_limit: 10,
				currentPageToGo: 1,

                isProcessing: false,
            }
        },
        created(){
            this.fetchUsers();
            this.fetchCompanies();
            this.fetchDepartments();
            this.fetchRoles();
        },
        methods:{
            showLoader(){
               this.loading = true;
            },
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
                this.isProcessing = true;
                const searchPayload = {
                    company: this.keywords.company ? this.keywords.company.id : '',
                    department: this.keywords.department ? this.keywords.department.id : '',
                    role: this.keywords.role ? this.keywords.role.id : '',
                    location: this.keywords.location ? this.keywords.location.id : '',
                    name: this.keywords.name ? this.keywords.name : ''
                };

                axios.get('/users-all', {
                    params: {
                        ...searchPayload,
                        page: this.currentPageToGo,
                        page_limit: this.page_limit
                    }
                })
                .then(response => { 
                    this.items = response.data.data;

                    this.pagination = response.data;
					this.isProcessing = false;
					this.currentPageToGo = 1

					//Set pagination page count
					this.setPaginationPageRange(this.pagination.current_page, this.pagination.last_page);

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
                this.showLoader();
                this.user_added = false;
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
                    this.user = [];
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.user_added = false;
                    document.getElementById('add_btn').disabled = false;
                    this.loading = false;
                })
            },
            updateUser(user_copied){
                this.showLoader();
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
                    this.loading = false;
                })
                .catch(error => {
                    this.user_updated = false;
                    this.errors = error.response.data.errors;
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            deleteUser(){
                this.showLoader();
                var index = this.users.findIndex(item => item.id == this.user_id);
                axios.delete(`/user/${this.user_id}`)
                .then(response => {
                    $('#deleteModal').modal('hide');
                    alert('User successfully deleted');
                    this.users.splice(index,1);
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.loading = false;
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
		    closeModal(name) {
                $(name).modal('hide');
		    	$('.modal-backdrop').remove();
                this.loading = false;
		    },
            searchKeyUp() {
				clearTimeout(this.keyTimeout);
                this.keyTimeout = setTimeout(() => {
					this.isProcessing = true;
					this.fetchUsers();
                }, 500)
			},

            //Pagination methods =============================
            goToPage(page) {
				this.currentPageToGo = page;
                this.fetchUsers();
            },
            changePageCount(pageLimit) {
                this.page_limit = pageLimit;
                this.fetchUsers();
            },
            setPaginationPageRange(page, pageCount) {

                let start = page - 2,
                    end = page + 2;

                if (end > pageCount) {
                    start -= (end - pageCount);
                    end = pageCount;
                }

                if (start <= 0) {
                    end += ((start - 1) * (-1));
                    start = 1;
                }

                end = end > pageCount ? pageCount : end;

                return this.pagination.range = Array(end - start + 1).fill().map((_, idx) => start + idx)

            },
			//================================================ 
        },  
        computed:{
            logoLink(){
                return window.location.origin+'/img/lafil-logo.png';
            },
		    exportUrl() { //link for table export
		    	let endpoint = '/users/export';
		    	let filter = this.keywords;
		    	endpoint += '/' + (filter.name? filter.name: '_');
		    	endpoint += '&&' + (filter.company? filter.company.name: '_');
		    	endpoint += '&&' + (filter.department? filter.department.name: '_');
		    	endpoint += '&&' + (filter.role? filter.role.name: '_');
		    	return endpoint;
		    }
        }
    }
</script>
