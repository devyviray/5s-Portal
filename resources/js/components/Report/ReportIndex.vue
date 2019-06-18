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
                <b>5S PORTAL - REPORT & RATING </b>
            </h1>
            <breadcrumb></breadcrumb>
        </div>
        <div id="page-inner">
            <div class="card">
                <div class="card-header" v-if="userRole > 2">
                    <div class="row ml-2">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <button class="btn btn-sm btn-primary" @click="createReport"> Create Report</button>
                            <button class="btn btn-sm btn-primary" @click="trendAndAnalysis"> Trend and Analysis</button> 
                        </div>
                    </div>
                    <div class="row ml-2">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">Company</label>
                                <select class="form-control" v-model="company" @change="changeCompany(company, 'getCompanies')">
                                    <option v-for="(company,c) in companies" v-bind:key="c" :value="company"> {{ company.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.company  ">{{ errors.company[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">Location</label>
                                <select class="form-control" v-model="location" @change="changeCompany('', '')">
                                    <option v-for="(location,l) in locations" v-bind:key="l" :value="location"> {{ location.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.location  ">{{ errors.location[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">Category</label>
                                <select class="form-control" v-model="category"  @change="changeCompany('', '')">
                                    <option v-for="(category,c) in categories" v-bind:key="c" :value="category"> {{ category.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.category  ">{{ errors.category[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-2" v-if="show_operation_line">
                            <div class="form-group">
                                <label class="form-control-label" for="role">Operation Line</label>
                                <select class="form-control" v-model="operation_line" @change="changeCompany('', '')">
                                    <option v-for="(operation_line,o) in operation_lines" v-bind:key="o" :value="operation_line"> {{ operation_line.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.operation_line  ">{{ errors.operation_line[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">AREA</label>
                                <select class="form-control" v-model="area"  @change="changeCompany('', '')">
                                    <option v-for="(area,a) in areas" v-bind:key="a" :value="area"> {{ area.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.area  ">{{ errors.area[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-sm btn-primary mt-4" @click="fetchFilteredReport"> Filter</button>
                        </div>
                    </div>
                </div>
                
                <table class="table align-items-center table-flush ">
                    <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th scope="col">ID</th>
                            <th scope="col">Process Owner</th>
                            <th scope="col">Company</th>
                            <th scope="col">Category</th>
                            <th scope="col">Operation Line</th>
                            <th scope="col">Area</th>
                            <th scope="col">Inspector</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(report, r) in filteredQueues" v-bind:key="r">
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" target="_blank" :href="viewReportLink+report.id">View</a>
                                        <a v-if="report.status == 2" class="dropdown-item" target="_blank" :href="verifiedReportLink+report.id">Validate</a>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(report)">Delete</a>
                                    </div>
                                </div> 
                            </td>
                            <td scope="row">{{ report.id }}</td>
                            <td>{{ report.process_owner.name }}</td>
                            <td>{{ report.company.name + ' ' + report.location.name }}</td>
                            <td>{{ report.category.name }} </td>
                            <td v-if="report.operation_line">{{ report.operation_line.name }}</td>
                            <td v-else></td>
                            <td>{{ report.area.name }} </td>
                            <td>{{ report.inspector.name }}</td>
                            <td>{{ reportStatus(report.status) }} </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mb-3 mt-3 ml-3" v-if="filteredQueues.length ">
            <div class="col-6">
                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
            </div>
            <div class="col-6 text-right">
                <span>{{ filteredQueues.length }} Filtered Report(s)</span><br>
                <span>{{ this.reports.length }} Total Report(s)</span>
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
        props: ['userName', 'userRole'],
        components:{
            Multiselect,
            navbarRight,
            breadcrumb,
            loader
        },
        data(){
            return {
                reports: [],
                companies : [],
                locations: [],
                operation_lines: [],
                categories: [],
                areas: [],
                company: '',
                location: '',
                operation_line: '',
                category: '',
                area: '',
                report_show: false,
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
            this.fetchCategories();
            this.fetchReports();
        },
        methods:{
            showLoader(){
               this.loading = true;
            },
            createReport(){
                return window.location.href = window.location.origin+'/create-report';
            },
            trendAndAnalysis(){
                return window.location.href = window.location.origin+'/trend-and-analysis';
            },
            changeCompany(company,action){
                if(action == 'getCompanies'){
                    axios.get(`/company-location/${company.id}`)
                    .then(response => { 
                        this.locations = response.data.locations;
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    })
                }else {
                    if(this.category.id == 1){
                         this.show_operation_line = true
                    }else{
                        this.show_operation_line = false;
                        this.operation_line = '';
                    }
                    if(this.company && this.location && this.category){
                        this.fetchCompayAreas();
                    }
                }
            },
            fetchReports(){
                 axios.get('/reports-all')
                 .then(response => {
                    this.reports = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                }); 
            },
            fetchCompanies(){
                axios.get('/companies-all')
                 .then(response => {
                    this.companies = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                }); 
            },
            fetchOperationLines(){
                axios.get('/operation-lines-all')
                 .then(response => {
                    this.operation_lines = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                }); 
            },
            fetchCategories(){
                axios.get('/categories-all')
                 .then(response => {
                    this.categories = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                }); 
            },
            fetchCompayAreas(){
                var operationId = this.operation_line ? this.operation_line.id : 0;
                axios.get(`/company-areas-per-company/${this.company.id}/${this.location.id}/${this.category.id}/${operationId}`)
                 .then(response => {
                    this.areas = response.data[0].areas;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                });
            },
            fetchFilteredReport(){
                axios.post('/report-filtered', {
                    company: this.company.id,
                    location: this.location.id,
                    operation_line: this.operation_line.id,
                    category: this.category.id,
                    area: this.area.id
                })
                .then(response => { 
                    this.reports = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors
                })
            },
            reportStatus(status){
                switch(status){
                    case 1:
                        return 'Pending'; 
                        break;
                    case 2: 
                        return 'For Checking';
                        break;
                    case 3: 
                        return 'Validated';
                        break;
                    case 4: 
                        return 'Report Final'
                        break;
                    default:
                }
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
            totalPages() {
                return Math.ceil(this.reports.length / this.itemsPerPage);
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.reports.slice(index, index + this.itemsPerPage);

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
            viewReportLink(){
               return window.location.origin+'/view-report/'; 
            },
            verifiedReportLink(){
                return window.location.origin+'/validate-report/'; 
            }
        }
    }
</script>
