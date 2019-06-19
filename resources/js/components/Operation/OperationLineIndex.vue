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
                    <navbarRight :user-role="userRole" :user-id="userId"></navbarRight>
                </div>
            </div>
        </nav>
        <div id="page-wrapper">
            <div class="div-spacing"></div>     
            <div class="header">
                <h1 class="page-header">
                    <img class="lafil-logo" :src="logoLink">
                    <b>5S PORTAL - OPERATION LINE</b>
                </h1>
                <breadcrumb></breadcrumb>
            </div>
            <div id="page-inner">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Operation line List</h3>
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
                            <th scope="col">Created date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(operation_line, o) in filteredQueues" v-bind:key="o">
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" data-toggle="modal" data-target="#editModal" style="cursor: pointer" @click="copyObject(operation_line)">Edit</a>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(operation_line)">Delete</a>
                                    </div>
                                </div>
                            </td>
                            <td scope="row">{{ operation_line.id }}</td>
                            <td>{{ operation_line.name }}</td>
                            <td>{{ operation_line.created_at }}</td>
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
                    <span>{{ filteredQueues.length }} Filtered Location(s)</span><br>
                    <span>{{ operation_lines.length }} Total Location(s)</span>
                </div>
            </div>
        </div>
        <!-- Add Operation line Modal -->
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
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Add Operation Line</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="operation_line_added">
                            <strong>Success!</strong> Operation Line succesfully added
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Name*</label> 
                                    <input type="text" id="operation_line_name" class="form-control" v-model="operation_line.name" placeholder="Operation Line name">
                                    <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="addOperationLine(operation_line)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Operation line Modal -->
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
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Edit Operation line</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="operation_line_updated">
                            <strong>Success!</strong> Operation Line succesfully added
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Name*</label> 
                                    <input type="text" id="operation_line_name" class="form-control" v-model="operation_line_copied.name" placeholder="Operation Line name">
                                    <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                                </div>
                            </div>  
                        </div> 
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateOperationLine(operation_line_copied)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Operation line Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Delete Operation line</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Are you sure you want to delete this Operation Line?
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-warning" @click="deleteOperationLine">Delete</button>
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
        props: ['userName', 'userRole', 'userId'],
        components:{
            navbarRight,
            breadcrumb,
            loader
        },
        data(){
            return {
                operation_lines : [],
                operation_line: [],
                operation_line_copied: [],
                operation_line_id: '',
                operation_line_added: false,
                operation_line_updated: false, 
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: '',
                loading: false,
            }
        },
        created(){
            this.fetchOperationLines();
        },
        methods:{
            showLoader(){
               this.loading = true;
            },
            copyObject(operation_line){
                this.operation_line_updated = false;
                this.operation_line_id = operation_line.id;
                this.operation_line_copied = Object.assign({}, operation_line);
            },
            fetchOperationLines(){
                axios.get('operation-lines-all')
                .then(response => {
                    this.operation_lines = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            addOperationLine(operation_line){
                this.showLoader();
                this.operation_line_added = false;
                document.getElementById('add_btn').disabled = true;
                this.errors = [];
                axios.post('/operation-line', {
                    name: operation_line.name
                })
                .then(response => {
                    this.operation_lines.unshift(response.data);
                    this.operation_line_added = true;
                    this.operation_line.name = "";
                    document.getElementById('add_btn').disabled = false;
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.operation_line_added = false;
                    document.getElementById('add_btn').disabled = false;
                    this.loading = false;
                })
            },
            updateOperationLine(operation_line_copied){
                this.showLoader();
                document.getElementById('edit_btn').disabled = true;
                this.operation_line_updated = false;
                this.errors = [];
                var index = this.operation_lines.findIndex(item => item.id == operation_line_copied.id);
                axios.post(`/operation-line/${operation_line_copied.id}`, {
                    name: operation_line_copied.name,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.operation_line_updated = true;
                    this.operation_lines.splice(index,1,response.data);
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
                .catch(error => {
                    this.operation_line_updated = false;
                    this.errors = error.response.data.errors;
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            deleteOperationLine(){
                this.showLoader();
                var index = this.operation_lines.findIndex(item => item.id == this.operation_line_id);
                axios.delete(`/operation-line/${this.operation_line_id}`)
                .then(response => {
                    $('#deleteModal').modal('hide');
                    alert('Location successfully deleted');
                    this.operation_lines.splice(index,1);
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
            filteredOperationLines(){
                let self = this;
                return self.operation_lines.filter(operation_line => {
                    return operation_line.name.toLowerCase().includes(this.keywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(this.operation_lines.length / this.itemsPerPage);
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredOperationLines.slice(index, index + this.itemsPerPage);

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
