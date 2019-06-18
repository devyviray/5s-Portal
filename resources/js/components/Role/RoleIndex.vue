<template>
    <div id="wrapper">
         <loader v-if="loading"></loader>
         <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="row">
                <div class="col-md-9"></div>
                <div class="col-md-2">
                    <span class="span-username">Hi, {{ this.userName }}</span>
                </div>
                <div class="col-md-1">
                    <navbarRight :user-role="userRole"></navbarRight>
                </div>
            </div>
        </nav>
        <div id="page-wrapper">
            <div class="div-spacing"></div>     
            <div class="header">
                <h1 class="page-header">
                    <img class="lafil-logo" :src="logoLink">
                    <b>5S PORTAL - ROLE</b>
                </h1>
                <breadcrumb></breadcrumb>
            </div>
            <div id="page-inner">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Role List</h3>
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
                <!-- Roles table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">LEvel</th>
                            <th scope="col">Created date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(role, r) in filteredQueues" v-bind:key="r">
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" data-toggle="modal" data-target="#editModal" style="cursor: pointer" @click="copyObject(role)">Edit</a>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(role)">Delete</a>
                                    </div>
                                </div>
                            </td>
                            <td scope="row">{{ role.id }}</td>
                            <td>{{ role.name }}</td>
                            <td>{{ role.level }}</td>
                            <td>{{ role.created_at }}</td>
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
                    <span>{{ filteredQueues.length }} Filtered Role(s)</span><br>
                    <span>{{ roles.length }} Total Role(s)</span>
                </div>
            </div>
        </div>
        <!-- Add Role Modal -->
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
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Add Role</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="role_added">
                            <strong>Success!</strong> Role succesfully added
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Name*</label> 
                                    <input type="text" id="role_name" class="form-control" v-model="role.name" placeholder="Role name">
                                    <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Level*</label> 
                                    <input type="text" id="role_name" class="form-control" v-model="role.level" placeholder="Role level">
                                    <span class="text-danger" v-if="errors.level">{{ errors.level[0] }}</span>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="addRole(role)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Role Modal -->
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
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Edit Role</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="role_updated">
                            <strong>Success!</strong> Role succesfully updated
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Name*</label> 
                                    <input type="text" id="role_name" class="form-control" v-model="role_copied.name" placeholder="Role name">
                                    <span class="text-danger" v-if="errors.role_name">{{ errors.role_name[0] }}</span>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Level*</label> 
                                    <input type="text" id="role_name" class="form-control" v-model="role_copied.level" placeholder="Role level">
                                    <span class="text-danger" v-if="errors.level">{{ errors.level[0] }}</span>
                                </div>
                            </div>  
                        </div> 
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateRole(role_copied)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Role Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Delete Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Are you sure you want to delete this Role?
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-warning" @click="deleteRole">Delete</button>
                </div>
                </div>
            </div>
        </div>

</div>
</template>

<script>
    import navbarRight from '../NavbarRight';
    import breadcrumb from '../Breadcrumb';
    import loader from '../Loader';
    export default {
        props: ['userName','userRole'],
        components:{
            navbarRight,
            breadcrumb,
            loader
        },
        data(){
            return {
                roles : [],
                role: [],
                role_copied: [],
                role_id: '',
                role_added: false,
                role_updated: false, 
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: '',
                loading: false,
            }
        },
        created(){
            this.fetchRoles();
        },
        methods:{
            showLoader(){
               this.loading = true;
            },
            copyObject(role){
                this.role_updated = false;
                this.role_id = role.id;
                this.role_copied = Object.assign({}, role);
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
            addRole(role){
                this.showLoader();
                document.getElementById('add_btn').disabled = true;
                this.errors = [];
                axios.post('/role', {
                    name: role.name,
                    level: role.level
                })
                .then(response => {
                    this.roles.unshift(response.data);
                    this.role_added = true;
                    document.getElementById('add_btn').disabled = false;
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.role_added = false;
                    document.getElementById('add_btn').disabled = false;
                    this.loading = false;
                })
            },
            updateRole(role_copied){
                this.showLoader();
                document.getElementById('edit_btn').disabled = true;
                this.role_updated = false;
                this.errors = [];
                var index = this.roles.findIndex(item => item.id == role_copied.id);
                axios.post(`/role/${role_copied.id}`, {
                    name: role_copied.name,
                    level: role_copied.level,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.role_updated = true;
                    this.roles.splice(index,1,response.data);
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
                .catch(error => {
                    this.role_updated = false;
                    this.errors = error.response.data.errors;
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            deleteRole(){
                this.showLoader();
                var index = this.roles.findIndex(item => item.id == this.role_id);
                axios.delete(`/role/${this.role_id}`)
                .then(response => {
                    $('#deleteModal').modal('hide');
                    alert('Location successfully deleted');
                    this.roles.splice(index,1);
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.loading = false;
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
            filteredRoles(){
                let self = this;
                return self.roles.filter(role => {
                    return role.name.toLowerCase().includes(this.keywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(this.roles.length / this.itemsPerPage);
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredRoles.slice(index, index + this.itemsPerPage);

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
