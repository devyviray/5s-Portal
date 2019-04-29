<template>
    <div id="wrapper">
         <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="row">
                <div class="col-md-9"></div>
                <div class="col-md-2">
                    <span class="span-username">Hi, Devy Viray</span>
                </div>
                <div class="col-md-1">
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li> <a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a> </li>
                                <li> <a href="#"><i class="fa fa-users fa-fw"></i> Users</a> </li>
                                <li> <a href="#"><i class="fa fa-building-o fa-fw"></i> Companies</a> </li>
                                <li> <a href="#"><i class="fa fa-globe fa-fw"></i> Locations</a> </li>
                                <li> <a :href="departmentLink"><i class="fa fa-trello fa-fw"></i> Departments</a> </li>
                                <li> <a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a> </li>
                                <li class="divider"></li>
                                <li> <a href="#" @click="logoutForm"><i class="fa fa-sign-out fa-fw"></i> Logout</a> </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="page-wrapper">
            <div class="div-spacing"></div>     
            <div class="header">
                <h1 class="page-header">
                    <img class="lafil-logo" :src="logoLink">
                    <b>5S PORTAL - DEPARTMENT</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li><span style="color: #FFFF">|</span>
                    <li><a href="#">Report & Rating</a></li> <span  style="color: #FFFF">|</span>
                    <li><a href="#">Orientation</a></li><span style="color: #FFFF">|</span>
                    <li><a href="#">Exam</a></li><span style="color: #FFFF">|</span>
                    <li><a href="#">FAQs</a></li>
                </ol>
            </div>
            <div id="page-inner">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Department List</h3>
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
                <!-- Departments table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Creaeted date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(department, d) in filteredQueues" v-bind:key="d">
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" data-toggle="modal" data-target="#editModal" style="cursor: pointer" @click="copyObject(department)">Edit</a>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(department)">Delete</a>
                                    </div>
                                </div>
                            </td>
                            <td scope="row">{{ department.id }}</td>
                            <td>{{ department.name }}</td>
                            <td>{{ department.created_at }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Add Department Modal -->
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
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Add Department</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="department_added">
                            <strong>Success!</strong> Department succesfully added
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Name*</label> 
                                    <input type="text" id="department_name" class="form-control" v-model="department.name" placeholder="Department name">
                                    <span class="text-danger" v-if="errors.department_name">{{ errors.department_name[0] }}</span>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="addDepartment(department)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Department Modal -->
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
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Update Department</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="department_updated">
                            <strong>Success!</strong> Department succesfully added
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Name*</label> 
                                    <input type="text" id="department_name" class="form-control" v-model="department_copied.name" placeholder="Department name">
                                    <span class="text-danger" v-if="errors.department_name">{{ errors.department_name[0] }}</span>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateDepartment(department_copied)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Department Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Delete Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Are you sure you want to delete this Department?
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-warning" @click="deleteDepartment">Delete</button>
                </div>
                </div>
            </div>
        </div>

</div>
</template>

<script>
    export default {
        data(){
            return {
                departments : [],
                department: [],
                department_copied: [],
                department_id: '',
                department_added: false,
                department_updated: false, 
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: '',
            }
        },
        created(){
            this.fetchDepartments();
        },
        methods:{
            copyObject(department){
                this.department_updated = false;
                this.department_id = department.id;
                this.department_copied = Object.assign({}, department);
            },
            logoutForm(){
                axios.post('/logout')
                .then(response =>{
                    if(response.status == 200){
                        window.location.href = window.location.origin+'/login';
                    }
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
            addDepartment(department){
                document.getElementById('add_btn').disabled = true;
                this.errors = [];
                axios.post('/department', {
                    name: department.name
                })
                .then(response => {
                    this.departments.unshift(response.data);
                    this.department_added = true;
                    document.getElementById('add_btn').disabled = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.department_added = false;
                    document.getElementById('add_btn').disabled = false;
                })
            },
            updateDepartment(department_copied){
                document.getElementById('edit_btn').disabled = true;
                this.department_updated = false;
                this.errors = [];
                var index = this.departments.findIndex(item => item.id == department_copied.id);
                axios.post(`/department/${department_copied.id}`, {
                    name: department_copied.name,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.department_updated = true;
                    this.departments.splice(index,1,response.data);
                    document.getElementById('edit_btn').disabled = false;
                    // this.loading = false;
                })
                .catch(error => {
                    this.department_updated = false;
                    this.errors = error.response.data.errors;
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            deleteDepartment(){
                var index = this.departments.findIndex(item => item.id == this.department_id);
                axios.delete(`/department/${this.department_id}`)
                .then(response => {
                    $('#deleteModal').modal('hide');
                    alert('Department successfully deleted');
                    this.departments.splice(index,1);
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            },
        },  
        computed:{
            filteredDepartments(){
                let self = this;
                return self.departments.filter(department => {
                    return department.name.toLowerCase().includes(this.keywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(this.departments.length / this.itemsPerPage);
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredDepartments.slice(index, index + this.itemsPerPage);

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
            departmentLink(){
                return window.location.origin+'/departments'
            },
        }
    }
</script>
