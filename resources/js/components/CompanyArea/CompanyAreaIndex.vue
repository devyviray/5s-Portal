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
                    <b>5S PORTAL - COMPANY AREA</b>
                </h1>
                <breadcrumb></breadcrumb>
            </div>
            <div id="page-inner">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Company Area List</h3>
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
                            <th scope="col">Category</th>
                            <th scope="col">Operation Line</th>
                            <th scope="col">Area</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(companyArea, c) in filteredQueues" v-bind:key="c">
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" data-toggle="modal" data-target="#editModal" style="cursor: pointer" @click="copyObject(companyArea)">Edit</a>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(companyArea)">Delete</a>
                                    </div>
                                </div>
                            </td>
                            <td scope="row">{{ companyArea.id }}</td>
                            <td>{{ companyArea.company.name }}</td>
                            <td>{{ companyArea.location.name }}</td>
                            <td>{{ companyArea.category.name }}</td>
                            <td v-if="companyArea.operation_line">{{ companyArea.operation_line.name }}</td>
                            <td v-else></td>  
                            <td>
                                <span v-for="(area, a) in companyArea.areas" :key="a">
                                    {{ area.name }} <br>
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
                    <span>{{ filteredQueues.length }} Filtered Company Area(s)</span><br>
                    <span>{{ company_areas.length }} Total Company Area(s)</span>
                </div>
            </div>
        </div>
        <!-- Add Company Area Modal -->
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
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Add Company Area</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="company_area_added">
                            <strong>Success!</strong> Company Area succesfully added
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Company*</label> 
                                    <select class="form-control" v-model="company_area.company" @change="changeCompany(company_area.company)">
                                        <option v-for="(company,c) in companies" v-bind:key="c" :value="company.id"> {{ company.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.company">{{ errors.company[0] }}</span>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Company Location*</label> 
                                    <select class="form-control" v-model="company_area.company_location">
                                        <option v-for="(company_location,c) in company_locations" v-bind:key="c" :value="company_location.id"> {{ company_location.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.location">{{ errors.location[0] }}</span>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Category*</label>
                                    <select class="form-control" v-model="company_area.category" @change="changeCategory(company_area.category)">
                                        <option v-for="(category,c) in categories" v-bind:key="c" :value="category.id"> {{ category.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.category">{{ errors.category[0] }}</span>
                                </div>
                            </div>  
                        </div>
                        <div class="row" v-if="show_operation_line">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Operation Line*</label> 
                                    <select class="form-control" v-model="company_area.operation_line">
                                        <option v-for="(operation_line,o) in operation_lines" v-bind:key="o" :value="operation_line.id"> {{ operation_line.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.operation_line">The Operation Line field is required.</span>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Areas*</label> 
                                    <multiselect
                                        v-model="company_area.areas"
                                        :options="areas"
                                        :multiple="true"
                                        track-by="id"
                                        :custom-label="customLabel"
                                        placeholder="Select Area"
                                    >
                                    </multiselect>
                                    <span class="text-danger" v-if="errors.areas">{{ errors.areas[0] }}</span>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="addCompanyArea(company_area)">Save</button>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Edit Company Area Modal -->
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
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Edit Company Area</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="company_area_updated">
                            <strong>Success!</strong> Company Area succesfully updated
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Company*</label> 
                                    <select class="form-control" v-model="company_area_copied.company_id" @change="changeCompany(company_area_copied.company)">
                                        <option v-for="(company,c) in companies" v-bind:key="c" :value="company.id"> {{ company.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.company">{{ errors.company[0] }}</span>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Company Location*</label> 
                                    <select class="form-control" v-model="company_area_copied.location_id">
                                        <option v-for="(company_location,c) in company_locations" v-bind:key="c" :value="company_location.id"> {{ company_location.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.company_location">{{ errors.company_location[0] }}</span>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Category*</label>
                                    <select class="form-control" v-model="company_area_copied.category_id" @change="changeCategory(company_area_copied.category_id)">
                                        <option v-for="(category,c) in categories" v-bind:key="c" :value="category.id"> {{ category.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.category">{{ errors.category[0] }}</span>
                                </div>
                            </div>  
                        </div>
                        <div class="row" v-if="show_operation_line">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Operation Line*</label> 
                                    <select class="form-control" v-model="company_area_copied.operation_line_id">
                                        <option v-for="(operation_line,o) in operation_lines" v-bind:key="o" :value="operation_line.id"> {{ operation_line.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.operation_line">{{ errors.operation_line[0] }}</span>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Areas*</label> 
                                    <multiselect
                                        v-model="company_area_copied.areas"
                                        :options="areas"
                                        :multiple="true"
                                        track-by="id"
                                        :custom-label="customLabel"
                                        placeholder="Select Area"
                                    >
                                    </multiselect>
                                    <span class="text-danger" v-if="errors.area_id">{{ errors.area_id[0] }}</span>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateCompanyArea(company_area_copied)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Company Area Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Delete Company Area</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Are you sure you want to delete this Company Area?
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-warning" @click="deleteCompanyArea">Delete</button>
                </div>
                </div>
            </div>
        </div>

</div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<script>
    import navbarRight from '../NavbarRight';
    import breadcrumb from '../Breadcrumb';
    import Multiselect from 'vue-multiselect';
    import loader from '../Loader';
    export default {
        props: ['userName', 'userRole', 'userId'],
        components:{
            navbarRight,
            breadcrumb,
            Multiselect,
            loader
        },
        data(){
            return {
                company_areas : [],
                company_area: [],
                company_area_copied: [],
                company_area_id: '',
                company_area_added: false,
                company_area_updated: false, 
                companies:[],
                company_locations: [],
                operation_lines:[],
                categories:[],
                areas:[],
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: '',
                loading: false,
                show_operation_line: false
            }
        },
        created(){
            this.fetchCompanyAreas();
            this.fetchCompanies();
            this.fetchOperationLines();
            this.fetchCategories();
            this.fetchAreas();
        },
        methods:{
            showLoader(){
               this.loading = true;
            },
            changeCategory(category){
                category == 1 ?  this.show_operation_line = true :  this.show_operation_line = false;
            },
            copyObject(company_area){
                this.errors = [];
                this.company_area_updated = false;
                this.company_area_id = company_area.id;
                this.company_area_copied = Object.assign({}, company_area);
                this.company_area_copied.company_id = company_area.company.id;
                this.company_area_copied.location_id = company_area.location.id;
                this.company_area_copied.category_id = company_area.category.id;
                this.company_area_copied.operation_line_id = company_area.operation_line ? company_area.operation_line.id : '';
                // this.company_area_copied.area = company_area.areas;
                this.changeCompany(this.company_area_copied.company_id);
                company_area.category.id == 1 ? this.show_operation_line = true : this.show_operation_line = false;

            },
            changeCompany(id){
                axios.get(`/company-location/${id}`)
                .then(response => { 
                    this.company_locations = response.data.locations;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            },
            fetchCompanyAreas(){
                axios.get('company-areas-all')
                .then(response => {
                    this.company_areas = response.data;
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
            fetchOperationLines(){
                 axios.get('operation-lines-all')
                .then(response => {
                    this.operation_lines = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            fetchAreas(){
                axios.get('areas-all')
                .then(response => {
                    this.areas = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            customLabel (area) {
                return `${ area.name  }`
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
            addCompanyArea(company_area){
                this.showLoader();
                var areas_ids = [];
                if(company_area.areas){
                    company_area.areas.forEach((area) => {
                        areas_ids.push(area.id)
                    });
                }

                document.getElementById('add_btn').disabled = true;
                this.errors = [];
                axios.post('/company-area', {
                    company: company_area.company,
                    location: company_area.company_location,
                    category: company_area.category,
                    operation_line: company_area.category == 1 ? company_area.operation_line : '',
                    areas: areas_ids
                })
                .then(response => {
                    this.company_area_added = true;
                    document.getElementById('add_btn').disabled = false;
                    this.fetchCompanyAreas();
                    this.company_area = [];
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.company_area_added = false;
                    document.getElementById('add_btn').disabled = false;
                    this.loading = false;
                })
            },
            updateCompanyArea(company_area_copied){
                this.showLoader();
                document.getElementById('edit_btn').disabled = true;
                this.company_area_updated = false;
                this.errors = [];
                var index = this.company_areas.findIndex(item => item.id == company_area_copied.id);

                var areas_ids = [];
                if(company_area_copied.areas){
                    company_area_copied.areas.forEach((area) => {
                        areas_ids.push(area.id)
                    });
                }
                axios.post(`/company-area/${company_area_copied.id}`, {
                    company: company_area_copied.company_id,
                    location_id: company_area_copied.location_id,
                    operation_line: company_area_copied.operation_line_id,
                    category_id: company_area_copied.category_id,
                    areas: areas_ids,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.company_area_updated = true;
                    this.company_areas.splice(index,1,response.data);
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
                .catch(error => {
                    this.company_area_updated = false;
                    this.errors = error.response.data.errors;
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            deleteCompanyArea(){
                this.showLoader();
                var index = this.company_areas.findIndex(item => item.id == this.company_area_id);
                axios.delete(`/company-area/${this.company_area_id}`)
                .then(response => {
                    $('#deleteModal').modal('hide');
                    alert('Company Area successfully deleted');
                    this.company_areas.splice(index,1);
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
            filteredCompanyAreas(){
                let self = this;
                return self.company_areas.filter(company_area => {
                    return company_area.company.name.toLowerCase().includes(this.keywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(this.company_areas.length / this.itemsPerPage);
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredCompanyAreas.slice(index, index + this.itemsPerPage);

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
