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
                <b>5S PORTAL - REPORT & RATING </b>
            </h1>
            <breadcrumb></breadcrumb>
        </div>
        <div id="page-inner">
            <div class="card">
                <div class="card-header">
                    <div class="row ml-2">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">Company</label>
                                <select class="form-control" v-model="company" @change="changeCompany(company, 'getCompanies')">
                                    <option v-for="(company,c) in companies" v-bind:key="c" :value="company"> {{ company.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.company  ">{{ errors.company }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">Company Location</label>
                                <select class="form-control" v-model="location" @change="changeCompany('', '')">
                                    <option v-for="(location,l) in locations" v-bind:key="l" :value="location"> {{ location.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.location  ">{{ errors.location }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">Operation Line</label>
                                <select class="form-control" v-model="operation_line" @change="changeCompany('', '')">
                                    <option v-for="(operation_line,o) in operation_lines" v-bind:key="o" :value="operation_line"> {{ operation_line.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.operation_line  ">{{ errors.operation_line }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">Category</label>
                                <select class="form-control" v-model="category"  @change="changeCompany('', '')">
                                    <option v-for="(category,c) in categories" v-bind:key="c" :value="category"> {{ category.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.category  ">{{ errors.category }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">AREA</label>
                                <select class="form-control" v-model="area"  @change="changeCompany('', '')">
                                    <option v-for="(area,a) in areas" v-bind:key="a" :value="area.area"> {{ area.area.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.area  ">{{ errors.area }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-sm btn-primary mt-4" @click="viewAction"> Go</button>
                            <button class="btn btn-sm btn-primary mt-4" @click="createReport"> Create Report</button>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
                
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th scope="col">#</th>
                            <th scope="col">Company</th>
                            <th scope="col">Operation Line</th>
                            <th scope="col">Category</th>
                            <th scope="col">Area</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(report, r) in reports" v-bind:key="r">
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" target="_blank" :href="viewReportLink+report[0].company.id+'/'+report[0].location.id+'/'+report[0].operation_line.id+'/'+report[0].category.id+'/'+report[0].area.id">View</a>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(report)">Delete</a>
                                    </div>
                                </div>
                            </td>
                            <td scope="row">{{ r }}</td>
                            <td>{{ report[0].company.name + ' ' + report[0].location.name }}</td>
                            <td>{{ report[0].operation_line.name }}</td>
                            <td>{{ report[0].category.name }} </td>
                            <td>{{ report[0].area.name }} </td>
                            <td>{{ report[0].status }} </td>
                        </tr>
                    </tbody>
                </table>
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
    export default {
        props: ['userName'],
        components:{
            Multiselect,
            navbarRight,
            breadcrumb
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
                itemsPerPage: 8,
                keywords: '',
            }
        },
        created(){
            this.fetchCompanies();
            this.fetchOperationLines();
            this.fetchCategories();
            this.fetchReports();
        },
        methods:{
            createReport(){
                return window.location.href = window.location.origin+'/create-report';
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
                    if(this.company && this.location && this.operation_line && this.category){
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
                axios.get(`/company-areas-per-company/${this.company.id}/${this.location.id}/${this.operation_line.id},${this.category.id}`)
                 .then(response => {
                    this.areas = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                });
            },
            viewAction(){
                this.errors = [];
                if(!this.company){
                    this.errors.company = 'This field is required';
                    this.report_show = false;
                }
                if(!this.location){
                    this.errors.location = 'This field is required';
                    this.report_show = false;
                }
                if(!this.operation_line){
                    this.errors.operation_line = 'This field is required';
                   this.report_show = false;
                }
                if(!this.category){
                    this.errors.category = 'This field is required';
                   this.report_show = false;
                }
                if(!this.area){
                    this.errors.area = 'This field is required';
                    this.report_show = false;
                }
                if(this.company && this.location && this.operation_line && this.category && this.area){
                    this.report_show = true;
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
            }, 
            showNextLinkView() {
                return this.currentPage == (this.totalPages - 1) ? false : true;
            }   
        },
        computed:{
            totalPages() {
                return Math.ceil(Object.values(this.selected_checklist).length / this.itemsPerPage);
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = Object.values(this.selected_checklist).slice(index, index + this.itemsPerPage);

                if(this.currentPage >= this.totalPages) {
                    this.currentPage = this.totalPages - 1
                }
                if(this.currentPage == -1) {
                    this.currentPage = 0;
                }
                return queues_array;
            },
            totalPagesView() {
                return Math.ceil(Object.values(this.reportsPerUser).length / this.itemsPerPage);
            },
            filteredQueuesView() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = Object.values(this.reportsPerUser).slice(index, index + this.itemsPerPage);

                if(this.currentPage >= this.totalPagesView) {
                    this.currentPage = this.totalPagesView - 1
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
            }
        }
    }
</script>
