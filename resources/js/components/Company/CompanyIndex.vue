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
                    <b>5S PORTAL - COMPANY</b>
                </h1>
                <breadcrumb></breadcrumb>
            </div>
            <div id="page-inner">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Compamy List</h3>
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
                            <th scope="col">Location</th>
                            <th scope="col">Created date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(company, c) in filteredQueues" v-bind:key="c">
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" data-toggle="modal" data-target="#editModal" style="cursor: pointer" @click="copyObject(company)">Edit</a>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(company)">Delete</a>
                                    </div>
                                </div>
                            </td>
                            <td scope="row">{{ company.id }}</td>
                            <td>{{ company.name }}</td>
                            <td>
                                <span v-for="(location, l) in company.locations" :key="l">
                                    {{ location.name }} <br/>
                                </span>
                            </td>
                            <td>{{ company.created_at }}</td>
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
                    <span>{{ filteredQueues.length }} Filtered Company(s)</span><br>
                    <span>{{ companies.length }} Total Company(s)</span>
                </div>
            </div>
        </div>
        <!-- Add Company Modal -->
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
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Add Company</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="company_added">
                            <strong>Success!</strong> Company succesfully added
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Name*</label> 
                                    <input type="text" id="name" class="form-control" v-model="company.name" placeholder="Company name">
                                    <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Location*</label>
                                      <multiselect
                                            v-model="company.location"
                                            :options="locations"
                                            :multiple="true"
                                            track-by="id"
                                            :custom-label="customLabelLocation"
                                            placeholder="Select Location"
                                            id="selected_location"
                                        >
                                    </multiselect> 
                                    <span class="text-danger" v-if="errors.location">The location field is required.</span>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="addCompany(company)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Company Modal -->
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
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Edit Company</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="company_updated">
                            <strong>Success!</strong> Company succesfully updated
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Name*</label> 
                                    <input type="text" id="name" class="form-control" v-model="company_copied.name" placeholder="Company name">
                                    <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Location*</label>
                                      <multiselect
                                            v-model="company_copied.locations"
                                            :options="locations"
                                            :multiple="true"
                                            track-by="id"
                                            :custom-label="customLabelLocation"
                                            placeholder="Select Location"
                                            id="selected_location"
                                        >
                                    </multiselect> 
                                    <span class="text-danger" v-if="errors.location">The location field is required.</span>
                                </div>
                            </div>  
                        </div> 
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateCompany(company_copied)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Company Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Delete Company</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Are you sure you want to delete this Company?
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-warning" @click="deleteCompany">Delete</button>
                </div>
                </div>
            </div>
        </div>

</div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<script>
    import Multiselect from 'vue-multiselect';
    import navbarRight from '../NavbarRight';
    import breadcrumb from '../Breadcrumb';
    import loader from '../Loader';
    export default {
        props: ['userName', 'userRole', 'userId'],
        components:{
            Multiselect,
            navbarRight,
            breadcrumb,
            loader
        },
        data(){
            return {
                companies : [],
                company: [],
                company_copied: [],
                company_id: '',
                company_added: false,
                company_updated: false,
                locations: [],
                location:[], 
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: '',
                loading: false,
            }
        },
        created(){
            this.fetchCompanies();
            this.fetchLocations();
        },
        methods:{       
            showLoader(){
               this.loading = true;
            },
            customLabelLocation (location) {
                return `${location.name  }`
            },
            copyObject(company){
                this.errors = [];
                this.company_updated = false;
                this.company_id = company.id;
                this.company_copied = Object.assign({}, company);
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
            fetchLocations(){
                axios.get('/locations-all')
                .then(response => { 
                    this.locations = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            addCompany(company){
                this.showLoader();
                this.company_added = false;
                var locationIds = [];
                if(company.location){
                    company.location.forEach((location) => {
                        locationIds.push(location.id);
                    });  
                }

                document.getElementById('add_btn').disabled = true;
                this.errors = [];
                axios.post('/company', {
                    name: company.name,
                    location: locationIds
                })
                .then(response => {
                    this.companies.unshift(response.data);
                    this.company_added = true;
                    document.getElementById('add_btn').disabled = false;
                    this.company = [];
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.company_added = false;
                    document.getElementById('add_btn').disabled = false;
                    this.loading = false;
                })
            },
            updateCompany(company_copied){
                this.showLoader();
                var locationIds = [];
                if(company_copied.locations){
                    company_copied.locations.forEach((location) => {
                        locationIds.push(location.id);
                    });  
                }
                
                document.getElementById('edit_btn').disabled = true;
                this.company_updated = false;
                this.errors = [];
                var index = this.companies.findIndex(item => item.id == company_copied.id);
                axios.post(`/company/${company_copied.id}`, {
                    name: company_copied.name,
                    location: locationIds,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.company_updated = true;
                    this.companies.splice(index,1,response.data);
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
                .catch(error => {
                    this.company_updated = false;
                    this.errors = error.response.data.errors;
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            deleteCompany(){
                this.showLoader();
                var index = this.companies.findIndex(item => item.id == this.company_id);
                axios.delete(`/company/${this.company_id}`)
                .then(response => {
                    $('#deleteModal').modal('hide');
                    alert('Company successfully deleted');
                    this.companies.splice(index,1);
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
            filteredCompanies(){
                let self = this;
                return self.companies.filter(company => {
                    return company.name.toLowerCase().includes(this.keywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(this.companies.length / this.itemsPerPage);
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredCompanies.slice(index, index + this.itemsPerPage);

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
