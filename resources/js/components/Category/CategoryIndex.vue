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
                    <b>5S PORTAL - CATEGORIES </b>
                </h1>
                <breadcrumb :user-role-level="userRoleLevel"></breadcrumb>
            </div>
            <div id="page-inner">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Category List</h3>
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
                        <tr v-for="(category, c) in filteredQueues" v-bind:key="c">
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" data-toggle="modal" data-target="#editModal" style="cursor: pointer" @click="copyObject(category)">Edit</a>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(category)">Delete</a>
                                    </div>
                                </div>
                            </td>
                            <td scope="row">{{ category.id }}</td>
                            <td>{{ category.name }}</td>
                            <td>{{ category.created_at }}</td>
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
                    <span>{{ filteredQueues.length }} Filtered Category(s)</span><br>
                    <span>{{ categories.length }} Total Category(s)</span>
                </div>
            </div>
        </div>
        <!-- Add Category Modal -->
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
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Add Category</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="category_added">
                            <strong>Success!</strong>Category succesfully added
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Name*</label> 
                                    <input type="text" id="category_name" class="form-control" v-model="category.name" placeholder="Category name">
                                    <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="addCategory(category)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Category Modal -->
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
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Edit Category</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="category_updated">
                            <strong>Success!</strong> Category succesfully updated
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Name*</label> 
                                    <input type="text" id="category_name" class="form-control" v-model="category_copied.name" placeholder="Category name">
                                    <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                                </div>
                            </div>  
                        </div> 
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateCategory(category_copied)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Category line Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Delete Category line</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Are you sure you want to delete this Category?
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-warning" @click="deleteCategory">Delete</button>
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
        props: ['userName', 'userRoleLevel', 'userId'],
        components:{
            navbarRight,
            breadcrumb,
            loader
        },
        data(){
            return {
                categories : [],
                category: [],
                category_copied: [],
                category_id: '',
                category_added: false,
                category_updated: false, 
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: '',
                loading: false,
            }
        },
        created(){
            this.fetchCategories();
        },
        methods:{
            showLoader(){
               this.loading = true;
            },
            copyObject(category){
                this.category_updated = false;
                this.category_id = category.id;
                this.category_copied = Object.assign({}, category);
            },
            fetchCategories(){
                axios.get('categories-all')
                .then(response => {
                    this.categories = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            addCategory(category){
                this.showLoader();
                this.category_added = false;
                document.getElementById('add_btn').disabled = true;
                this.errors = [];
                axios.post('/category', {
                    name: category.name
                })
                .then(response => {
                    this.categories.unshift(response.data);
                    this.category_added = true;
                    this.category.name = "";
                    document.getElementById('add_btn').disabled = false;
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.category_added = false;
                    document.getElementById('add_btn').disabled = false;
                    this.loading = false;
                })
            },
            updateCategory(category_copied){
                this.showLoader();
                document.getElementById('edit_btn').disabled = true;
                this.category_updated = false;
                this.errors = [];
                var index = this.categories.findIndex(item => item.id == category_copied.id);
                axios.post(`/category/${category_copied.id}`, {
                    name: category_copied.name,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.category_updated = true;
                    this.categories.splice(index,1,response.data);
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
                .catch(error => {
                    this.category_updated = false;
                    this.errors = error.response.data.errors;
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            deleteCategory(){
                this.showLoader();
                var index = this.categories.findIndex(item => item.id == this.category_id);
                axios.delete(`/category/${this.category_id}`)
                .then(response => {
                    $('#deleteModal').modal('hide');
                    alert('Category successfully deleted');
                    this.categories.splice(index,1);
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
            filteredCategories(){
                let self = this;
                return self.categories.filter(category => {
                    return category.name.toLowerCase().includes(this.keywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(this.categories.length / this.itemsPerPage);
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredCategories.slice(index, index + this.itemsPerPage);

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
