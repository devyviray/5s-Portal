<template>
    <div id="wrapper">
        <loader v-if="loading"></loader>
        <page-header></page-header>
        <div>
            <div>
                <breadcrumb :user-role-level="userRoleLevel" :user-id="userId"></breadcrumb>
            </div>
            <div>
                <div class="row row-margin">
                    <div class="col-md-12">
                        <div class="card">
                            <div id="page-inner">
                                <div class="card-header border-0">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="mb-0">Company Operation Line List</h3>
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
                                            <th scope="col">Operation Line</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(companyOperationLine, c) in filteredQueues" v-bind:key="c">
                                             <td class="text-right">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#editModal" style="cursor: pointer" @click="copyObject(companyOperationLine,c)">Edit</a>
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(companyOperationLine,c)">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ c + 1 }}</td>
                                            <td scope="row">{{ companyOperationLine[0].company_location.company.name }}</td>
                                            <td>{{ companyOperationLine[0].company_location.location.name }}</td>
                                            <td>
                                                <span v-for="(operationLine, o) in companyOperationLine" :key="o">
                                                    {{ operationLine.operation_line.name }} <br>
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
                                    <span>{{ filteredQueues.length }}  of {{ company_operation_lines.length }} Total Company Area(s)</span>
                                </div>
                            </div>
                        </div>
                    </div>
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
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Add Company Operation Line</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="company_operation_line_added">
                            <strong>Success!</strong> Company Operation Line succesfully added
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Company*</label> 
                                    <select class="form-control" v-model="company_operation_line.company" @change="changeCompany(company_operation_line.company)">
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
                                    <select class="form-control" v-model="company_operation_line.company_location">
                                        <option v-for="(company_location,c) in company_locations" v-bind:key="c" :value="company_location.id"> {{ company_location.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.location">{{ errors.location[0] }}</span>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Operation Line*</label> 
                                    <multiselect
                                        v-model="company_operation_line.operation_line"
                                        :options="operation_lines"
                                        :multiple="true"
                                        track-by="id"
                                        :custom-label="customLabel"
                                        placeholder="Select Operation Line"
                                    >
                                    </multiselect>
                                    <span class="text-danger" v-if="errors.operation_line">{{ errors.operation_line[0] }}</span>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="addCompanyOperationLine(company_operation_line)">Save</button>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Edit Modal -->
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
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Edit Company Operation Line</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="company_operation_line_updated">
                            <strong>Success!</strong> Company Operation Line succesfully updated
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Company*</label> 
                                    <select class="form-control" v-model="company_operation_line_copied.company" @change="changeCompany(company_operation_line_copied.company)" disabled>
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
                                    <select class="form-control" v-model="company_operation_line_copied.location" disabled>
                                        <option v-for="(company_location,c) in company_locations" v-bind:key="c" :value="company_location.id"> {{ company_location.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.location">{{ errors.location[0] }}</span>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Operation Line*</label> 
                                    <multiselect
                                        v-model="operationLinesEdit"
                                        :options="operation_lines"
                                        :multiple="true"
                                        track-by="id"
                                        :custom-label="customLabel"
                                        placeholder="Select Operation Line"
                                    >
                                    </multiselect>
                                    <span class="text-danger" v-if="errors.operation_line">{{ errors.operation_line[0] }}</span>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateCompanyOperationLine(company_operation_line_copied,operationLinesEdit)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Delete Company Operation Line</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Are you sure you want to delete this Company Operation Line?
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-warning" @click="deleteCompanyOperationLine">Delete</button>
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
        props: ['userName', 'userRoleLevel', 'userId'],
        components:{
            navbarRight,
            breadcrumb,
            Multiselect,
            loader
        },
        data(){
            return {
                company_operation_lines: [],
                company_operation_line: [],
                company_operation_line_copied: [],
                company_operation_line_id: '',
                company_operation_line_added: false,
                company_operation_line_updated: false, 
                companies:[],
                company_locations: [],
                operation_lines: [],
                operationLinesEdit:[],
                companyLocationId: '',
                index: '',
                errors: [],
                currentPage: 0,
                itemsPerPage: 15,
                keywords: '',
                loading: false,
                show_operation_line: false
            }
        },
        created(){
            this.fetchCompanies();
            this.fetchOperationLines();
            this.fetchCompanyOperationLines();
        },
        methods:{
            showLoader(){
               this.loading = true;
            },
            changeCategory(category){
                category == 1 ?  this.show_operation_line = true :  this.show_operation_line = false;
            },
            copyObject(company_operation_line,index){
                this.company_operation_line_copied = [];
                this.operationLinesEdit = [];
                company_operation_line.filter(operationLine => {
                    this.operationLinesEdit.push(operationLine.operation_line)
                });
            
                this.errors = [];
                this.company_operation_line_updated = false;
                this.company_operation_line_copied.company = company_operation_line[0].company_location.company.id;
                this.company_operation_line_copied.location = company_operation_line[0].company_location.location.id;
                this.companyLocationId = company_operation_line[0].company_location_id;
                this.changeCompany(this.company_operation_line_copied.company);
                this.index = index;
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
            customLabel (operation_line) {
                return `${ operation_line.name  }`
            },
            fetchCompanyOperationLines(){
                axios.get('company-operation-lines-all')
                .then(response => {
                    this.company_operation_lines = Object.values(response.data);
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            addCompanyOperationLine(company_operation_line){
                this.showLoader();
                var operationLine_ids = [];
                if(company_operation_line.operation_line){
                    company_operation_line.operation_line.forEach((operation_line) => {
                        operationLine_ids.push(operation_line.id)
                    });
                }
                document.getElementById('add_btn').disabled = true;
                this.errors = [];
                axios.post('/company-operation-line', {
                    company: company_operation_line.company,
                    location: company_operation_line.company_location,
                    operation_line: operationLine_ids,
                })
                .then(response => {
                    this.company_operation_line_added = true;
                    document.getElementById('add_btn').disabled = false;
                    this.company_operation_line = [];
                    this.loading = false;
                    location.reload();
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.company_operation_line_added = false;
                    document.getElementById('add_btn').disabled = false;
                    this.loading = false;
                })
            },
            updateCompanyOperationLine(company_operation_line_copied, operationLines){
                this.showLoader();
                document.getElementById('edit_btn').disabled = true;
                this.company_operation_line_updated = false;
                this.errors = [];
                // var index = this.company_areas.findIndex(item => item.id == company_operation_line_copied.id);
                var operationLine_ids = [];
                if(operationLines){
                    operationLines.forEach((operation_line) => {
                        operationLine_ids.push(operation_line.id)
                    });
                }
                axios.post(`/company-operation-line-edit/${this.companyLocationId}`, {
                    company: company_operation_line_copied.company,
                    location: company_operation_line_copied.location,
                    operation_line: operationLine_ids,
                    // _method: 'PATCH'
                })
                .then(response => {
                    this.company_operation_line_updated = true;
                    // this.company_areas.splice(index,1,response.data);
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                    location.reload();
                })
                .catch(error => {
                    this.company_operation_line_updated = false;
                    this.errors = error.response.data.errors;
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            deleteCompanyOperationLine(){
                this.showLoader();
                axios.delete(`/company-operation-line/${this.companyLocationId}`)
                .then(response => {
                    $('#deleteModal').modal('hide');
                    alert('Company Area successfully deleted');
                    // this.company_operation_lines.splice(this.index,1);
                    this.loading = false;
                    location.reload();
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
            filteredCompanyOperationLines(){
                let self = this;
                return self.company_operation_lines.filter(company_operation_line => {
                    return company_operation_line[0].company_location.company.name.toLowerCase().includes(this.keywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(this.filteredCompanyOperationLines.length / this.itemsPerPage);
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredCompanyOperationLines.slice(index, index + this.itemsPerPage);

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
